import Vue from 'vue'
import VueApollo from 'vue-apollo'
import ApolloClient from "apollo-boost";
export default ({ baseURL, store }, inject) => {

  const apolloClient = new ApolloClient({
    uri: `${baseURL}index.php?rest_route=/vuefront/v1/proxy`,
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
