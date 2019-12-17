import Vue from 'vue'
/**
 * Search and connect layouts
 * @author Dreamvention <info@dreamvention.com>
 * @date 20.12.2018
 */
export default (options, inject) => {
  let req = require.context('~/layouts/', false, /\.vue$/)
  const files = req.keys()
  for (const key in files) {
    const fileName = files[key]
    const component = req(fileName).default
    let layoutName = fileName.replace('./', '')
    layoutName = layoutName.replace('.vue', '')
    Vue.component('layout-' + layoutName, component)
  }
}
