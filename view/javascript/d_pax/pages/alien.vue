<template>
  <div class="alient-page">
    <div class="alient-page__banner">
      <div class="alient-page__banner_image">
        <img
          :src="require('~/assets/img/banned-email.svg')"
          alt=""
        >
      </div>
      <div class="alient-page__banner_title">
        {{ $t('textTitle') }}
      </div>
      <div class="alient-page__banner_description">
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
        class="alient-page__button_logout"
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
  data() {
    return {
      refreshLoading: false
    }
  },
  layout: 'auth',
  computed: {
    ...mapGetters({
      account: 'account/get'
    })
  },
  middleware: ['alien'],
  methods: {
    handleLogout() {
      this.$store.dispatch('auth/logout')
      this.$router.push('/check')
    },
    async handleRefresh() {
      this.refreshLoading = true
      await this.$store.dispatch('account/load')
      if(this.account.banneded) {
        this.$router.push('/')
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
    "textTitle": "This is not your CMS",
    "textDescription": "It seems you are trying to log into an account that is not the owner of this CMS. If this is a mistake, please contact out support center by clicking the button below.",
    "buttonUnban": "Support center",
    "buttonLogout": "Try another email"
  }
</i18n>
<style lang="scss">
.alient-page {
  margin-top: 60px;
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
    background-color: #f9f9f9;
    padding: 50px;
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

