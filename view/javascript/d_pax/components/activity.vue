<template>
  <div
    class="vf-activity"
  >
    <b-table
      id="my-table"
      class="vf-activity__table"
      outlined
      responsive
      :fields="fields"
      :items="options"
      :per-page="perPage"
      :current-page="currentPage"
      small
    >
      <template #cell(log)="data">
        <span
          v-if="data.item.status === null || data.item.status"
          class="vf-activity__success_build"
        />
        <span
          v-else-if="data.item.generating"
          class="vf-activity__pending_build"
        />
        <span
          v-else
          class="vf-activity__failed_build"
        />
        {{ data.item.generating ? $t('textGenerating') : $t('textGenerated') }} <span v-if="!data.item.generating">({{ Math.floor($moment.duration(data.item.time, 'milliseconds').asMinutes()) }} min)</span>
      </template>
      <template #cell(date)="data">
        {{ $moment(data.item.date).format('DD-MM-YYYY') }}
      </template>
      <template #cell(time)="data">
        {{ $moment(data.item.date).format('HH:mm') }}
      </template>
    </b-table>
    <div class="text-center">
      <b-pagination
        v-model="currentPage"
        :total-rows="cms.builds.length"
        :per-page="perPage"
        aria-controls="my-table"
      />
    </div>
  </div>
</template>
<script>

import {mapGetters} from 'vuex'
export default {
  data() {
    return {
      fields: [
        {
          key: 'log',
          label: this.$t('columnActivityLog'),
          sortable: false
        },
        {
          key: 'date',
          label: this.$t('columnDate'),
          sortable: true
        },
        {
          key: 'time',
          label: this.$t('columnTime'),
          sortable: false
        }
      ],
      perPage: 5,
      currentPage: 1
    }
  },
  computed: {
    ...mapGetters({
      cms: 'cms/get'
    }),
    options() {
      let result = []

      if(this.cms.generating) {
        result = [{status: false, generating: true, data: this.$moment().toISOString()} ,...result]
      }

      result = [...result,...this.cms.builds]
      return result
    }
  }
}
</script>
<style lang="scss">
  .vf-activity.vf-activity .vf-activity {
    margin-bottom: 60px;
    text-align: left;
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
            padding: 20px 50px;
            background-color: #fbfbfc;
          }
        }
        tbody {
          td {
            position: relative;
            > .vf-activity__success_build {
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
            > .vf-activity__failed_build {
              &:after {
                position: absolute;
                content: '';
                display: block;
                width: 10px;
                height: 10px;
                border-radius: 50%;
                background-color: #e64141;
                top: 50%;
                transform: translateY(-50%);
                left: 25px;
              }
            }
            > .vf-activity__pending_build {
              &:after {
                position: absolute;
                content: '';
                display: block;
                width: 10px;
                height: 10px;
                border-radius: 50%;
                background-color: $yellow;
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
            padding: 20px 50px;
          }
        }
      }
    }
  }
</style>

<i18n locale="en">
{
  "columnActivityLog": "Activity log",
  "columnDate": "Date",
  "columnTime": "Time",
  "textGenerated": "Web App files generated",
  "textGenerating": "Web App files generating"
}
</i18n>
