<template>
  <div class="account-subscription">
    <div class="account-subscription__title">
      {{ $t('text_title') }}
    </div>
    <div class="account-subscription__description">
      {{ $t('text_description') }}
    </div>
    <b-button
      class="account-subscription__button"
      variant="warning"
      size="lg"
      @click="handleSubscribe"
    >
      {{ $t("button_subscribe") }}
    </b-button>
  </div>
</template>
<script>
export default {
  methods: {
    async handleSubscribe () {
      await this.$store.dispatch('cms/subscribe')
    }
  }
}
</script>
<i18n locale="en">
{
  "text_title": "Go Production!",
  "text_description": "Perfect for commercial projects. Increase your build minutes to 1000 per month with $5 for extra 300 minutes. ",
  "button_subscribe": "$10 for 1000 min / month"
}
</i18n>
<style lang="scss">
  .account-subscription {
    border-radius: 3px;
    background-color: $dark-mint;
    padding: 35px;
    margin-bottom: 50px;
    &__title {
      font-family: 'Open Sans', sans-serif;
      font-size: 24px;
      font-weight: 600;
      font-stretch: normal;
      font-style: normal;
      line-height: 1;
      letter-spacing: 0.24px;
      text-align: left;
      color: $white;
      margin-bottom: 15px;
    }
    &__description {
      font-family: 'Open Sans', sans-serif;
      font-size: 16px;
      font-weight: normal;
      font-stretch: normal;
      font-style: normal;
      line-height: 1.5;
      letter-spacing: 0.16px;
      text-align: left;
      color: $white;
      margin-bottom: 20px;
    }
    &__button {
      font-family: 'Open Sans', sans-serif;
      font-size: 16px;
      font-weight: 600;
      font-stretch: normal;
      font-style: normal;
      line-height: 1.38;
      letter-spacing: 0.16px;
      text-align: center;
      color: #261805;
      white-space: normal;
    }
  }
</style>
