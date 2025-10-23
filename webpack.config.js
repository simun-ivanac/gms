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

	.enableSassLoader()

	.configureTerserPlugin((options) => {
		options.parallel = true;
		options.minify = TerserPlugin.swcMinify;
		options.terserOptions = {
			compress: {
				drop_console: true,
				passes: 2,
			},
			format: {
				comments: false,
			},
		}
	})

	.configureCssMinimizerPlugin((options) => {
		options.parallel = true;
		options.minify = CssMinimizerPlugin.cssoMinify;
	})

	.configureFilenames({
		js: '[name]-[contenthash].js',
		css: '[name]-[contenthash].css',
	});
;

module.exports = Encore.getWebpackConfig();
