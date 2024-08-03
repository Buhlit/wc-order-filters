const mix = require('laravel-mix');

mix.sass('resources/sass/app.scss', 'assets/css/admin/main.css').sourceMaps()
	.webpackConfig({
		devtool: 'source-map'
	})
	.options({
		processCssUrls: false
	});