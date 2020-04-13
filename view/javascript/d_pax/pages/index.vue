<template>
  <div class="vf-home">
    <b-row>
      <b-col md="9">
        <re-build v-show="!cms.generating" />
        <welcome
          v-if="cms.builds.length === 0"
          v-show="!cms.generating"
        />
        <activity v-if="cms.builds.length > 0 || cms.generating" />
        <first-build v-if="firstBuild" />
        <apps />
        <development />
      </b-col>
      <b-col md="3">
        <subscription v-if="!account.subscribe" />
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
import FirstBuild from '~/components/firstBuild'
import Apps from '~/components/apps'
export default {
  components: {
    Subscription,
    Information,
    Development,
    Activity,
    Welcome,
    ReBuild,
    FirstBuild,
    Apps
  },
  async fetch(ctx) {
    await ctx.store.dispatch('information/load')
    await ctx.store.dispatch('apps/list')
  },
  middleware: ['authenticated', 'confirmed', 'noBanned', 'noAlien'],
  computed: {
    ...mapGetters({account: 'account/get' ,information: 'information/get', cms: 'cms/get', firstBuild: 'cms/firstBuild'})
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
