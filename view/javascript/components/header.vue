<template>
  <b-navbar class="header">
    <div class="vf-header">
      <div class="vf-header__wrapper">
        <b-row>
          <b-col
            md="auto"
            sm="12"
          >
            <header-logo />
          </b-col>
          <b-col>
            <b-navbar-nav class="align-items-center vf-header__right_nav">
              <header-activation v-if="cms.builds.length > 0" />
              <header-account />
            </b-navbar-nav>
          </b-col>
        </b-row>
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

  computed: {
    ...mapGetters({
      cms: 'cms/get'
    })
  },

}
</script>
<style lang="scss">
  .vf-header {
    border-bottom: 1px solid #D9D9D9;
    margin-bottom: 50px;
    padding: 0 40px 25px;
    @media (min-width: 1920px) {
      padding: 0 80px 50px;
    }
    &__wrapper {
      min-height: 63px;
      display: flex;
      flex-flow: row;
      > .row {
        flex:1;
      }
    }
    &__button_rebuild {
      margin-right: 30px;
      @media (min-width: 1920px) {
        margin-right: 60px;
      }
    }
    &__right_nav {
      width: 100%;
      display: flex;
      flex-flow: row;
      justify-content: flex-end;
      align-items: center;
      margin: 0;
      @media (--phone-and-tablet) {
        padding: 0;
        margin-top: 15px;
        justify-content: center;
      }
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
  "text_vuefront": "VueFront"
}
</i18n>
