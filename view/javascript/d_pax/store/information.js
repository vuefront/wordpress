export const state = () => ({
  information: {}
})

export const mutations = {
  setInformation(state, payload) {
    state.information = payload
  }
}

export const getters = {
  get(state) {
    return state.information
  }
}

export const actions = {
  async updateVueFront({commit}, payload) {
    try {
      commit('setResponseError', false, {root: true})

      const {data} = await this.$axios.post('/api/vf_update', payload)

      commit('setInformation', data)
    } catch (e) {
      commit('setResponseError', e, {root: true})
    }
  },
  async activateVueFront({commit}, payload) {
    try {
      commit('setResponseError', false, {root: true})

      const {data} = await this.$axios.post('/api/vf_turn_on', payload)

      commit('setInformation', data)
    } catch (e) {
      commit('setResponseError', e, {root: true})
    }
  },
  async deActivateVueFront({commit, rootGetters}, payload) {
    try {
      commit('setResponseError', false, {root: true})

      const {data} = await this.$axios.post('/api/vf_turn_off', payload)

      commit('setInformation', data)
    } catch (e) {
      commit('setResponseError', e, {root: true})
    }
  },
  async load({commit}) {
    try {
      commit('setResponseError', false, {root: true})

      const {data} = await this.$axios.get('/api/vf_information')

      commit('setInformation', data)
    } catch (e) {
      commit('setResponseError', e, {root: true})
    }
  }
}
