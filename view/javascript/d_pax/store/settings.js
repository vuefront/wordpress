import gql from 'graphql-tag'
import _ from 'lodash'
export const state = () => ({
  setting: {},
  edit: false
})

export const mutations = {
  setSetting(state, payload) {
    state.setting = payload
  },
  setEdit(state, payload) {
    state.edit = payload
  }
}

export const getters = {
  get(state) {
    return state.setting
  },
  edit(state) {
    return state.edit
  }
}

export const actions = {

  async load({commit}) {
    try {
      commit('setResponseError', false, {root: true})
      const {data} = await this.$axios.get('/api/vf_settings')

      commit('setSetting', data)
    } catch (e) {
      commit('setResponseError', e, {root: true})
    }
  },
  async edit({commit, getters}, payload) {
    try {
      const setting = _.merge(getters.get, payload)
      commit('setResponseError', false, {root: true})
      await this.$axios.post('/api/vf_settings_edit', {setting})

    } catch (e) {
      commit('setResponseError', e, {root: true})
    }
  }
}
