import Vue from 'vue'
import { ValidationProvider, extend, ValidationObserver } from 'vee-validate'
import { required, email, min, max, numeric, confirmed } from 'vee-validate/dist/rules'
import isDecimal from 'validator/lib/isDecimal'

Vue.component('ValidationProvider', ValidationProvider)
Vue.component('ValidationObserver', ValidationObserver)

extend('email', email)
extend('required', required)
extend('min', min)
extend('max', max)
extend('numeric', numeric)
extend('confirmed', confirmed)
extend('decimal', {
  validate: val => isDecimal(val.toString()),
  message: 'Please enter a valid number'
})
