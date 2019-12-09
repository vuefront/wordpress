<template>
  <div class="vf-module-content">
    <b-container fluid>
      <b-row>
        <b-col md="6">
          <banner />
        </b-col>
        <b-col
          md="6"
          align-self="center"
        >
          <div class="text-center vf-module-content__image">
            <img :src="require('~/assets/img/logo.svg')">
          </div>
          <router-view />
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>
<script>
import Banner from '~/components/banner'

export default {
  components: {
    Banner
  },
}
</script>
