const webpack = require('webpack');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const WriteFilePlugin = require('write-file-webpack-plugin');
const package = 'starter';
const SpeedMeasurePlugin = require('speed-measure-webpack-plugin');
const smp = new SpeedMeasurePlugin();

if (package === 'starter') {
  var elements = 'assets/elements_starter';
  var skeletonPath = 'assets/elements_starter/skeleton.html';
} else if (package === 'professional') {
  var elements = 'assets/elements_professional';
  var skeletonPath = 'assets/elements_professional/skeleton.html';
} else if (package === 'enterprise') {
  var elements = 'assets/elements_enterprise';
  var skeletonPath = 'assets/elements_enterprise/skeleton.html';
}

module.exports = env =>
  smp.wrap({
    entry: {
      builder: './assets/js/builder.js',
      sites: './assets/js/sites.js',
      templates: './assets/js/templates.js',
      images: './assets/js/images.js',
      settings: './assets/js/settings.js',
      users: './assets/js/users.js',
      login: './assets/js/login.js',
      register: './assets/js/register.js',
      packages: './assets/js/packages.js',
      sent: './assets/js/sent.js',
      autoupdate: './assets/js/autoupdate.js',
      inblock: './assets/js/inblock.js',
      elements_blocks: './assets/js/elements_blocks.js',
      elements_components: './assets/js/elements_components.js',
      elements_browser: './assets/js/elements_browser.js',
      file_editor: './assets/js/file_editor.js',
      integrations: './assets/js/integrations.js'
    },
    output: {
      path: __dirname + '/build/',
      publicPath: '/build/',
      filename: '[name].bundle.js'
    },
    devServer: {
      publicPath: __dirname + '/build',
      inline: true,
      headers: {
        'Access-Control-Allow-Origin': '*'
      }
    },
    module: {
      rules: [
        {
          test: /\.css$/,
          use: [MiniCssExtractPlugin.loader, 'css-loader']
        },
        {
          test: /\.scss$/,
          use: ['style-loader', 'css-loader?sourceMap', 'sass-loader?sourceMap']
        },
        {
          test: /\.js$/,
          include: [/assets\/js/],
          exclude: [/assets\/js\/custom/, /assets\/js\/vendor/],
          enforce: 'pre',
          use: [
            {
              loader: 'jshint-loader',
              options: {
                esversion: 6,
                bitwise: true,
                eqeqeq: true,
                forin: true,
                freeze: true,
                futurehostile: true,
                newcap: true,
                latedef: 'nofunc',
                noarg: true,
                nocomma: true,
                nonbsp: true,
                nonew: true,
                strict: true,
                undef: true,
                node: true,
                browser: true,
                loopfunc: true,
                laxcomma: true,
                '-W121': false,
                '-W089': false,
                '-W055': false,
                '-W069': false,
                '-W080': false,
                '-W038': false,
                eqeqeq: false,
                globals: {
                  define: false,
                  alert: false,
                  confirm: false,
                  ace: false,
                  $: false,
                  jQuery: false,
                  Slim: false,
                  slimImageUpdate: true,
                  slimHandleServerError: true,
                  slimImageTransform: true,
                  tail: false
                }
              }
            },
            {
              loader: 'babel-loader',
              options: {
                plugins: [
                  '@babel/plugin-transform-template-literals',
                  '@babel/plugin-transform-classes',
                  '@babel/plugin-transform-parameters',
                  require('babel-plugin-check-es2015-constants'),
                  '@babel/plugin-transform-block-scoping',
                  '@babel/plugin-transform-for-of',
                  '@babel/plugin-transform-arrow-functions',
                  require('babel-plugin-add-module-exports')
                ]
              }
            }
          ]
        },
        {
          test: /\.svg(\?v=\d+\.\d+\.\d+)?$/,
          use: {
            loader: 'url-loader',
            options: {
              limit: 10000,
              mimetype: 'image/svg+xml'
            }
          }
        },
        {
          test: /\.(jpe?g|png|gif|svg)$/i,
          use: [
            {
              loader: 'url-loader',
              options: {
                limit: 1000
              }
            },
            {
              loader: 'img-loader',
              options: {
                progressive: true
              }
            }
          ]
        },
        {
          test: /\.eot(\?v=\d+\.\d+\.\d+)?$/,
          loader: 'file-loader'
        },
        {
          test: /\.(woff(\?v=\d+\.\d+\.\d+)|woff2(\?v=\d+\.\d+\.\d+)|woff|woff2)$/,
          use: [
            {
              loader: 'url-loader',
              options: {
                limit: 5000,
                mimetype: 'aplication/font-woff'
              }
            }
          ]
        },
        {
          test: /\.ttf(\?v=\d+\.\d+\.\d+)?$/,
          use: [
            {
              loader: 'url-loader',
              options: {
                limit: 10000,
                mimetype: 'pplication/octet-stream'
              }
            }
          ]
        }
      ]
    },
    devtool: env && env.sourcemaps ? 'source-map' : false,
    plugins: [
      new WriteFilePlugin(),
      new MiniCssExtractPlugin({
        filename: '[name].css'
      }),
      new CleanWebpackPlugin(['build'], {
        root: __dirname,
        verbose: true,
        dry: false
      }),
      new CopyWebpackPlugin([
        { from: skeletonPath, to: __dirname + '/elements/skeleton.html' },
        { from: elements, to: __dirname + '/elements' },
        {
          from: 'assets/css/blocks.css',
          to: __dirname + '/elements/css/blocks.css'
        },
        { from: 'assets/images/thumbs', to: __dirname + '/elements/thumbs' },
        {
          from: 'assets/images/component_thumbs',
          to: __dirname + '/elements/thumbs/components'
        }
      ])
    ]
  });
