<template>
  <div class="vf-home">
    <b-row>
      <b-col md="9">
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
        <welcome v-if="cms.builds.length === 0" />
        <activity v-else />
        <development />
      </b-col>
      <b-col md="3">
        <subscription v-if="false" />
        <information />
      </b-col>
    </b-row>
  </div>
</template>
<script>
import {mapGetters} from 'vuex'
import Subscription from '~/components/subscription'
import Development from '~/components/development'
import Information from '~/components/information'
import Activity from '~/components/activity'
import Welcome from '~/components/welcome'
export default {
  components: {
    Subscription,
    Information,
    Development,
    Activity,
    Welcome
  },
  async fetch(ctx) {
    await ctx.store.dispatch('information/load')
  },
  data() {
    return {
      loading: false
    }
  },
  middleware: ['authenticated', 'confirmed', 'noBanned', 'noAlien'],
  computed: {
    ...mapGetters({information: 'information/get', cms: 'cms/get'})
  },
  mounted() {
    if(this.cms.generating) {
      const interval = setInterval(async () => {
        await this.$store.dispatch('cms/load', {id: this.cms.id})
        this.$apolloClient.defaultClient.clearStore()
        if(!this.cms.generating) {
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
  "buildDescription": "If you made any change via your admin panel, you will need to rebuild your frontend Web App to see the changes."
}
</i18n>
<style lang="scss">
.vf-home {

  &__rebuild {
    margin-bottom: 30px !important;
    padding: 20px !important;
    &_description {
      font-size: 1.3rem;
    }
  }
}
</style>
