import Vue from 'vue'
import VueRouter from 'vue-router'
import { isUndefined, isArray, isString } from 'lodash'
import paxConfig from '~/pax.config.js'
/**
 * Setting up and connecting the router.
 * @author Dreamvention <info@dreamvention.com>
 * @date 20.12.2018
 */
Vue.use(VueRouter)
let redirected = false

const redirect = (router, next) => {
  return path => {
    redirected = true
    next(path)
  }
}

const loadMiddleware = async (component, context) => {
  if (isArray(component.middleware)) {
    for (const item of component.middleware) {
      await require(`~/middleware/${item}`).default(context)

    }
  } else if (isString(component.middleware)) {
    await require(`~/middleware/${components.middleware}`).default(context)
  }
}

export function initRouter(context) {
  const req = require.context('~/pages/', true, /\.vue$/)
  let router = null
  let routes = []
  req.keys().forEach(fileName => {
    let pageName = fileName.replace('./', '/')
    pageName = pageName.replace('.vue', '')
    pageName = pageName.replace('__', ':')
    if (pageName.indexOf('/index') > 0) {
      pageName = pageName.replace('/index', '')
    }
    pageName = pageName.replace('index', '')
    let component = req(fileName).default
    component.beforeEach = async (to, from, next) => {}
    /**
     * Function to wait for actions to be taken before opening the page.
     * @param {any} to Page with which the transition was made
     * @param {any} from The page to which the transition
     * @param {any} next Continued loading page
     */
    component.beforeRouteEnter = async (to, from, next) => {
      redirected = false
      if (!isUndefined(component.middleware)) {
        await loadMiddleware(component, {
          router,
          route: to,
          redirect: redirect(router, next),
          ...context
        })
      }
      if (!isUndefined(component.layout)) {
        const compLayout = require(`~/layouts/${component.layout}`).default
        if (!isUndefined(compLayout.middleware)) {
          await loadMiddleware(compLayout, {
            router,
            route: to,
            redirect: redirect(router, next),
            ...context
          })
        }
      } else {
        const compLayout = require('~/layouts/default').default
        if (!isUndefined(compLayout.middleware)) {
          await loadMiddleware(compLayout, {
            router,
            route: to,
            redirect: redirect(router, next),
            ...context
          })
        }
      }
      if (!isUndefined(component.fetch)) {
        await component.fetch({
          router,
          route: to,
          redirect: redirect(router, next),
          ...context
        })
      }
      if (!redirected) {
        next()
      }
    }

    routes = [...routes, { path: pageName, component }]
  })

  const mode = !isUndefined(paxConfig.router.mode)
    ? paxConfig.router.mode
    : 'hash'

  router = new VueRouter({
    mode,
    routes,
    fallback: false
  })

  if (!isUndefined(paxConfig.router.default)) {
    router.replace(paxConfig.router.default)
  }

  return router
}
