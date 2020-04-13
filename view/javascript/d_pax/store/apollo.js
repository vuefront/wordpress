import omitDeepLodash from 'omit-deep-lodash'

export const state = () => ({
  data: {}
})

export const getters = {
  get(state) {
    return state.data
  }
}

export const mutations = {
  setData(state, payload) {
    state.data = payload.data
  }
}

export const actions = {
  async mutate({ commit }, options) {
    try {
      const variables = omitDeepLodash(options.variables, '__typename')
      let client = this.$apolloClient.defaultClient
      const res = await client.mutate({ ...options, variables })
      commit('setError', false, { root: true })
      commit('setData', res)
    } catch (e) {
      commit('setError', e.graphQLErrors ? e.graphQLErrors[0] : e, {
        root: true
      })
    }
  },
  async query({ commit }, options) {
    try {
      const variables = omitDeepLodash(options.variables, '__typename')
      let client = this.$apolloClient.defaultClient
      const res = await client.query({ ...options, variables })
      commit('setError', false, { root: true })
      commit('setData', res)
    } catch (e) {
      commit('setError', e.graphQLErrors ? e.graphQLErrors[0] : e, {
        root: true
      })
    }
  }
}
