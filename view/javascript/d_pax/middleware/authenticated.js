export default function ({ redirect, store }) {
  const isAuth = store.getters['auth/isLogged']
  if (!isAuth) {
    return redirect('/login')
  }
}
