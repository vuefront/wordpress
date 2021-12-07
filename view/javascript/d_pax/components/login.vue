<template>
  <div
    id="vf-login-form"
    class="login-page"
  >
    <h3 class="font-weight-normal login-page__email">
      {{ form.email }}
    </h3>
    <b-alert
      :show="!!error"
      variant="danger"
      dismissible
    >
      {{ $t(error) }}
    </b-alert>
    <validation-observer v-slot="{validate}">
      <b-form
        class="login-page__form"
        @submit.stop.prevent="validate().then(onSubmit)"
      >
        <validation-provider
          v-slot="{errors, valid}"
          :name="$t('text_password')"
          rules="required|min:8"
        >
          <b-form-group
            :label="$t('text_password')"
            :state="errors[0] ? false : (valid ? true : null)"
            :invalid-feedback="errors[0]"
            label-for="input-password"
          >
            <b-form-input
              id="input-password"
              v-model="form.password"
              :state="errors[0] ? false : (valid ? true : null)"
              size="lg"
              type="password"
            />
          </b-form-group>
        </validation-provider>
        <div class="text-center login-page__button_submit">
          <b-button
            :disabled="loading"
            type="submit"
            variant="success"
            size="lg"
          >
            <b-spinner
              v-if="loading"
              type="grow"
            />
            {{ $t('button_sign_in') }}
          </b-button>
          <div class="text-center">
            <a
              class="login-page__button_logout"
              @click="handleLogout"
            >
              {{ $t('buttonLogout') }}
            </a>
          </div>
        </div>
      </b-form>
    </validation-observer>
  </div>
</template>
<script>
import {mapGetters} from 'vuex'
export default {
  middleware: ['notAuthenticated', 'withEmail'],
  data() {
    return {
      form: {
        email: this.$store.getters['auth/email'],
        password: ''
      },
      loading: false
    }
  },
  computed: {
    ...mapGetters({
      error: "error",
      account: "account/get"
    })
  },
  methods: {
    handleLogout() {
      this.$store.dispatch('auth/logout')
      this.$emit('check')
      // this.$router.push('/check')
    },
    async onSubmit (valid) {
      if (!valid) {
        return
      }
      this.loading = true

      await this.$store.dispatch("auth/login", {email: this.form.email, password: this.form.password})

      if(!this.error) {

        await this.$store.dispatch('cms/list')
        const id = await this.$store.dispatch('cms/search')
        if (this.$store.getters['cms/alien']) {
          this.$store.commit('auth/toggleShowLogin')
        } else {
          if(id) {
            await this.$store.dispatch('cms/load', {id})
          }
          this.$store.commit('auth/toggleShowLogin')
        }
      } else {
        if (this.error == 'email_banned') {
          this.$store.commit('account/setBanned', true)
          this.$store.commit('setResponseError', false)
          this.$store.commit('auth/toggleShowLogin')
        }
      }


      this.loading = false
    }
  }
}
</script>
<style lang="scss">
  #vf-login-form.login-page.login-page.login-page {
    padding-top: 60px;

    .login-page {
      &__email {
        text-transform: none;
        margin: 0 0 30px !important;
        font-size: 28px;
        line-height: 1.2;
        font-family: 'Open Sans', sans-serif;
        border: 0;
        padding: 0;
        height: auto;
      }
      &__button_submit {
        margin-top: 30px;
      }
      &__button_logout {
        display: block;
        cursor: pointer;
        margin-top: 55px;
        font-family: 'Open Sans', sans-serif;
        font-size: 18px;
        font-weight: 600;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.11;
        letter-spacing: 0.18px;
        text-align: center;
      }
    }
  }
</style>
<i18n locale="en">
{
  "text_password": "Password",
  "button_sign_in": "Sign In",
  "incorrect_email_or_password": "Incorrent Email or Password",
  "buttonLogout": "Try another email"
}
</i18n>
