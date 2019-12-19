<template>
  <div>
    <div class="cms-activation">
      <div
        class="cms-activation__title d-none d-md-block"
      >
        {{ $t('textTitle') }}
      </div>
      <div
        class="cms-activation__checkbox"
        :class="{'cms-activation__checkbox_on': information.status}"
        @click="handleClick"
      >
        <div>
          <div
            v-if="loading"
            class="loader"
          >
            <b-spinner />
          </div>
        </div>
      </div>
      <div class="cms-activation__status_text">
        {{ information.status ? $t('textOn'): $t('textOff') }}
      </div>
      <modal
        v-model="popup"
        class="cms-activation__modal"
        btn-close
      >
        <div class="text-center">
          <img
            :src="require('~/assets/img/rocket.png')"
            class="cms-activation__modal__image"
            width="200"
            alt=""
          >
        </div>
        <div class="cms-activation__modal__title">
          {{ $t('popupTitle') }}
        </div>
        <div class="cms-activation__modal__subtitle">
          {{ $t('subTitlePopup') }} <a
            @click.prevent.stop="handleSearch"
          >{{ $t('text_bellow') }}</a>.
        </div>
        <!-- eslint-disable vue/no-v-html -->
        <div
          v-if="information.htaccess"
          class="cms-activation__modal__description"
          v-html="$t('descriptionPopup').replace('[path]', `${information.backup}`)"
        />
        <div
          class="cms-activation__modal__footer_title"
        >
          {{ $t('footerTitlePopup') }}
        </div>
        <div class="text-center">
          <div
            v-if="error"
            class="cms-activation__modal__error"
          >
            {{ $t(error) }}
          </div>
        </div>
        <div class="text-center">
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
            class="cms-activation__modal__footer_link"
            @click="popup = false"
          >
            {{ $t('buttonAbort') }}
          </div>
        </div>
        <div class="text-center">
          <img
            :src="require('~/assets/img/footer-modal.svg')"
            class="cms-activation__modal__footer_image"
            alt=""
          >
        </div>
      </modal>
    </div>
  </div>
</template>
<script>
import {mapGetters} from 'vuex'
import Modal from '~/components/modal'

export default {
  components: {
    Modal
  },
  data() {
    return {
      loading: false,
      popup: false
    }
  },
  computed: {
    ...mapGetters({
      information: 'information/get',
      cms: 'cms/get',
      error: 'error'
    })
  },
  methods: {
    async handleClick() {
      if(!this.information.status) {
        this.popup = true
      } else {
        this.loading = true
        await this.$store.dispatch('information/deActivateVueFront', {url: this.cms.downloadUrl})
      }
      this.loading = false
    },
    async handleSearch() {
      this.popup = false
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
        this.popup = false
      }
    }
  }
}
</script>
<i18n locale="en">
  {
  "textTitle":"Frontend Web App status",
  "textOn":"On",
  "textOff":"Off",
  "popupTitle": "Confirm launch!",
  "subTitlePopup": "You are about to activate your new Frontend Web App. To do this, we will update your .htaccess to add VueFront related apache rules. If you have a custom .htaccess file, we strongly advise you to add the rules manually by following the instructions",
  "text_bellow": "here",
  "descriptionPopup": "To ensure your security, we will make a copy of your .htaccess file at <br>[path]. In case of unexpected situations or even site failure, please restore your old .htaccess file via ftp or your Cpanel file manager.",
  "buttonConfirm": "Confirm",
  "buttonAbort": "Abort",
  "footerTitlePopup": "Ready to turn your website into a PWA and SPA?",
  "not_writable_htaccess": "File permissions. Please add writing permissions to the following files and folder: .htaccess"
  }
</i18n>
<style lang="scss">
  .cms-activation {
    display: flex;
    flex-flow: row;
    align-items: center;
    margin-right: 20px;
    @media (--phone-and-tablet) {
      margin-right: auto;
    }
    @media (min-width: 1920px) {
      margin-right: 40px;
    }
    &__modal {
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
    &__title {
      font-family: 'Open Sans', sans-serif;
      font-size: 16px;
      font-weight: normal;
      font-stretch: normal;
      font-style: normal;
      line-height: 1.38;
      letter-spacing: 0.16px;
      text-align: right;
      color: $black;
      margin-right: 16px;
      user-select: none;
    }
    &__checkbox {
      width: 87px;
      height: 48px;
      border-radius: 25px;
      border: solid 1px #d4d4d4;
      background-color: $white;
      margin-right: 13px;
      padding: 0 5px;
      display: flex;
      align-items: center;
      justify-content: flex-start;
      cursor: pointer;
      user-select: none;

      div {
        display: block;
        width: 38px;
        height: 38px;
        background-color: #c5c5c5;
        border-radius: 50%;
        position: relative;
        .loader {
          display: flex;
          justify-content: center;
          align-items: center;
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          color: #fff;
        }
      }

      &_on {
        justify-content: flex-end;
        div {
          background-color: $dark-mint;
        }
      }
    }
    &__status_text {
      font-family: 'Open Sans', sans-serif;
      font-size: 24px;
      font-weight: normal;
      font-stretch: normal;
      font-style: normal;
      line-height: 1.38;
      letter-spacing: 0.24px;
      text-align: left;
      color: $black;
      user-select: none;
      min-width: 35px;
    }
  }
</style>
