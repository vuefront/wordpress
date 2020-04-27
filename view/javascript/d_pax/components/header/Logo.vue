<template>
  <div class="header-logo">
    <img
      :src="require('~/assets/img/logo-icon.svg')"
      class="header-logo__image"
    >
    <span class="header-logo__text">{{ $t('text_vuefront') }}</span>
  </div>
</template>
<style lang="scss">
  .header-logo {
    display: flex;
    flex-flow: row;
    align-items: center;
    @media (--phone-and-tablet) {
      justify-content: center;
    }
    &__image {
      height: 50px;
      @media (min-width: 1920px) {
        height: 100%;
      }
      width: auto;
      margin-right: 13px;
    }
    &__text {
      font-family: 'Open Sans', sans-serif;
      font-size: 30px;
      font-weight: 600;
      font-stretch: normal;
      font-style: normal;
      line-height: 0.6;
      letter-spacing: 0.4px;
      text-align: left;
      color: $black;
      @media (min-width: 1920px) {
        font-size: 40px;
      }
    }
  }
</style>
<i18n locale="en">
{
  "text_vuefront": "VueFront"
}
</i18n>
