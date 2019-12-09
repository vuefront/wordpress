<template>
  <div class="cms-activation">
    <div class="cms-activation__title">
      {{ $t('textTitle') }}
    </div>
    <div
      class="cms-activation__checkbox"
      :class="{'cms-activation__checkbox_on': information.status}"
      @click="handleClick"
    />
    <div class="cms-activation__status_text">
      {{ information.status ? $t('textOn'): $t('textOff') }}
    </div>
  </div>
</template>
<script>
import {mapGetters} from 'vuex'
export default {
  computed: {
    ...mapGetters({
      information: 'information/get',
      cms: 'cms/get'
    })
  },
  methods: {
    async handleClick() {
      if(!this.information.status) {
        await this.$store.dispatch('information/activateVueFront', {url: this.cms.downloadUrl})
      } else {
        await this.$store.dispatch('information/deActivateVueFront', {url: this.cms.downloadUrl})
      }
    }
  }
}
</script>
<i18n locale="en">
{
  "textTitle":"Auto-activation",
  "textOn":"On",
  "textOff":"Off"
}
</i18n>
<style lang="scss">
  .cms-activation {
    display: flex;
    flex-flow: row;
    align-items: center;
    margin-right: 40px;
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
        &:before {
          content: '';
          display: block;
          width: 38px;
          height: 38px;
          background-color: #c5c5c5;
          border-radius: 50%;
        }
        &_on {
          justify-content: flex-end;
          &:before {
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
