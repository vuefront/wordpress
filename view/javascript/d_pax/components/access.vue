<template>
  <div
    class="vf-access__wrapper"
  >
    <div class="vf-access__wrapper_title">
      Access key
    </div>
    <div class="vf-access__wrapper_text">
      You can specify Access Key to access the hidden graphl API.
    </div>
    <b-input-group
      class="vf-access__access_key_wrapper"
    >
      <input
        v-model="accessKey"
        type="text"
        class="vf-access__access_key"
      >
      <b-input-group-append>
        <b-button
          variant="success"
          @click="handleSave"
        >
          {{ saved ? $t('buttonSaved') :$t('buttonSave') }}
        </b-button>
      </b-input-group-append>
    </b-input-group>
  </div>
</template>
<script>
export default {
  data() {
    return {
      accessKey: this.$store.getters['settings/get'].accessKey ? this.$store.getters['settings/get'].accessKey : '',
      saved: false
    }
  },
  methods: {
    async handleSave() {
      await this.$store.dispatch('settings/edit', {accessKey: this.accessKey})
      if (!this.$store.getters.error) {
        this.saved = true
        setTimeout(() => {
          this.saved = false
        }, 2000)
      }
    }
  }
}
</script>
<i18n locale="en">
{
  "buttonSave": "Save",
  "buttonSaved": "Saved"
}
</i18n>
<style lang="scss">
.vf-access {
      &__access_key {
      height: 54px !important;
      background-color: #efeff1!important;
      padding: 12px 24px!important;
      line-height: 30px!important;
      font-family: 'Open Sans', sans-serif !important;
      font-size: 24px !important;
      font-weight: 600;
      font-stretch: normal;
      font-style: normal;
      letter-spacing: 0.24px;
      text-align: left;
      color: $black !important;
      border: none!important;
      flex: 1;
      @media (--phone-and-tablet) {
        overflow: hidden;
      }
      &_wrapper {
        margin-bottom: 20px;
        width: 100%;
      }
    }
  &__wrapper {
      border-radius: 3px;
      border: 1px solid $white-five;
      background-color: $white;
      padding: 50px 55px;
      margin-bottom: 60px;
      @media (--phone-and-tablet) {
        padding: 20px 25px;
      }

      &_title {
        font-family: 'Open Sans', sans-serif;
        font-size: 28px;
        font-weight: 600;
        font-stretch: normal;
        font-style: normal;
        line-height: 0.86;
        letter-spacing: 0.28px;
        text-align: left;
        color: $black;
        margin-bottom: 20px;
        @media(--phone-and-tablet) {
          font-size: 24px;
        }
      }
      &_text {
        font-family: 'Open Sans', sans-serif;
        font-size: 18px !important;
        font-weight: normal;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.56;
        letter-spacing: 0.18px;
        text-align: left;
        color: #7d7d7d;
        margin-bottom: 10px;
      }
  }
}
</style>
