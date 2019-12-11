<template>
  <div class="cms-activation">
    <div
      class="cms-activation__title d-none d-md-block"
    >
      {{ $t('textTitle') }}
    </div>
    <div
      class="cms-activation__checkbox"
      :class="{'cms-activation__checkbox_on': information.status}"
      @click="handleClick"
    >
      <div>
        <div
          v-if="loading"
          class="loader"
        >
          <b-spinner />
        </div>
      </div>
    </div>
    <div class="cms-activation__status_text">
      {{ information.status ? $t('textOn'): $t('textOff') }}
    </div>
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
      information: 'information/get',
      cms: 'cms/get'
    })
  },
  methods: {
    async handleClick() {
      this.loading = true
      if(!this.information.status) {
        await this.$store.dispatch('information/activateVueFront', {url: this.cms.downloadUrl})
      } else {
        await this.$store.dispatch('information/deActivateVueFront', {url: this.cms.downloadUrl})
      }
      this.loading = false
    }
  }
}
</script>
<i18n locale="en">
{
  "textTitle":"Frontend Web App status",
  "textOn":"On",
  "textOff":"Off"
}
</i18n>
<style lang="scss">
  .cms-activation {
    display: flex;
    flex-flow: row;
    align-items: center;
    margin-right: 20px;
    @media (--phone-and-tablet) {
      margin-right: auto;
    }
    @media (min-width: 1920px) {
      margin-right: 40px;
    }
    &__title {
      font-family: 'Open Sans', sans-serif;
      font-size: 16px;
      font-weight: normal;
      font-stretch: normal;
      font-style: normal;
      line-height: 1.38;
      letter-spacing: 0.16px;
      text-align: right;
      color: $black;
      margin-right: 16px;
      user-select: none;
    }
    &__checkbox {
        width: 87px;
        height: 48px;
        border-radius: 25px;
        border: solid 1px #d4d4d4;
        background-color: $white;
        margin-right: 13px;
        padding: 0 5px;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        cursor: pointer;
        user-select: none;

        div {
          display: block;
          width: 38px;
          height: 38px;
          background-color: #c5c5c5;
          border-radius: 50%;
          position: relative;
          .loader {
            display: flex;
            justify-content: center;
            align-items: center;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            color: #fff;
          }
        }

        &_on {
          justify-content: flex-end;
          div {
            background-color: $dark-mint;
          }
        }
    }
    &__status_text {
        font-family: 'Open Sans', sans-serif;
        font-size: 24px;
        font-weight: normal;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.38;
        letter-spacing: 0.24px;
        text-align: left;
        color: $black;
        user-select: none;
        min-width: 35px;
    }
  }
</style>
