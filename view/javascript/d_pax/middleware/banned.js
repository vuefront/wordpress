export default function ({ redirect, store }) {
  const banned = store.getters['account/banned']
  if (!banned) {
    return redirect('/')
  }
}
