<template lang="pug">
  div(id="sign-in-modal" class="sign-in-modal")
    modal(:value="true" btn-close @cancel="handleClose")
      div(class="text-center vf-module-content__image")
        img(:src="require('~/assets/img/logo.svg')")
      check(v-if="step === 'check'" @login="step = 'login'" @register="step = 'register'")
      login(v-if="step === 'login'" @check="step = 'check'")
      register(v-if="step === 'register'" @check="step = 'check'")
</template>
<script>
import Modal from './modal'
import {mapGetters} from 'vuex'
import Check from '~/components/check'
import Banned from '~/components/banned'
import Register from '~/components/register'
import Login from '~/components/login'
import Alien from '~/components/alien'
export default {
  components: {
    Modal,
    Login,
    Check,
    Banned,
    Register,
    Alien
  },
  props: {
    app: {
      type: Object,
      default() {
        return null
      }
    },
    id: {
      type: Number,
      default() {
        return 0
      }
    }
  },
  data() {
    return {
      step: 'check'
    }
  },
  computed: {
    ...mapGetters({
      edit: 'apps/edit'
    })
  },
  methods: {
    handleClose() {
      this.$store.commit('auth/toggleShowLogin', false)
    },
    async onSubmit(valid) {
      if (valid) {
        await this.$store.dispatch('apps/edit', {key: this.id, app: {
          eventUrl: this.form.eventUrl,
          authUrl: this.form.authUrl,
          jwt: this.form.jwt,
        }})
        await this.$store.dispatch('apps/list')
        this.$store.commit('apps/setEdit', false)
      }
    }
  }
}
</script>
<i18n locale="en">
{
  "text_title": "Sign In",
  "text_url": "URL Site",
  "text_callback_url": "Url for event",
  "text_access_key": "Key for access to admin api",
  "text_auth_url": "Auth url",
  "text_jwt": "App JWT",
  "text_save": "Save"
}
</i18n>
<style lang="scss">
#sign-in-modal.sign-in-modal {

  &__submit_button {
    text-align: center;
  }
  .vf-modal {
      > .vf-modal-dialog {
        width: 700px;
        > .vf-modal-content {
          > .vf-modal-body {

            padding-top: 50px!important;
            padding-bottom: 50px!important;
            padding-left: 110px;
            padding-right: 110px;
            color: black !important;;
          }
        }
      }
    }
     &__title {
      font-family: 'Open Sans', sans-serif;
      font-size: 30px;
      font-weight: 600;
      font-stretch: normal;
      font-style: normal;
      line-height: 1.25;
      letter-spacing: 0.4px;
      text-align: center;
      color: $black;
      margin-bottom: 20px;
    }
    &__subtitle {
      margin-bottom: 20px;
    }
}
</style>
