<template>
  <div class="vf-sign-in-banner">
    <img
      :src="require('~/assets/img/Top_image.svg')"
      alt=""
      class="vf-sign-in-banner__top_img"
    >
    <div class="vf-sign-in-banner__title">
      {{ $t('textTitle') }}
    </div>
    <div class="vf-sign-in-banner__description">
      {{ $t('textDescription') }}
    </div>
    <div class="text-center">
      <b-button
        variant="success"
        class="vf-sign-in-banner__button_rebuild"
        @click="handleSignIn"
      >
        {{ $t('buttonSignIn') }}
      </b-button>
    </div>
    <img
      :src="require('~/assets/img/Bottom_image.svg')"
      alt=""
      class="vf-sign-in-banner__bottom_img"
    >
  </div>
</template>
<script>
import {mapGetters} from 'vuex'
export default {
    data() {
    return {
      loading: false
    }
  },
  computed: {
    ...mapGetters({
      cms: 'cms/get',
      error: 'error'
    })
  },
  methods: {
    handleSignIn() {
      this.$store.commit('auth/toggleShowLogin')
    }
  }
}
</script>
<i18n locale="en">
{
  "textTitle": "Sign In",
  "textDescription": "Log in to turn your old-fashioned website into a progressive web app and SPA in less then a minute.",
  "buttonRebuild": "Build",
  "buttonSignIn": "Sign In"
}
</i18n>
<style lang="scss">
  .vf-sign-in-banner {
    border-radius: 3px;
    box-shadow: 0 0 30px 0 rgba(0, 0, 0, 0.1);
    padding: 85px 100px 50px;
    position: relative;
    margin-bottom: 60px;
    z-index: 3;
    &__top_img {
      position: absolute;
      top: 0;
      left: 60px;
      width: calc(100% - 120px);
      height: auto;
      z-index: -1;
    }
    &__bottom_img {
      position: absolute;
      bottom: 0;
      left: 60px;
      width: calc(100% - 120px);
      height: auto;
      z-index: -1;
    }
    &__title {
      font-family: 'Open Sans', sans-serif;
      font-size: 40px;
      font-weight: 600;
      font-stretch: normal;
      font-style: normal;
      line-height: 1.25;
      letter-spacing: 0.4px;
      text-align: center;
      color: $black;
      margin-bottom: 10px;
      z-index: 1;
    }
    &__description {
      font-family: 'Open Sans', sans-serif;
      font-size: 18px;
      font-weight: normal;
      font-stretch: normal;
      font-style: normal;
      line-height: 1.56;
      letter-spacing: 0.18px;
      text-align: center;
      color: $warm-grey-two;
      margin-bottom: 30px;
      z-index: 1;
    }
    &__button_rebuild {
      z-index: 1;
    }
  }
</style>
