import gql from 'graphql-tag'

export const state = () => ({
  account: {},
  banned: false
})

export const mutations = {
  setAccount(state, payload) {
    state.account = payload
  },
  setBanned(state, payload) {
    state.banned = payload
  }
}

export const getters = {
  get(state) {
    return state.account
  },
  banned(state) {
    return state.banned
  }
}

export const actions = {
  async load({commit}) {
    try {
      commit('setResponseError', false, {root: true})

      const {data} = await this.$apolloClient.defaultClient.query({
        query: gql`
        {
          account {
            id
            firstName
            lastName
            confirmed
            email
            banned
            subscribe
            subscribeCancel
            subscribeDateEnd
            paymentMethodChecked
            image(width: 101, height: 101) {
                url
                path
            }
          }
          cmsList {
            content {
              id
              url
              type
            }
          }
        }
      `,
        variables: {}
      })

      commit('setAccount', data.account)
      commit('cms/setEntities', data.cmsList, {root: true})
      commit('setBanned', data.account.banned)
    } catch (e) {
      commit('setResponseError', e, {root: true})
    }
    this.$apolloClient.defaultClient.clearStore()
  }
}
