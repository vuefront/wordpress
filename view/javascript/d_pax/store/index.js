import {isUndefined, isNull} from 'lodash'
export const state = () => ({
  error: false
})

export const mutations = {
  setError(state, payload) {
    state.error = payload
  },
  setResponseError (state, e) {
    if (e.graphQLErrors && e.graphQLErrors.message) {
      state.error = e.graphQLErrors.message.error
    } else if (e.graphQLErrors && !isUndefined(e.graphQLErrors[0])) {
      state.error = e.graphQLErrors[0].message.error
    } else if (e.graphQLErrors) {
      state.error = e.graphQLErrors
    } else if (e.response){
      if(!isUndefined(e.response.data.data) && !isUndefined(e.response.data.data[0]) && e.response.data.data[0].message) {
        state.error = e.response.data.data[0].message
      } else {
        state.error = e.response.data.error
      }
    } else {
      state.error = e
    }
  },
}

export const getters = {
  error(state) {
    return state.error
  }
}

export const actions = {
  async clientInit({ dispatch, commit, getters }) {
    await dispatch('settings/load')
    if(!isNull(this.$cookie.get('auth'))) {
      commit('auth/setAuth', this.$cookie.get('auth'))

      if(getters['auth/isLogged']) {
        await dispatch('account/load')

        if(!getters['error']) {
          const id = await dispatch('cms/search')

          if(id) {
            await dispatch('cms/load', {id})
          }
        } else {
          await dispatch('auth/logout')
        }
      }
    } else {
      await dispatch('auth/logout')
    }
  }
}
