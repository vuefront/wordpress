import Vue from 'vue'
import moment from 'moment'

export default ({ tokenUrl, baseURL, app, store, apiURL }, inject) => {
  inject('moment', moment)
}
