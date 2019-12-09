export default function ({ redirect, store }) {
  const account = store.getters['account/get']
  if (account.confirmed) {
    return redirect('/')
  }
}
