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
              <header-activation v-if="isLogged && cms.builds.length > 0 && information.apache" />
              <b-button
                v-if="isLogged && cms.builds.length > 0 && !information.apache"
                class="vf-header__button_activate"
                variant="success"
                @click="$scrollTo('#vf-nginx-configure')"
              >
                {{ $t('button_activate') }}
              </b-button>
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
      isLogged: "auth/isLogged",
      cms: 'cms/get',
      information: 'information/get'
    })
  },

}
</script>
<style lang="scss">
  .vf-header {
    border-bottom: 1px solid #D9D9D9;
    margin-bottom: 25px;
    padding: 0 30px 25px;
    @media (min-width: 1920px) {
      padding: 0 60px 50px;
    }
    &__button_activate {
      margin-right: 20px;
      @media (--phone-and-tablet) {
        margin-right: auto;
      }
      @media (min-width: 1920px) {
        margin-right: 40px;
      }
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
  "text_vuefront": "VueFront",
  "button_activate": "Activate"
}
</i18n>
