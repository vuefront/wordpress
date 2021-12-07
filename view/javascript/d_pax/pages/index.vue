<template>
  <div class="vf-home">
    <b-row>
      <b-col md="9">
        <sign-in-banner v-if="!isLogged && !banned" />
        <alien v-if="isLogged && alien && account.confirmed" />
        <confirm v-if="isLogged && !account.confirmed" />
        <banned v-if="banned" />
        <re-build
          v-if="isLogged && account.confirmed && !alien && !banned"
          v-show="!cms.generating"
        />
        <welcome
          v-if="!alien && account.confirmed && !banned && isLogged && cms.builds.length === 0"
          v-show="!cms.generating"
        />
        <activity v-if="isLogged && account.confirmed && !alien && !banned && (cms.builds.length > 0 || cms.generating)" />
        <first-build v-if="isLogged && account.confirmed && !alien && !banned && firstBuild" />
        <apps />
        <access />
        <development />
      </b-col>
      <b-col md="3">
        <subscription v-if="isLogged && account.confirmed && !banned && !account.subscribe" />
        <information />
      </b-col>
    </b-row>
  </div>
</template>
<script>
import {mapGetters} from 'vuex'
import SignInBanner from '~/components/sign-in-banner'
import Subscription from '~/components/subscription'
import Development from '~/components/development'
import Information from '~/components/information'
import Activity from '~/components/activity'
import Welcome from '~/components/welcome'
import ReBuild from '~/components/rebuild'
import FirstBuild from '~/components/firstBuild'
import Apps from '~/components/apps'
import Access from '~/components/access'
import Alien from '~/components/alien'
import Banned from '~/components/banned'
import Confirm from '~/components/confirm'
export default {
  components: {
    Access,
    Subscription,
    Information,
    Development,
    Activity,
    Welcome,
    ReBuild,
    FirstBuild,
    Apps,
    SignInBanner,
    Alien,
    Banned,
    Confirm
  },
  async fetch(ctx) {
    await ctx.store.dispatch('information/load')
    await ctx.store.dispatch('apps/list')
  },
  computed: {
    ...mapGetters({alien: "cms/alien", isLogged: "auth/isLogged", banned: 'account/banned', account: 'account/get' ,information: 'information/get', cms: 'cms/get', firstBuild: 'cms/firstBuild'})
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
