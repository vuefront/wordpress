<template>
  <div class="vf-module-content vf-module-content__default">
    <vf-header />
    <div class="vf-module-content__wrapper">
      <div
        v-if="error"
        class="vf-module-content__error"
      >
        {{ $t(error) }}
      </div>
      <router-view />
    </div>
  </div>
</template>
<script>
import vfHeader from '~/components/header'
import {mapGetters} from 'vuex'
export default {
  components: {
    vfHeader
  },
  computed: {
    ...mapGetters({error: 'error'})
  }
}
</script>
<i18n locale="en">
{
  "not_writable_htaccess": "File permissions. Please add writing permissions to the following files and folder: .htaccess"
}
</i18n>
<style lang="scss">
  .vf-module-content {
    &__default {
      padding: 50px 0;
      background-color: #ffffff;
    }
    &__error {
      color: $tomato;
      margin-bottom: 20px;
      font-family: 'Open Sans', sans-serif;
      font-size: 16px;
      word-break: break-all;
    }
    &__wrapper {
      padding: 0 30px;
      @media (min-width: 1920px) {
        padding: 0 60px;
      }
    }
  }
</style>

