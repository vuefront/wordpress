<template>
  <div>
    <b-row>
      <b-col md="9">
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
  middleware: ['authenticated', 'confirmed', 'noBanned', 'noAlien'],
  async fetch(ctx) {
    await ctx.store.dispatch('information/load')
  },
  computed: {
    ...mapGetters({information: 'information/get', cms: 'cms/get'})
  }
}
</script>
