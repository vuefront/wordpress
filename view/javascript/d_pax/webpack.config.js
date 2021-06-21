const path = require('path')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const VueLoaderPlugin = require('vue-loader/lib/plugin')
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer')
const WebpackManifestGeneratorPlugin = require('./webpack-manifest')
const CleanWebpackPlugin = require('clean-webpack-plugin').CleanWebpackPlugin
const webpack = require('webpack')
const url = require('url')
const zlib = require('zlib')
const _ = require('lodash')
const fs = require('fs')
const paxConfig = require('./pax.config')
require('dotenv').config()
const polyfill =  !_.isUndefined(paxConfig.polyfill) ? paxConfig.polyfill : true

const isWin = process.platform === "win32";

const host = process.env.HOST || 'localhost'
const port = process.env.PORT || '3000'
const analyzer = process.env.ANALYZER || false

module.exports = (env, argv) => {
  const isDev = argv.mode === 'development'

  const currentUrl = getCurrentUrl()
  const currentDir = getCurrentDir()
  const distRelativePath = getDistRelativePath(currentDir)

  const rewriteUrl = url.parse(currentUrl)
  const proxyUrl = 'http://' + host + ':' + port + rewriteUrl.path
  const publicPath = isDev
    ? 'http://' +
      host +
      ':' +
      port +
      rewriteUrl.path +
      distRelativePath.replace(/\\/g, '/')
    : '/' + distRelativePath.replace(/\\/g, '/')

  const publicRelativePath = isDev ? distRelativePath.replace(/\\/g, '/') : process.env.PLUGIN_URL
  let rewriteUrls = {}
  rewriteUrls[currentUrl] = ''
  rewriteUrls['^' + rewriteUrl.path] = ''

  const plugins = []

  if (isDev) {
    if (analyzer) {
      plugins.push(
        new BundleAnalyzerPlugin({
          analyzerMode: 'static',
          openAnalyzer: false,
          reportFilename: 'stat/index.html',
          logLevel: 'silent'
        })
      )
    }
  }

  let entry = []

  if(polyfill) {
    entry = [...entry, '@babel/polyfill']
  }

  return {
    entry: [...entry, path.resolve(__dirname, './core/main.js')],
    output: {
      path: path.resolve(__dirname, `../${paxConfig.codename}/`),
      filename: '[fullhash].bundle.js',
      publicPath: publicPath
    },
    resolve: {
      extensions: ['*', '.js', '.vue', '.json', '.gql'],
      alias: {
        '~': path.resolve(__dirname, './'),
        assets: path.resolve(__dirname, './assets'),
        vue$: 'vue/dist/vue.esm.js'
      }
    },
    performance: {
      hints: false
    },
    devServer: {
      index: '',
      publicPath,
      // noInfo: true,
      open: true,
      host: host,
      port: port,
      overlay: {
        errors: true,
        warnings: false
      },
      // stats: 'errors-only',
      open: false,
      hotOnly: true,
      stats: {
        preset: 'minimal',
        moduleTrace: true,
        errorDetails: true
      },
      historyApiFallback: true,
      compress: false,
      proxy: {
        '/': {
          target: currentUrl,
          secure: false,
          changeOrigin: true,
          autoRewrite: true,
          pathRewrite: rewriteUrls,
          headers: {
            'X-ProxiedBy-Webpack': true
          },
          onProxyRes(proxyRes, req, res) {
            var _write = res.write
            if (
              /^text\/html/.test(proxyRes.headers['content-type'])
            ) {
              res.write = buffer => {
                try {
                  const isZipped =
                    proxyRes.headers['content-encoding'] === 'gzip'
                  let body = (isZipped
                    ? zlib.gunzipSync(buffer)
                    : buffer
                  ).toString('utf8')
                  body = _.replace(body, new RegExp(currentUrl, 'g'), proxyUrl)
                  body = body
                    .split(currentUrl.replace(/\//g, '\\/'))
                    .join(proxyUrl.replace(/\//g, '\\/'))
                  body = body
                    .split(currentUrl.replace(/http[s]?:/g, ''))
                    .join(proxyUrl.replace(/http[s]?:/g, ''))

                  let newBuffer = new Buffer.from(body)

                  const gzipRes =  isZipped ? zlib.gzipSync(newBuffer) : newBuffer
                  if(isZipped) {
                    res.setHeader('content-length', gzipRes.length);
                  }
                  _write.call(res, gzipRes)
                } catch (e) {
                  _write.call(res, buffer)
                }
              }
            }
          }
        }
      }
    },
    stats: {
      // colors: true,
      // hash: false,
      // version: true,
      // timings: true,
      // assets: true,
      // chunks: false,
      // modules: false,
      // reasons: false,
      // children: false,
      // source: false,
      // errors: true,
      // errorDetails: false,
      // warnings: true,
      // publicPath: false
      preset: 'minimal',
      moduleTrace: true,
      errorDetails: true
    },
    devtool: 'eval-source-map',
    module: {
      rules: [
        {
          test: /\.pug$/,
          loader: 'pug-plain-loader'
        },
        {
          resourceQuery: /blockType=i18n/,
          type: 'javascript/auto',
          loader: '@kazupon/vue-i18n-loader',
        },
        {
          test: /\.(graphql|gql)$/,
          use: 'graphql-tag/loader'
        },
        {
          test: /\.vue$/,
          loader: 'vue-loader',
          options: {
            extractCSS: true
          }
        },
        {
          test: /\.js$/,
          exclude: /node_modules/,
          use: ['babel-loader']
        },
        {
          test: /\.css$/,
          use: [
            {
              loader: MiniCssExtractPlugin.loader,
              options: {
                esModule: false,
              },
            },
            'css-loader'
          ]
        },
        {
          test: /\.(pcss|postcss)$/,
          use: [
              {
                loader: MiniCssExtractPlugin.loader,
                options: {
                  esModule: false,
                },
              },
            'css-loader',
            'postcss-loader'
          ]
        },
        {
          test: /\.scss$/,
          use: [
              {
                loader: MiniCssExtractPlugin.loader,
                options: {
                  esModule: false,
                },
              },
            'css-loader',
            'postcss-loader',
            'sass-loader',
            {
              loader: 'sass-resources-loader',
              options: {
                resources: [
                  path.resolve(__dirname, './assets/scss/_colors.scss'),
                  path.resolve(__dirname, './assets/scss/_variables.scss')
                ]
              }
            }
          ]
        },
        {
          test: /\.(png|jpe?g|gif|svg|webp)$/,
          use: [
            {
              loader: 'url-loader',
              options: {
                limit: 17192,
                esModule: false,
                fallback: 'file-loader',
                context: path.resolve(__dirname, './assets'),
                outputPath: './',
                publicPath: '../'+publicRelativePath,
                name: '[path][name].[ext]'
              }
            }
          ]
        },
        {
          enforce: 'pre',
          test: /\.(js|vue)$/,
          loader: 'eslint-loader',
          exclude: /node_modules/,
          options: {
            fix: true,
          }
        }
      ]
    },
    plugins: [
      new MiniCssExtractPlugin({
        filename: '[name].[fullhash].css',
        chunkFilename: '[id].[fullhash].css'
      }),
      new VueLoaderPlugin(),
      new WebpackManifestGeneratorPlugin({
        filename: 'manifest.json'
      }),
      new webpack.ProgressPlugin(),
      new CleanWebpackPlugin({
        cleanStaleWebpackAssets: false,
        root: path.resolve(__dirname, '../'),
        verbose: false,
        dry: false,
        watch: false
      }),
      new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/),
      ...plugins
    ],
    optimization: {
      splitChunks: {
        chunks: 'async',
        cacheGroups: {
          defaultVendors: {
            test: /[\\/]node_modules[\\/]/,
            priority: -10,
            maxSize: 200000,
            name: 'vendor',
            // chunks: 'initial',
            enforce: true
          },
          default: {
            minChunks: 2,
            priority: -20,
            reuseExistingChunk: true
          }
        }
      }
    }
  }
}
const loadConfig = () => {
  let configFile = false
  let currentDir = false
  do {
    currentDir = currentDir
      ? path.resolve(currentDir, '../')
      : path.resolve(__dirname, '../')
    configFile = fs.existsSync(path.resolve(currentDir, './wp-config.php'))
  } while (!configFile)

  return fs
    .readFileSync(path.resolve(currentDir, './wp-config.php'))
    .toString('UTF-8')
}

const getCurrentUrl = () => {
  return process.env.SITE_URL || 'http://localhost/'
}
const getCurrentDir = () => {
  let configFile = false
  let currentDir = false
  do {
    currentDir = currentDir
      ? path.resolve(currentDir, '../')
      : path.resolve(__dirname, '../')
    configFile = fs.existsSync(path.resolve(currentDir, './wp-config.php'))
  } while (!configFile)

  return currentDir
}
const getDistRelativePath = currentDir => {
  const cDir = path.resolve(currentDir, './')
  const nDir = path.resolve(__dirname, `../${paxConfig.codename}/`)

  if(isWin) {
    return _.replace(nDir, cDir + '\\', '')
  } else {
    return _.replace(nDir, cDir + '/', '')
  }
}
