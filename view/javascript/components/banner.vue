<template>
  <div class="vf-info">
    <img
      class="vf-info__top_image"
      :src="require('~/assets/img/top-info.svg')"
    >
    <div class="vf-info__title">
      Progressive Web Application
    </div>
    <div class="vf-info__description">
      VueFront will turn your WordPress Website into a PWA and SPA in less then 5 minutes for free. No coding required. Enter your email and follow the instructions.
    </div>
    <img
      class="vf-info__bottom_image"
      :src="require('~/assets/img/bottom-info.svg')"
    >
  </div>
</template>
<style lang="scss">
.vf-info {
  border-radius: 3px;
  box-shadow: 0 0 30px 0 rgba(0, 0, 0, 0.1);
  background-color: white;
  @media (--phone-and-tablet) {
    margin-bottom: 30px;
  }
  &__top_image {
    max-width: 100%;
    height: auto;
    margin-bottom: 100px;
    width: 100%;
  }
  &__bottom_image {
    margin-top: 30px;
    max-width: 100%;
    height: auto;
    width: 100%;
  }
  &__title {
    padding: 0 80px;
    font-family: "Open Sans", sans-serif;
    font-size: 40px;
    font-weight: 600;
    font-stretch: normal;
    font-style: normal;
    line-height: 1.5;
    letter-spacing: 0.4px;
    text-align: left;
    color: #1a1a1a!important;
    margin-bottom: 20px;
    @media (--phone-and-tablet) {
      padding: 0 40px;
    }
  }
  &__description {
    padding: 0 80px;
    font-family: "Open Sans", sans-serif;
    font-size: 18px;
    font-weight: normal;
    font-stretch: normal;
    font-style: normal;
    line-height: 1.56;
    letter-spacing: 0.18px;
    text-align: left;
    color: #7d7d7d;
    @media (--phone-and-tablet) {
      padding: 0 40px;
    }
  }
}
</style>
