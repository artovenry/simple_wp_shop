_= require "underscore"
path= require "path"
webpack= require "webpack"
htmlWebpackPlugin= require "html-webpack-plugin"
VueLoaderPlugin= require "vue-loader/lib/plugin"
MiniCssExtractPlugin = require "mini-css-extract-plugin"
Autoprefixer= require "autoprefixer"

module.exports=
  mode: "development"
  devtool: "inline-source-map"
  resolve:
    extensions:[".js", ".coffee" ,".json"]
  devServer:
    host: process.env.npm_config_host ? "localhost"
    port: process.env.npm_config_port ? "8080"
  entry:
    main: "./src/main.coffee"
  module:
    rules: do ->
      babelLoader=
        loader: "babel-loader", options: presets: ["@babel/env"], plugins: ["transform-vue-jsx"]
      _.values
        JS     : test: /\.js$/, use: [babelLoader], exclude: /node_modules/
        COFFEE : test: /\.coffee$/, use: [babelLoader, {loader: "coffee-loader"}]
        VUE    : test: /\.vue$/, loader: "vue-loader"
        PUG    : test: /\.pug$/, loader: "pug-plain-loader"
        SCSS   : do ->
          loaders= (extract= no)-> _.compact [
            if extract then {loader: MiniCssExtractPlugin.loader, options: sourceMap: on}
            if not extract then {loader: "style-loader", options: sourceMap: on}
            {loader: "css-loader", options: sourceMap: on, importLoaders: 2}
            {loader: "postcss-loader", options: plugins: [Autoprefixer], sourceMap: on}
            {loader: "sass-loader", options: sourceMap: on}
          ]
          test: /\.s?css$/
          oneOf: _.values
            EXTRACT: resourceQuery: /extract/, use: loaders(extract= yes)
            DEFAULT: use: loaders(extract= no)
  plugins: _.compact _.flatten _.values
    Define:
      new webpack.DefinePlugin
        POSTCODE_API_KEY: JSON.stringify(process.env.npm_config_postcode_api_key ? "")
    provide: new webpack.ProvidePlugin
      _ : "underscore"
      TWEEN : "@tweenjs/tween.js"
      Color : "color-js"
    vue: new VueLoaderPlugin
    extract: new MiniCssExtractPlugin filename: "[name].css"
    # html: [
    #   new htmlWebpackPlugin()
    # ]
