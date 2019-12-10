<template>
  <b-navbar class="header">
    <div class="vf-header">
      <div class="vf-header__wrapper">
        <header-logo />
        <b-navbar-nav class="align-items-center vf-header__right_nav">
          <header-activation v-if="cms.builds.length > 0" />
          <b-button
            v-if="cms.builds.length > 0"
            :disabled="cms.generating || loading"
            variant="success"
            class="vf-header__button_rebuild"
            @click="handleGenerate"
          >
            <b-spinner
              v-if="cms.generating || loading"
              type="grow"
            />
            {{ $t('buttonRebuild') }}
          </b-button>
          <header-account />
        </b-navbar-nav>
      </div>
    </div>
  </b-navbar>
</template>
<script>
import HeaderAccount from '~/components/header/Account'
import HeaderLogo from '~/components/header/Logo'
import HeaderActivation from '~/components/header/Activation'
import {mapGetters} from 'vuex'
export default {
  components: {
    HeaderAccount,
    HeaderLogo,
    HeaderActivation
  },
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
<style lang="scss">
  .vf-header {
    padding: 0 80px 50px;
    border-bottom: 1px solid #D9D9D9;
    margin-bottom: 70px;
    &__wrapper {
      height: 63px;
      display: flex;
      flex-flow: row;
    }
    &__button_rebuild {
      margin-right: 60px;
    }
    &__right_nav {
      width: 100%;
      display: flex;
      flex-flow: row;
      justify-content: flex-end;
      align-items: center;
      margin: 0;
    }
    &__logo {
      display: flex;
      align-items: center;
      flex-flow: row;
      img {
        width: auto;
        height: 100%;
        margin-right: 12px;
      }
    }
  }
</style>
<i18n locale="en">
{
  "text_vuefront": "VueFront",
  "buttonRebuild": "ReBuild"
}
</i18n>
