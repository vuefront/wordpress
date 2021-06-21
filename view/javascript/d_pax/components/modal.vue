<template>
  <div
    v-show="show || value"
    :transition="transition"
  >
    <div
      class="vf-modal"
      @click.self="clickMask"
    >
      <div
        :class="modalClass"
        class="vf-modal-dialog"
      >
        <div class="vf-modal-content">
          <a
            v-if="btnClose"
            class="vf-modal-close"
            @click="cancel"
          ><svg-icon
            type="mdi"
            :path="mdiClose"
          /></a>
          <div
            v-if="$slots.header"
            class="vf-modal-header"
          >
            <slot name="header" />
          </div>
          <div class="vf-modal-body">
            <slot />
          </div>
          <div
            v-if="$slots.footer"
            class="vf-modal-footer"
          >
            <slot name="footer" />
          </div>
        </div>
      </div>
    </div>
    <div class="vf-modal-backdrop in" />
  </div>
</template>
<script>
import { mdiClose } from '@mdi/js';

export default {
  props: {
    value: {
      type: Boolean,
      default: false
    },
    btnClose: {
      type: Boolean,
      default: false
    },
    title: {
      type: String,
      default: 'Modal'
    },
    small: {
      type: Boolean,
      default: false
    },
    large: {
      type: Boolean,
      default: false
    },
    full: {
      type: Boolean,
      default: false
    },
    force: {
      type: Boolean,
      default: false
    },
    transition: {
      type: String,
      default: 'modal'
    },
    okText: {
      type: String,
      default: 'OK'
    },
    cancelText: {
      type: String,
      default: 'Cancel'
    },
    okClass: {
      type: String,
      default: 'btn blue'
    },
    cancelClass: {
      type: String,
      default: 'btn red btn-outline'
    },
    closeWhenOK: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      duration: null,
      show: false,
      mdiClose
    }
  },
  computed: {
    modalClass() {
      return {
        'vf-modal-lg': this.large,
        'vf-modal-sm': this.small,
        'vf-modal-full': this.full
      }
    }
  },
  watch: {
    show(value) {
      if (value) {
        document.body.className += ' vf-modal-open'
        document.querySelector('html').className += ' vf-modal-open'
      } else {
        if (!this.duration) {
          this.duration =
            window
              .getComputedStyle(this.$el)
              ['transition-duration'].replace('s', '') * 1000
        }
        window.setTimeout(() => {
          document.body.className = document.body.className.replace(
            /\s?vf-modal-open/,
            ''
          )
          document.querySelector('html').className = document
            .querySelector('html')
            .className.replace(/\s?vf-modal-open/, '')
        }, this.duration || 0)
      }
    },
    value(value) {
      if (value) {
        document.body.className += ' vf-modal-open'
        document.querySelector('html').className += ' vf-modal-open'
      } else {
        if (!this.duration) {
          this.duration =
            window
              .getComputedStyle(this.$el)
              ['transition-duration'].replace('s', '') * 1000
        }
        window.setTimeout(() => {
          document.body.className = document.body.className.replace(
            /\s?vf-modal-open/,
            ''
          )
          document.querySelector('html').className = document
            .querySelector('html')
            .className.replace(/\s?vf-modal-open/, '')
        }, this.duration || 0)
      }
    }
  },
  created() {
    if (this.show || this.value) {
      document.body.className += ' vf-modal-open'
      document.querySelector('html').className += ' vf-modal-open'
    }
  },
  beforeDestroy() {
    document.body.className = document.body.className.replace(
      /\s?vf-modal-open/,
      ''
    )
    document.querySelector('html').className = document
      .querySelector('html')
      .className.replace(/\s?vf-modal-open/, '')
  },
  methods: {
    ok() {
      this.$emit('ok')
      if (this.closeWhenOK) {
        this.show = false
        this.$emit('input', false)
      }
    },
    cancel() {
      this.$emit('cancel')
      this.show = false
      this.$emit('input', false)
    },
    clickMask() {
      if (!this.force) {
        this.cancel()
      }
    }
  }
}
</script>
<style scoped>
.vf-modal-transition {
  transition: all 0.6s ease;
}

.vf-modal-leave {
  border-radius: 1px !important;
}

.vf-modal-transition .vf-modal-dialog,
.vf-modal-transition .vf-modal-backdrop {
  transition: all 0.5s ease;
}

.vf-modal-enter .vf-modal-dialog,
.vf-modal-leave .vf-modal-dialog {
  opacity: 0;
  transform: translateY(-30%);
}

.vf-modal-enter .vf-modal-backdrop,
.vf-modal-leave .vf-modal-backdrop {
  opacity: 0;
}
</style>
