import Vue from 'vue'
import Vuex from 'vuex'
import { camelCase, merge } from 'lodash'
/**
 * Vuex Settings and Connection.
 * @author Dreamvention <info@dreamvention.com>
 * @date 20.12.2018
 */

let storeData = {}
let store = {}
let files

const updateModules = () => {
  files = require.context('~/store/', true, /\.js$/)
  const filenames = files.keys()

  const indexFilename = filenames.find(filename =>
    filename.includes('./index.')
  )

  if (indexFilename) {
    storeData = getModule(indexFilename)
  }

  if (!storeData.modules) {
    storeData.modules = {}
  }

  for (const filename of filenames) {
    let name = filename.replace(/^\.\//, '').replace(/\.(js)$/, '')
    if (name === 'index') continue

    const namePath = name.split(/\//)

    name = namePath[namePath.length - 1]

    if (['state', 'getters', 'actions', 'mutations'].includes(name)) {
      const module = getModuleNamespace(storeData, namePath, true)
      appendModule(module, filename, name)
      continue
    }

    const isIndex = name === 'index'
    if (isIndex) {
      namePath.pop()
    }
    const module = getModuleNamespace(storeData, namePath)
    const fileModule = getModule(filename)
    name = namePath.pop()
    module[name] = module[name] || {}

    if (!isIndex) {
      module[name] = merge({}, module[name], fileModule)
      module[name].namespaced = true
      continue
    }

    const appendedMods = {}
    if (module[name].appends) {
      appendedMods.appends = module[name].appends
      for (const append of module[name].appends) {
        appendedMods[append] = module[name][append]
      }
    }

    module[name] = merge({}, module[name], appendedMods, fileModule)
    module[name].namespaced = true
  }
  if (module.hot) {
    module.hot.accept(files.id, () => {
      updateModules()

      store.hotUpdate(storeData)
    })
  }
}

Vue.use(Vuex)
updateModules()

const debug = process.env.NODE_ENV !== 'production'

store = new Vuex.Store({
  strict: debug,
  ...storeData
})

function getModule(filename) {
  const file = files(filename)
  const module = file.default || file
  return module
}

function getModuleNamespace(storeData, namePath, forAppend = false) {
  if (namePath.length === 1) {
    if (forAppend) {
      return storeData
    }
    return storeData.modules
  }
  const namespace = namePath.shift()
  storeData.modules[namespace] = storeData.modules[namespace] || {}
  storeData.modules[namespace].namespaced = true
  storeData.modules[namespace].modules =
    storeData.modules[namespace].modules || {}
  return getModuleNamespace(storeData.modules[namespace], namePath, forAppend)
}

function appendModule(module, filename, name) {
  const file = files(filename)
  module.appends = module.appends || []
  module.appends.push(name)
  module[name] = file.default || file
}

export default store
