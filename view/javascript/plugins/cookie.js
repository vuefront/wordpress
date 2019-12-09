import Vue from 'vue'
import VueCookie from 'vue-cookie'

export default ({ tokenUrl, baseURL, app, store, apiURL }, inject) => {
  inject('cookie', VueCookie)
}
