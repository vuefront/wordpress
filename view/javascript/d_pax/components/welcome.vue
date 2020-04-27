<template>
  <div class="vf-welcome">
    <img
      :src="require('~/assets/img/Top_image.svg')"
      alt=""
      class="vf-welcome__top_img"
    >
    <div class="vf-welcome__title">
      {{ $t('textTitle') }}
    </div>
    <div class="vf-welcome__description">
      {{ $t('textDescription') }}
    </div>
    <div class="text-center">
      <b-button
        :disabled="cms.generating || loading"
        variant="success"
        class="vf-welcome__button_rebuild"
        @click="handleGenerate"
      >
        <b-spinner
          v-if="cms.generating || loading"
          type="grow"
        />
        {{ $t('buttonRebuild') }}
      </b-button>
    </div>
    <img
      :src="require('~/assets/img/Bottom_image.svg')"
      alt=""
      class="vf-welcome__bottom_img"
    >
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
      cms: 'cms/get',
      error: 'error'
    })
  },
  mounted() {
    if(this.cms.generating) {
      const interval = setInterval(async () => {
        await this.$store.dispatch('cms/load', {id: this.cms.id})
        this.$apolloClient.defaultClient.clearStore()
        if(!this.cms.generating) {
          await this.$store.dispatch('information/updateVueFront', {url: this.cms.downloadUrl})
          this.$store.commit('cms/setFirstBuild', true)
          clearInterval(interval)
        }
      }, 3000)
    }
  },
  methods: {
    async handleGenerate() {
      this.loading = true
      await this.$store.dispatch('cms/generate', {id: this.cms.id})
      if(!this.error) {
        const interval = setInterval(async () => {
          await this.$store.dispatch('cms/load', {id: this.cms.id})
          this.$apolloClient.defaultClient.clearStore()
          if(!this.cms.generating) {
            await this.$store.dispatch('information/updateVueFront', {url: this.cms.downloadUrl})
            this.$store.commit('cms/setFirstBuild', true)

            this.loading = false
            clearInterval(interval)
          }
        }, 3000)
      } else {
        this.loading = false
      }
    }
  }
}
</script>
<i18n locale="en">
{
  "textTitle": "All set. Let's build your website.",
  "textDescription": "You are now ready to turn your old-fashioned website into a Progressive Web App and a Single Page Application in less then a minute. Ready, Set, Build!",
  "buttonRebuild": "Build"
}
</i18n>
<style lang="scss">
  .vf-welcome {
    border-radius: 3px;
    box-shadow: 0 0 30px 0 rgba(0, 0, 0, 0.1);
    padding: 85px 100px 50px;
    position: relative;
    margin-bottom: 60px;
    z-index: 3;
    &__top_img {
      position: absolute;
      top: 0;
      left: 60px;
      width: calc(100% - 120px);
      height: auto;
      z-index: -1;
    }
    &__bottom_img {
      position: absolute;
      bottom: 0;
      left: 60px;
      width: calc(100% - 120px);
      height: auto;
      z-index: -1;
    }
    &__title {
      font-family: 'Open Sans', sans-serif;
      font-size: 40px;
      font-weight: 600;
      font-stretch: normal;
      font-style: normal;
      line-height: 1.25;
      letter-spacing: 0.4px;
      text-align: center;
      color: $black;
      margin-bottom: 10px;
      z-index: 1;
    }
    &__description {
      font-family: 'Open Sans', sans-serif;
      font-size: 18px;
      font-weight: normal;
      font-stretch: normal;
      font-style: normal;
      line-height: 1.56;
      letter-spacing: 0.18px;
      text-align: center;
      color: $warm-grey-two;
      margin-bottom: 30px;
      z-index: 1;
    }
    &__button_rebuild {
      z-index: 1;
    }
  }
</style>
