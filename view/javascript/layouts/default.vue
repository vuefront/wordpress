<template>
  <div class="vf-module-content vf-module-content__default">
    <vf-header />
    <div class="vf-module-content__wrapper">
      <router-view />
    </div>
  </div>
</template>
<script>
import vfHeader from '~/components/header'
export default {
  components: {
    vfHeader
  }
}
</script>
<style lang="scss">
  .vf-module-content {
    &__default {
      padding: 65px 0;
      background-color: #ffffff;
    }
    &__wrapper {
      padding: 0 80px;
    }
  }
</style>

