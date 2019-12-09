export default function ({ redirect, store }) {
  const alien = store.getters['cms/alien']
  if (alien) {
    return redirect('/alien')
  }
}
