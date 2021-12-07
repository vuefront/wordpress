<template>
  <div class="banned-page">
    <div class="banned-page__banner">
      <div class="banned-page__banner_image">
        <img
          :src="require('~/assets/img/banned-email.svg')"
          alt=""
        >
      </div>
      <div class="banned-page__banner_title">
        {{ $t('textTitle') }}
      </div>
      <div class="banned-page__banner_description">
        {{ $t('textDescription') }}
      </div>
    </div>
    <div class="text-center">
      <a
        href="https://vuefront.com/help-center"
        target="_blank"
        class="btn btn-success"
      >{{ $t('buttonUnban') }}</a>
    </div>
    <div class="text-center">
      <a
        class="banned-page__button_logout"
        @click="handleLogout"
      >
        {{ $t('buttonLogout') }}
      </a>
    </div>
  </div>
</template>
<script>
import {mapGetters} from 'vuex'
export default {
  middleware: ['banned'],
  data() {
    return {
      refreshLoading: false
    }
  },
  computed: {
    ...mapGetters({
      account: 'account/get'
    })
  },
  methods: {
    handleLogout() {
      this.$store.dispatch('auth/logout')
      this.$store.commit('auth/toggleShowLogin')
    },
    async handleRefresh() {
      this.refreshLoading = true
      await this.$store.dispatch('account/load')
      if(this.account.banneded) {
        this.$store.commit('auth/toggleShowLogin')
      }
      this.refreshLoading = false
    },
    async handleResend() {
      await this.$store.dispatch('auth/resend')
    }
  }
}
</script>
<i18n locale="en">
{
  "textTitle": "Your account has been banned",
  "textDescription": "Unfortunately, due to the current circumstances, we had to ban your account. This is not a permanent measure and you can dispute it via our support center. If you wish to learn more about your ban, click the button below.",
  "buttonUnban": "Unban my account",
  "buttonLogout": "Try another email"
}
</i18n>
<style lang="scss">
  .banned-page {
    border-radius: 3px;
    border: 1px solid #d9d9d9;
    background-color: #ffffff;
    padding: 50px 55px;
    margin-bottom: 60px;
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
    &__banner {
      margin-bottom: 40px;
      &_image {
        padding: 0 40px;
        margin-bottom: 20px;
        img {
          width: 100;
          max-width: 100%;
          height: auto;
        }
      }
      &_title {
          font-family: 'Open Sans', sans-serif;
          font-size: 28px;
          font-weight: normal;
          font-stretch: normal;
          font-style: normal;
          line-height: 1.36;
          letter-spacing: normal;
          text-align: center;
          color: $black;
      }
      &_description {
        font-family: 'Open Sans', sans-serif;
        font-size: 16px;
        font-weight: normal;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.38;
        letter-spacing: normal;
        text-align: center;
        color: $black;
        margin-top: 5px;
      }
    }
  }
</style>
