<template>
  <div class="header-account">
    <b-nav-item-dropdown
      toggle-class="text-decoration-none text-1 m-0 font-weight-semibold"
      no-caret
      text
    >
      <template v-slot:button-content>
        <img
          :src="accountImage"
          width="24px"
          class="header-account__image"
          width-amp="24"
          height-amp="24"
          layout="fixed"
        >
        <span class="header-account__full_name">{{ account.firstName }} {{ account.lastName }}</span>
        <font-awesome-icon
          :icon="faAngleDown"
          class="header-account__icon"
        />
      </template>
      <a
        href="https://vuefront.com/account/cms"
        target="_blank"
        class="text-2"
      >
        Cms
      </a>
      <a
        class="text-2"
        href="https://vuefront.com/account/orders"
        target="_blank"
      >
        Themes
      </a>
      <a
        class="text-2"
        href="https://vuefront.com/account/account"
        target="_blank"
      >
        My Account
      </a>
      <hr class="header-account__separator">
      <a
        class="text-2"
        @click="logout"
      >
        Sign out
      </a>
    </b-nav-item-dropdown>
  </div>
</template>
<script>
import { mapGetters } from 'vuex'
import { isNull } from 'lodash'
import { BNavItemDropdown } from 'bootstrap-vue'
import { faAngleDown } from '@fortawesome/pro-light-svg-icons'
export default {
  components: {
    BNavItemDropdown
  },
  computed: {
    ...mapGetters({
      account: 'account/get'
    }),
    faAngleDown () {
      return faAngleDown
    },
    accountImage () {
      if (this.account.image && this.account.image.url !== '') {
        return this.account.image.url
      } else {
        return require('~/assets/img/profile.png')
      }
    }
  },
  methods: {
    async logout () {
      await this.$store.dispatch('auth/logout')
      this.$router.push('/check')
    }
  }
}
</script>
<style lang="scss">
  .header-account {
    display: flex;
    align-items: center;
    height: 52px;
    border-radius: 3px;
    border: solid 1px $white-five;
    background-color: $white;
    padding: 13px 17px;

    &__separator {
      border-top: 1px solid $white-eight;
      margin-top: 0;
      margin: 15px 0;
    }

    .dropdown {
      margin: 0;
      &:hover {
        > .dropdown-menu {
          display: block;
        }
        &:after {
          display: block;
        }
      }
      &:after {
        content: '';
        display: none;
        position: absolute;
        left: 0;
        right: 0;
        bottom: -15px;
        height: 15px;
      }
      .dropdown-menu {
        margin-top: 10px;
        min-width: 226px;
        border: none;
        border-radius: 2px;
        box-shadow: 0 0 30px 0 rgba(0, 0, 0, 0.1);
        padding: 30px 0 20px;
        outline: 0;
        a {
          margin: 0 30px;
          text-decoration: none;
          display: block;
          cursor: pointer;
          font-size: 18px;
          color: #1a1a1a;
          line-height: 2.22;
          outline: 0;
          box-shadow: none;
          &.btn {
            height: 42px;
            padding: 10px 15px;
          }
          &:last-child {
            margin-bottom: 0;
          }
          &:hover:not(.btn) {
            color: $dark-mint;
          }
        }
      }
    }

    .dropdown-toggle {
      display: flex;
      align-items: center;
      border: 0;
      outline: 0;
      box-shadow: none;
    }

    &__icon {
      font-size: 24px;
      color: #1a1a1a;
    }

    &__full_name {
      margin-right: 5px;
      font-size: 18px;
      font-weight: 600;
      line-height: 1.33;
      letter-spacing: 0.18px;
      color: #1a1a1a;
      padding-right: 100px;
    }

    &__image {
      border-radius: 50%;
      margin-right: 10px;
      width: 24px;
    }
  }
</style>
