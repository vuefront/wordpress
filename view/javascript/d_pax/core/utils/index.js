/**
 * Search and connect all application utilities
 * @author Dreamvention <info@dreamvention.com>
 * @date 20.12.2018
 */

/**
 * Utility downloads
 * @param {any} options Application settings
 */
const load = async (options, inject) => {
  const req = require.context('./', false, /\.js$/)
  const files = req.keys()

  for (const key in files) {
    const fileName = files[key]
    if (fileName === './index.js') continue
    if (fileName === './store.js') continue
    if (fileName === './i18n.js') continue
    if (fileName === './router.js') continue

    const util = req(fileName).default

    await util(options, inject)
  }
}

export { load }
