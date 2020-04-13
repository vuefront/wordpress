import { isUndefined } from 'lodash'
/**
 * Search and connect plugins
 * @author Dreamvention <info@dreamvention.com>
 * @date 20.12.2018
 */
export default async (options, inject) => {
  const req = require.context('~/plugins/', false, /\.js$/)
  const files = req.keys()

  for (const fileName of files) {
    const plugin = req(fileName)

    if (!isUndefined(plugin.default)) {
      await plugin.default(options, inject)
    }
  }
}
