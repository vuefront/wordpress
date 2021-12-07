<template>
  <div class="vf-information">
    <div
      v-if="isLogged"
      class="vf-information__item"
    >
      <div class="vf-information__item_title">
        {{ $t('textAccount') }}
      </div>
      <div class="vf-information__item_value">
        {{ !account.subscribe ? $t('textFree') : $t('textSubscribe') }}
      </div>
    </div>
    <div
      v-if="isLogged"
      class="vf-information__item"
    >
      <div class="vf-information__item_title">
        {{ $t('textBuildMin') }}
      </div>
      <div class="vf-information__item_value">
        {{ totalUsaged }} / {{ timeCount }}
      </div>
    </div>
    <div class="vf-information__item">
      <div class="vf-information__item_title">
        {{ $t('textPluginVersion') }}
      </div>
      <div class="vf-information__item_value">
        {{ information.plugin_version }}
      </div>
    </div>
    <div
      v-for="(value, index) in information.extensions"
      :key="index"
      class="vf-information__item"
    >
      <div class="vf-information__item_title">
        {{ value.name }}
      </div>
      <div class="vf-information__item_value">
        {{ value.status ? $t('textActive') : $t('textNoActive') }}
      </div>
    </div>
    <div class="vf-information__item">
      <div class="vf-information__item_title">
        {{ $t('textPHPVersion') }}
      </div>
      <div class="vf-information__item_value">
        {{ information.phpversion }}
      </div>
    </div>
    <div class="vf-information__item">
      <div class="vf-information__item_title">
        {{ $t('textServer') }}
      </div>
      <div class="vf-information__item_value">
        {{ information.server }}
      </div>
    </div>
  </div>
</template>
<script>
import {mapGetters} from 'vuex'
export default {
  computed: {
    ...mapGetters({isLogged: "auth/isLogged", information: 'information/get', cms: 'cms/get', account: 'account/get'}),
    totalUsaged () {
      return Math.floor(this.$moment.duration(this.account.times, 'milliseconds').asMinutes())
    },
    timeCount () {
      return this.account.subscribe ? 1000 : 100
    }
  }
}
</script>
<i18n locale="en">
{
  "textAccount": "Account",
  "textFree": "Free",
  "textSubscribe": "Subscription",
  "textPluginVersion": "Plugin version",
  "textPHPVersion": "PHP",
  "textServer": "Server",
  "textActive": "Active",
  "textNoActive": "Not active",
  "textBuildMin": "Build min."
}
</i18n>
<style lang="scss">
  .vf-information {
    border-radius: 3px;
    border: 1px solid $white-five;
    background-color: $white;
    padding: 35px;
    &__item {
      display: flex;
      flex-flow: row;
      margin-bottom: 15px;
      &_title {
        text-align: left;
        flex: 1;
        font-family: 'Open Sans', sans-serif;
        font-size: 16px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.5;
        letter-spacing: 0.16px;
        color: $black;
      }
      &_value {
        padding-left: 10px;
        font-family: 'Open Sans', sans-serif;
        font-size: 16px;
        font-weight: normal;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.5;
        letter-spacing: 0.16px;
        text-align: right;
        color: $black;
        white-space: nowrap;
        overflow-y: auto;
      }
    }
  }
</style>
