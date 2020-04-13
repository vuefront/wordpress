const variables = require('./assets/scss/variables.json')
module.exports = () => ({
  syntax: 'postcss-scss',
  plugins: [
    require('autoprefixer')(),
    require('postcss-mixins'),
    require('postcss-css-variables')({
      variables
    }),
    require('postcss-custom-media')({
      importFrom: './assets/scss/customMediaVariables.css'
    }),
    require('postcss-font-magician')({
      protocol: 'https:',
      foundries: 'bootstrap google'
    }),
    require('postcss-hexrgba')(),
    require('postcss-color-function')(),
    require('postcss-nested')(),
    require('postcss-rem-to-pixel')({
      rootValue: 12
    })
  ]
})
