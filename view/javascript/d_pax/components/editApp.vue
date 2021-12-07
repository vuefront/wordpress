<template lang="pug">
  modal(:value="edit" class="edit-app" btn-close @cancel="handleClose")
    div(class="edit-app__title") {{$t('text_title')}}
    validation-observer(v-slot="{validate}")
      b-form(class="edit-app__form" @submit.stop.prevent="validate().then(onSubmit)")
        validation-provider(v-slot="{errors, valid}" :name="$t('text_url')" rules="required")
          b-form-group(:label="$t('text_callback_url')" label-for="input-callback-url")
            b-form-input(id="input-callback-url" v-model="form.eventUrl")
        b-form-group(:label="$t('text_auth_url')" label-for="input-auth-url")
          b-form-input(id="input-auth-url" v-model="form.authUrl")
        b-form-group(:label="$t('text_jwt')" label-for="input-jwt")
          b-form-input(id="input-jwt" v-model="form.jwt")
        div(class="edit-app__submit_button")
          b-button(type="submit" size="lg" variant="success") {{$t('text_save')}}
</template>
<script>
import Modal from './modal'
import {mapGetters} from 'vuex'
export default {
  components: {
    Modal
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
      form: {
        eventUrl: this.app.eventUrl || '',
        authUrl: this.app.authUrl || '',
        jwt: this.app.jwt || ''
      }
    }
  },
  computed: {
    ...mapGetters({
      edit: 'apps/edit'
    })
  },
  methods: {
    handleClose() {
      this.$store.commit('apps/setEdit', false)
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
  "text_title": "Edit app",
  "text_url": "URL Site",
  "text_callback_url": "Url for event",
  "text_access_key": "Key for access to admin api",
  "text_auth_url": "Auth url",
  "text_jwt": "App JWT",
  "text_save": "Save"
}
</i18n>
<style lang="scss">
.edit-app {
  &__submit_button {
    text-align: center;
  }
  .vf-modal {
      .vf-modal-dialog {
        .vf-modal-content {
          .vf-modal-body {
            padding-top: 50px!important;
            padding-bottom: 50px!important;
            padding-left: 110px;
            padding-right: 110px;
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
