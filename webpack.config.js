const TerserPlugin = require('terser-webpack-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
	Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
	.setOutputPath('public/build/')
	.setPublicPath('/build')
	.addEntry('app', './assets/app.js')
	.splitEntryChunks()
	.enableSingleRuntimeChunk()
	.cleanupOutputBeforeBuild()

	// Displays build status system notifications to the user
	// .enableBuildNotifications()

	.enableSourceMaps(!Encore.isProduction())
	.enableVersioning(Encore.isProduction())

	// enables and configure @babel/preset-env polyfills
	.configureBabelPresetEnv((config) => {
		config.useBuiltIns = 'usage';
		config.corejs = '3.38';
	})

	.configureFilenames({
		js: '[name]-[contenthash].js',
		css: '[name]-[contenthash].css',
	})

	.configureImageRule({
		type: 'asset/resource',
		filename: 'images/[name][ext]',
	})

	.configureFontRule({
		type: 'asset/resource',
		filename: 'fonts/[name][ext]',
	})

	.addLoader({
		test: /\.(js|jsx)$/,
		exclude: /node_modules/,
		use: [
			{
				loader: 'swc-loader',
				options: {
					parseMap: true,
					jsc: {
						parser: {
							jsx: true,
						},
					},
				},
			},
		],
	})
	.addLoader({
		test: /\.scss$/,
		exclude: /node_modules/,
		use: [
			MiniCssExtractPlugin.loader,
			{
				loader: 'css-loader',
			},
			{
				loader: 'postcss-loader',
			},
			{
				loader: 'sass-loader',
				options: {
					implementation: require('sass'),
				},
			}
		],
	})
	.addLoader({
		test: /\.css$/,
		exclude: /node_modules/,
		use: [
			MiniCssExtractPlugin.loader,
			{
				loader: 'css-loader',
			},
			{
				loader: 'postcss-loader',
			},
		],
	})
;

const optimization = {
	minimize: true,
	minimizer: [
		// Default .configureTerserPlugin() sucks.
		new TerserPlugin({
			parallel: true,
			minify: TerserPlugin.swcMinify,
			terserOptions: {
				compress: {
					drop_console: Encore.isProduction(),
					passes: 2,
				},
				format: {
					comments: false,
				},
			},
		}),
		// Default .configureCssMinimizerPlugin() sucks.
		new CssMinimizerPlugin({
			parallel: true,
			minify: CssMinimizerPlugin.cssoMinify,
		}),
	],
};

module.exports = {
	...Encore.getWebpackConfig(),
	cache: true,
	optimization,
};
