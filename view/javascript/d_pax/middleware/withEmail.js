export default function ({ redirect, store }) {
  const isEmail = store.getters['auth/email'] !== ''
  if (!isEmail) {
    return redirect('/check')
  }
}
