<template>
  <b-alert
    v-if="cms.builds.length > 0"
    show
    variant="success"
    class="vf-home__rebuild"
  >
    <b-row>
      <b-col>
        <h4 class="alert-heading">
          {{ $t('buildText') }}
        </h4>
        <p class="vf-home__rebuild_description">
          {{ $t('buildDescription') }}
        </p>
      </b-col>
      <b-col
        md="auto"
        align-self="center"
      >
        <b-button
          :disabled="cms.generating || loading"
          variant="success"
          @click="handleGenerate"
        >
          <b-spinner
            v-if="cms.generating || loading"
            type="grow"
          />
          {{ $t('buttonRebuild') }}
        </b-button>
      </b-col>
    </b-row>
  </b-alert>
</template>
<script>
import { mapGetters } from 'vuex';
export default {
  data() {
    return {
      loading: false
    }
  },
  computed: {
    ...mapGetters({
      cms: 'cms/get'
    })
  },
  mounted() {
    if(this.cms.generating) {
      const interval = setInterval(async () => {
        await this.$store.dispatch('cms/load', {id: this.cms.id})
        this.$apolloClient.defaultClient.clearStore()
        if(!this.cms.generating) {
          await this.$store.dispatch('information/updateVueFront', {url: this.cms.downloadUrl})
          clearInterval(interval)
        }
      }, 3000)
    }
  },
  methods: {
    async handleGenerate() {
      this.loading = true
      await this.$store.dispatch('cms/generate', {id: this.cms.id})
      const interval = setInterval(async () => {
        await this.$store.dispatch('cms/load', {id: this.cms.id})
        this.$apolloClient.defaultClient.clearStore()
        if(!this.cms.generating) {
          await this.$store.dispatch('information/updateVueFront', {url: this.cms.downloadUrl})
          this.loading = false
          clearInterval(interval)
        }
      }, 3000)
    }
  }
}
</script>
<i18n locale="en">
{
  "buttonRebuild": "Rebuild",
  "buildText": "Made a change? Just hit Rebuild",
  "buildDescription": "For Search engines like Google to update their search results properly, you must rebuild your app on every significant change."
}
</i18n>
<style lang="scss">
  .vf-home__rebuild {
    &::before {
      display: none!important;
    }
    h2, h4 {
      font-family: 'Open Sans', sans-serif;
      margin-top: 0;
      font-size: 20px;
    }
    p {
      margin: 0 0 9px;
    }
  }
</style>
