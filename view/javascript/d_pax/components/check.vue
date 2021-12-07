<template>
  <div class="check-page">
    <b-alert
      :show="!!error"
      variant="danger"
      dismissible
    >
      {{ $t(error) }}
    </b-alert>
    <validation-observer v-slot="{validate}">
      <b-form @submit.stop.prevent="validate().then(onSubmit)">
        <validation-provider
          v-slot="{errors, valid}"
          :name="$t('text_email')"
          rules="required|email"
        >
          <b-form-group
            :label="$t('text_email')"
            :state="errors[0] ? false : (valid ? true : null)"
            :invalid-feedback="errors[0]"
            label-for="input-email"
          >
            <b-form-input
              id="input-email"
              v-model="form.email"
              :state="errors[0] ? false : (valid ? true : null)"
              size="lg"
            />
          </b-form-group>
        </validation-provider>
        <div class="text-center">
          <b-button
            class="check-page__button_submit"
            :disabled="loading"
            type="submit"
            variant="success"
            size="lg"
          >
            <b-spinner
              v-if="loading"
              type="grow"
            />
            {{ $t('button_confirm') }}
          </b-button>
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
        email: "",
      },
      loading: false,
      issetEmail: null
    };
  },
  computed: {
    ...mapGetters({
      error: "error",
      existingEmail: "auth/existingEmail"
    })
  },
  mounted() {
    this.$store.commit('account/setBanned', false)
  },
  methods: {
    async onSubmit (valid) {
      if (!valid) {
        return
      }
      this.loading = true

      await this.$store.dispatch('auth/checkEmail', {email: this.form.email})

      if(!this.existingEmail) {
        this.$emit('register')
      } else {
        this.$emit('login')
      }

      this.loading = false
    }
  }
};
</script>
<i18n locale="en">
{
  "text_email": "Email",
  "button_confirm": "Confirm",
  "unauthorized": "Expired session"
}
</i18n>
<style lang="scss">
  .check-page {
    &__button_submit {
      margin-top: 25px;
    }
  }
</style>
