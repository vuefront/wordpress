import Vue from 'vue'
import { load } from '~/core/utils'
import App from './App.vue'
import store from '~/core/utils/store'
import i18n from '~/core/utils/i18n'
import { initRouter } from '~/core/utils/router'
import paxConfig from '~/pax.config.js'
import messagesEn from '~/locales/en'
import { isUndefined } from 'lodash'
/**
 * Main application file
 * @author Dreamvention <info@dreamvention.com>
 * @date 20.12.2018
 */
/**
 *Creating the main component for the application
 */
Vue.component(`${paxConfig.prefix}-app`, App)

let app = {}
let injectVars = {}

const inject = (key, value) => {
  if (isUndefined(app[key])) {
    key = '$' + key
    app[key] = value
    store[key] = value
    injectVars[key] = value
    Vue.use(() => {
      if (!Vue.prototype.hasOwnProperty(key)) {
        Object.defineProperty(Vue.prototype, key, {
          get() {
            return this.$root.$options[key]
          }
        })
      }
    })
  }
}

/**
 * Determination of the main function of the application
 */
window[paxConfig.codename] = async options => {
  await load({ ...options, app, store, ...paxConfig, ...injectVars }, inject)
  const router = initRouter({ store })
  inject('paxConfig', paxConfig)
  inject('paxOptions', options)
  store.$router = router
  await store.dispatch('clientInit')
  app = new Vue({
    ...injectVars,
    store,
    router,
    i18n
  })

  app.$mount(options.selector)
}
