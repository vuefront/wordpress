import Vue from 'vue'
import VueApollo from 'vue-apollo'
import ApolloClient from "apollo-boost";
export default ({ baseURL, apiURL, store }, inject) => {

  const apolloClient = new ApolloClient({
    uri: `${baseURL}?rest_route=/vuefront/v1/proxy&api_url=${apiURL}`,
    request: (operation) => {
      const headers = {}
      if (
        store.getters['auth/isLogged']
      ) {
        headers['token'] = `${
          store.getters['auth/isLogged']
        }`
      }
      operation.setContext({
        headers
      });
    }
  })

  Vue.use(VueApollo)
  const apolloProvider = new VueApollo({
    defaultClient: apolloClient
  })

  inject('apolloClient', apolloProvider)
}
