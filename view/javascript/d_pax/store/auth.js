import gql from 'graphql-tag'

export const state = () => ({
  isLogged: null,
  showLogin: false,
  email: '',
  existingEmail: null
})

export const mutations = {
  setAuth(state, auth) {
    state.isLogged = auth
    this.$cookie.set('auth', auth)
  },
  toggleShowLogin(state) {
    state.showLogin = !state.showLogin
  },
  logout(state, payload) {
    state.isLogged = false
    this.$cookie.delete('auth')
    this.$cookie.delete('vfCmsId')
  },
  login(state, token) {
    state.isLogged = token
    this.$cookie.set('auth', token)
  },
  setEmail(state, payload) {
    state.email = payload
  },
  setExistingEmail(state, payload) {
    state.existingEmail = payload
  }
}

export const getters = {
  showLogin(state) {
    return state.showLogin
  },
  isLogged(state) {
    return state.isLogged
  },
  email(state) {
    return state.email
  },
  existingEmail(state) {
    return state.existingEmail
  }
}

export const actions = {
  async checkEmail({commit}, payload) {
    try {
      commit('setResponseError', false, {root: true})
      const {data} = await this.$apolloClient.defaultClient.mutate({
        mutation: gql`
          mutation($email: String) {
            accountCheckEmail(email: $email)
          }
        `,
        variables: {
          email: payload.email
        }
      })

      commit('setExistingEmail', data.accountCheckEmail)
      commit('setEmail', payload.email)
    } catch (e) {
      commit('setResponseError', e, {root: true})
    }
  },
  logout({commit}) {
    commit('logout', null)
    commit('account/setAccount', {}, {root: true})
    commit('setExistingEmail', '')
  },
  async resend({commit}) {
    try {
      commit('setResponseError', false, {root: true})
      const {data} = await this.$apolloClient.defaultClient.mutate({
        mutation: gql`
          mutation {
            accountResendRegister {
              id
              firstName
              lastName
              email
              banned
              confirmed
              times
              subscribe
              subscribeCancel
              subscribeDateEnd
              paymentMethodChecked
              image(width: 101, height: 101) {
                  url
                  path
              }
            }
          }
        `,
        variables: {}
      })


      commit('account/setAccount', data.accountResendRegister.user, {root: true})

    } catch (e) {
      commit('setResponseError', e, {root: true})
    }
  },
  async login({commit}, payload) {
    try {
      commit('setResponseError', false, {root: true})
      const {data} = await this.$apolloClient.defaultClient.mutate({
        mutation: gql`
          mutation($email: String, $password: String) {
            accountLogin(email: $email, password: $password) {
              token
              user {
                id
                firstName
                lastName
                email
                banned
                confirmed
                times
                subscribe
                subscribeCancel
                subscribeDateEnd
                paymentMethodChecked
                image(width: 101, height: 101) {
                    url
                    path
                }
              }
            }
          }
        `,
        variables: {
          email: payload.email,
          password: payload.password
        }
      })


      commit('account/setAccount', data.accountLogin.user, {root: true})
      commit('setAuth', data.accountLogin.token )

    } catch (e) {
      commit('setResponseError', e, {root: true})
    }
  },
  async register({commit}, payload) {
    try {
      commit('setResponseError', false, {root: true})
      const {data} = await this.$apolloClient.defaultClient.mutate({
        mutation: gql`
          mutation($user: UserInput) {
            accountRegister(user: $user) {
              token
              user {
                id
                firstName
                lastName
                email
                banned
                confirmed
                times
                subscribe
                subscribeCancel
                subscribeDateEnd
                paymentMethodChecked
                image(width: 101, height: 101) {
                    url
                    path
                }
              }
            }
          }
        `,
        variables: {
          user: payload
        }
      })


      commit('account/setAccount', data.accountRegister.user, {root: true})
      commit('setAuth', data.accountRegister.token )

    } catch (e) {
      commit('setResponseError', e, {root: true})
    }
  }
}
