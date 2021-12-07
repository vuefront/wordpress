import gql from 'graphql-tag'
export const state = () => ({
  cms: {
    builds: []
  },
  entities: {},
  alien: false,
  firstBuild: false
})

export const mutations = {
  setCms(state, payload) {
    state.cms = payload
  },
  setEntities(state, payload) {
    state.entities = payload
  },
  setAlien(state, payload) {
    state.alien = payload
  },
  setFirstBuild(state, payload) {
    state.firstBuild = payload
  }
}

export const getters = {
  get(state) {
    return state.cms
  },
  list(state) {
    return state.entities
  },
  alien(state) {
    return state.alien
  },
  firstBuild(state) {
    return state.firstBuild
  }
}

export const actions = {

  async subscribe({commit}) {
    try {
      const { data } = await this.$apolloClient.defaultClient.mutate({
        mutation: gql`
          mutation {
            subscribeCms {
              id
              firstName
              lastName
              email
              npmToken
              banned
              confirmed
              balance
              times
              subscribe
              subscribeCancel
              subscribeDateEnd
              paymentMethodChecked
              image(width: 101, height: 101) {
                  url
                  path
              }
              developer {
                linkSupport
                status
                username
              }
              role {
                name
              }
            }
          }
        `,
        variables: {}
      })
      commit('account/setAccount', data.subscribeCms, { root: true })
    } catch (e) {
      commit('setResponseError', e, { root: true })
    }
  },
  async search({commit, getters, dispatch, rootGetters}) {
    let id = false
    commit('setAlien', false)
    for(const key in getters['list'].content) {
      if(getters['list'].content[key].url === this.$paxOptions.siteUrl) {
        id = getters['list'].content[key].id
      }
    }
    if(!id) {
      id = await dispatch('create')
      if(rootGetters['error'] && rootGetters['error'] === 'alien_cms') {
        commit('setAlien', true)
        commit('setResponseError', false, {root: true})
        // this.$router.push('/alien')
      }
    }

    if(!id) {
      return false
    }

    return id
  },
  async create({commit}) {
    try {
      commit('setResponseError', false, {root: true})

      const {data} = await this.$apolloClient.defaultClient.mutate({
        mutation: gql`
          mutation($cms: CmsInput) {
            addCms(cms: $cms) {
              id
            }
          }
        `,
        variables: {
          cms: {
            url: this.$paxOptions.siteUrl,
            type: this.$paxOptions.type
          }
        }
      })

      return data.addCms.id

    } catch (e) {
      commit('setResponseError', e, {root: true})
    }

    return false
  },
  async generate({commit}, payload) {

    try {
      commit('setResponseError', false, {root: true})

      const {data} = await this.$apolloClient.defaultClient.mutate({
        mutation: gql`
          mutation($id: String) {
            accountCmsGenerate(id: $id) {
              status
            }
          }
        `,
        variables: payload
      })
    } catch (e) {
      commit('setResponseError', e, {root: true})
    }
    this.$apolloClient.defaultClient.clearStore()
  },
  async list({commit}) {
    try {
      commit('setResponseError', false, {root: true})

      const {data} = await this.$apolloClient.defaultClient.query({
        query: gql`
          {
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

      commit('setEntities', data.cmsList)
    } catch (e) {
      commit('setResponseError', e, {root: true})
    }
    this.$apolloClient.defaultClient.clearStore()
  },
  async load({commit}, {id}) {
    try {
      commit('setResponseError', false, {root: true})

      const {data} = await this.$apolloClient.defaultClient.query({
        query: gql`
          query($id: String){
            cms(id: $id) {
              id
              url
              downloadUrl
              generating
              usagedTime
              builds {
                id
                time
                date
                buildId
                status
              }
            }
          }
        `,
        variables: {
          id
        }
      })

      commit('setCms', data.cms)
    } catch (e) {
      commit('setResponseError', e, {root: true})
    }
  }
}
