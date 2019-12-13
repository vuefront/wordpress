<template>
  <div class="vf-home">
    <b-row>
      <b-col md="9">
        <re-build />
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
import ReBuild from '~/components/rebuild'
export default {
  components: {
    Subscription,
    Information,
    Development,
    Activity,
    Welcome,
    ReBuild
  },
  async fetch(ctx) {
    await ctx.store.dispatch('information/load')
  },
  middleware: ['authenticated', 'confirmed', 'noBanned', 'noAlien'],
  computed: {
    ...mapGetters({information: 'information/get', cms: 'cms/get'})
  }
}
</script>
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
