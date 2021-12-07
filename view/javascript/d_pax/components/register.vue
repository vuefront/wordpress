<template>
  <div
    id="vf-login-form"
    class="register-page login-page"
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
        <b-row>
          <b-col>
            <validation-provider
              v-slot="{errors, valid}"
              :name="$t('entryFirstName')"
              rules="required|min:1"
            >
              <b-form-group
                :label="$t('entryFirstName')"
                :state="errors[0] ? false : (valid ? true : null)"
                :invalid-feedback="errors[0]"
                label-for="input-first-name"
              >
                <b-form-input
                  id="input-first-name"
                  v-model="form.firstName"
                  :state="errors[0] ? false : (valid ? true : null)"
                  size="lg"
                />
              </b-form-group>
            </validation-provider>
          </b-col>
          <b-col>
            <validation-provider
              v-slot="{errors, valid}"
              :name="$t('entryLastName')"
              rules="required|min:1"
            >
              <b-form-group
                :label="$t('entryLastName')"
                :state="errors[0] ? false : (valid ? true : null)"
                :invalid-feedback="errors[0]"
                label-for="input-last-name"
              >
                <b-form-input
                  id="input-last-name"
                  v-model="form.lastName"
                  :state="errors[0] ? false : (valid ? true : null)"
                  size="lg"
                />
              </b-form-group>
            </validation-provider>
          </b-col>
        </b-row>
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
            {{ $t('button_sign_up') }}
          </b-button>
        </div>
        <div class="text-center">
          <a
            class="login-page__button_logout"
            @click="handleLogout"
          >
            {{ $t('buttonLogout') }}
          </a>
        </div>
      </b-form>
    </validation-observer>
  </div>
</template>
<script>
import { mapGetters } from "vuex";
export default {
  data() {
    return {
      form: {
        firstName: "",
        lastName: "",
        email: this.$store.getters['auth/email'],
        password: ""
      },
      loading: false
    };
  },
  computed: {
    ...mapGetters({
      error: "error"
    })
  },
  methods: {
    handleLogout() {
      this.$store.dispatch('auth/logout')
      this.$emit('check')
    },
    async onSubmit (valid) {
      if (!valid) {
        return
      }
      this.loading = true

      await this.$store.dispatch("auth/register", {email: this.form.email, password: this.form.password, firstName: this.form.firstName, lastName: this.form.lastName})

      if(!this.error) {
        this.$store.commit('auth/toggleShowLogin')
      }

      this.loading = false
    }
  }
};
</script>
<i18n locale="en">
{
  "entryFirstName": "First Name",
  "entryLastName": "Last Name",
  "text_password": "Password",
  "button_sign_up": "Sign up",
  "buttonLogout": "Try another email"
}
</i18n>
