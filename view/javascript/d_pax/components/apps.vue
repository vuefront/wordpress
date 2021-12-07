<template>
  <div
    class="vf-apps__wrapper"
  >
    <div class="vf-apps__wrapper_title">
      Connected Apps
    </div>
    <div class="vf-apps__wrapper_text">
      You can connect Apps to VueFront by providing thier JWT. To add an App, specify the App codename and JWT.
    </div>
    <b-input-group class="vf-apps__create_wrapper">
      <input
        v-model="form.codename"
        type="text"
        class="vf-apps__create_wrapper_codename"
        placeholder="App Codename"
      >
      <input
        v-model="form.jwt"
        type="text"
        class="vf-apps__create_wrapper_jwt"
        placeholder="App JWT"
      >
      <template #append>
        <b-button
          variant="success"
          @click="handleAdd"
        >
          Add
        </b-button>
      </template>
    </b-input-group>
    <div>
      <b-table-simple
        v-if="!isEmptyApps"
        class="vf-apps__table"
        responsive
      >
        <b-thead>
          <b-tr>
            <b-th>
              App Codename
            </b-th>
            <b-th>
              Date added
            </b-th>
            <b-th>
              Action
            </b-th>
          </b-tr>
        </b-thead>
        <b-tbody>
          <b-tr
            v-for="(value, index) in apps"
            :key="index"
          >
            <b-td>
              <span />{{ value.codename }}
            </b-td>
            <b-td>
              {{ $moment(value.dateAdded).format('DD-MM-YYYY') }}
            </b-td>
            <b-td>
              <b-button
                variant="primary"
                size="sm"
                @click="handleEdit(index)"
              >
                Edit
              </b-button>
              <b-button
                variant="danger"
                size="sm"
                @click="handleRemove(index)"
              >
                Delete
              </b-button>
            </b-td>
          </b-tr>
        </b-tbody>
      </b-table-simple>
    </div>
    <edit-app
      v-if="showEdit"
      :id="edit"
      :app="editApp"
    />
  </div>
</template>
<script>
import {isEmpty} from 'lodash'
import {mapGetters} from 'vuex'
import EditApp from './editApp'
export default {
  components: {
    EditApp
  },
  data() {
    return {
      edit: 0,
      editApp: {},
      form: {
        codename: '',
        jwt: ''
      }
    }
  },
  computed: {
    ...mapGetters({
      apps: 'apps/list',
      showEdit: 'apps/edit'
    }),
    isEmptyApps() {
      return isEmpty(this.apps)
    }
  },
  methods: {
    async handleAdd() {
      await this.$store.dispatch('apps/create', this.form)
      await this.$store.dispatch('apps/list')
    },
    async handleRemove(index) {
      await this.$store.dispatch('apps/remove', {key: index})
      await this.$store.dispatch('apps/list')
    },
    handleEdit(index) {
      this.$store.commit('apps/setEdit', true)
      this.editApp = this.apps[index]
      this.edit = index
    }
  }
}
</script>
<style lang="scss">
.vf-apps {
  &__table {
      .table {
        thead {
          th {
            font-family: 'Open Sans', sans-serif;
            font-size: 16px;
            font-weight: 600;
            font-stretch: normal;
            font-style: normal;
            line-height: 1.5;
            letter-spacing: 0.16px;
            text-align: left;
            color: $black;
            padding: 20px 50px!important;
            background-color: #fbfbfc;
          }
        }
        tbody {
          td {
            position: relative;
            > span {
              &:after {
                position: absolute;
                content: '';
                display: block;
                width: 10px;
                height: 10px;
                border-radius: 50%;
                background-color: $dark-mint;
                top: 50%;
                transform: translateY(-50%);
                left: 25px;
              }
            }
            font-family: 'Open Sans', sans-serif;
            font-size: 16px;
            font-weight: normal;
            font-stretch: normal;
            font-style: normal;
            line-height: 1.5;
            letter-spacing: 0.16px;
            text-align: left;
            color: $warm-grey-two;
            padding: 20px 50px !important;
          }
        }
      }
    }
  &__create_wrapper {
    margin-bottom: 20px;
    &_codename {
      width: 44%;
      padding: 10px;
      margin: 0px;
      border: none;
      background: #efeff1 !important;
      height: 54px !important;
      background-color: #efeff1 !important;
      padding: 12px 24px !important;
      line-height: 30px !important;
      font-family: 'Open Sans', sans-serif !important;
      font-size: 24px !important;
      font-weight: 600;
      font-stretch: normal;
      font-style: normal;
      letter-spacing: 0.24px;
      text-align: left;
      color: #1a1a1a !important;
      border: none !important;
      flex: 1;
      outline: 0;
    }
    &_jwt {
      width: 43%;
      padding: 10px;
      background: #efeff1 !important;
      border: none;
      margin: 0;
      height: 54px !important;
      background-color: #efeff1 !important;
      padding: 12px 24px !important;
      line-height: 30px !important;
      font-family: 'Open Sans', sans-serif !important;
      font-size: 24px !important;
      font-weight: 600;
      font-stretch: normal;
      font-style: normal;
      letter-spacing: 0.24px;
      text-align: left;
      color: #1a1a1a !important;
      border: none !important;
      flex: 1;
      outline: 0;
    }
  }
  &__wrapper {
      border-radius: 3px;
      border: 1px solid $white-five;
      background-color: $white;
      padding: 50px 55px;
      margin-bottom: 60px;
      @media (--phone-and-tablet) {
        padding: 20px 25px;
      }

      &_title {
        font-family: 'Open Sans', sans-serif;
        font-size: 28px;
        font-weight: 600;
        font-stretch: normal;
        font-style: normal;
        line-height: 0.86;
        letter-spacing: 0.28px;
        text-align: left;
        color: $black;
        margin-bottom: 20px;
        @media(--phone-and-tablet) {
          font-size: 24px;
        }
      }
      &_text {
        font-family: 'Open Sans', sans-serif;
        font-size: 18px !important;
        font-weight: normal;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.56;
        letter-spacing: 0.18px;
        text-align: left;
        color: #7d7d7d;
        margin-bottom: 10px;
      }
  }
}
</style>
