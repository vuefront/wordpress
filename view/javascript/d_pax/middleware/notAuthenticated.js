export default function ({ redirect, store }) {
  if (store.getters['auth/isLogged']) {
    return redirect('/')
  }
}
