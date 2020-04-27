<template>
  <modal
    :value="true"
    class="first-build"
    btn-close
    @cancel="handleClose"
  >
    <div class="text-center">
      <img
        :src="require('~/assets/img/firstBuild.svg')"
        class="first-build__image"
        width="200"
        alt=""
      >
    </div>
    <div class="first-build__title">
      {{ $t('popupTitle') }}
    </div>
    <div class="first-build__subtitle">
      {{ $t('subTitlePopup') }} <a
        @click.prevent.stop="handleSearch"
      >{{ $t('text_bellow') }}</a>.
    </div>
    <div
      v-if="information.apache"
      class="first-build__footer_title"
    >
      {{ $t('footerTitlePopup') }}
    </div>
    <div class="text-center">
      <div
        v-if="error"
        class="first-build__error"
      >
        {{ $t(error) }}
      </div>
    </div>
    <div
      v-if="information.apache"
      class="text-center"
    >
      <b-button
        :disabled="loading"
        variant="success"
        size="lg"
        @click="handleConfirm"
      >
        <b-spinner
          v-if="loading"
          type="grow"
        />
        {{ $t('buttonConfirm') }}
      </b-button>
    </div>
    <div class="text-center">
      <div
        class="first-build__footer_link"
        @click="handleClose"
      >
        {{ $t('buttonAbort') }}
      </div>
    </div>
    <div class="text-center">
      <img
        :src="require('~/assets/img/footer-modal.svg')"
        class="first-build__footer_image"
        alt=""
      >
    </div>
  </modal>
</template>
<script>
import Modal from '~/components/modal'
import {mapGetters} from 'vuex'
export default {
  components: {
    Modal
  },
  data() {
    return {
      loading: false
    }
  },
  computed: {
    ...mapGetters({information: 'information/get', cms: 'cms/get', error: 'error'})
  },
  methods: {
    async handleSearch() {
      this.$store.commit('cms/setFirstBuild', false)
      this.$nextTick(() => {
        this.$scrollTo('#vf-apache-configure')
      })
    },
    async handleConfirm() {
      this.loading = true
      if(!this.information.status) {
        await this.$store.dispatch('information/activateVueFront', {url: this.cms.downloadUrl})
      } else {
        await this.$store.dispatch('information/deActivateVueFront', {url: this.cms.downloadUrl})
      }

      this.loading = false

      if(!this.error) {
        this.$store.commit('cms/setFirstBuild', false)
      }
    },
    handleClose() {
      this.$store.commit('cms/setFirstBuild', false)
    }
  }
}
</script>
<i18n locale="en">
  {
    "popupTitle": "Yay! Your Build is ready",
    "subTitlePopup": "We have generated your website and uploaded it into your root folder. To replace your current Frontend with the new VueFront Web App, you will need to configure your server settings by following these",
    "text_bellow": "instructions",
    "footerTitlePopup": "Would you like us to do this for you?",
    "buttonConfirm": "Activate",
    "buttonAbort": "Close",
    "not_writable_htaccess": "File permissions. Please add writing permissions to the following files and folder: .htaccess"
  }
</i18n>
<style lang="scss">
  .first-build {
    .vf-modal {
      .vf-modal-dialog {
        .vf-modal-content {
          .vf-modal-body {
            padding-top: 50px!important;
            padding-bottom: 0;
            padding-left: 110px;
            padding-right: 110px;
          }
        }
      }
    }
    a {
      cursor: pointer;
    }
    &__image {
      margin-bottom: 30px;
    }
    &__error {
      color: $tomato;
      margin-bottom: 20px;
      font-family: 'Open Sans', sans-serif;
      font-size: 16px;
    }
    &__title {
      font-family: 'Open Sans', sans-serif;
      font-size: 30px;
      font-weight: 600;
      font-stretch: normal;
      font-style: normal;
      line-height: 1.25;
      letter-spacing: 0.4px;
      text-align: center;
      color: $black;
      margin-bottom: 20px;
    }
    &__subtitle {
      margin-bottom: 20px;
    }
    &__description {
      margin-bottom: 20px;
    }
    &__footer_title {
      margin-bottom: 30px;
      font-weight: 600!important;
      color: #333!important;

    }
    &__subtitle, &__description, &__footer_title {
      font-family: 'Open Sans', sans-serif;
      font-size: 16px;
      font-weight: normal;
      font-stretch: normal;
      font-style: normal;
      line-height: 1.56;
      letter-spacing: 0.18px;
      text-align: center;
      color: $warm-grey-two;
    }
    &__footer_link {
      font-family: 'Open Sans', sans-serif;
      font-size: 16px;
      font-weight: 600;
      font-stretch: normal;
      font-style: normal;
      line-height: 1.38;
      letter-spacing: 0.16px;
      color: $dark-mint;
      margin-top: 20px;
      cursor: pointer;
      display: inline-block;
    }
    &__footer_image {
      margin-top: 50px;
    }
  }
</style>
