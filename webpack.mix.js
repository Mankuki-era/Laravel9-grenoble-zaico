const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

// mix.webpackConfig({
//     stats: {
//         children: true,
//     },
// });

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/router.js', 'public/js')
    .vue({ version: 2 })
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/share.scss', 'public/css')
    .sass('resources/sass/auth/login.scss', 'public/css/auth')
    .sass('resources/sass/item/index.scss', 'public/css/item')
    .sass('resources/sass/log/index.scss', 'public/css/log')
    .sass('resources/sass/stock/index.scss', 'public/css/stock')
    .sass('resources/sass/stock/step/input.scss', 'public/css/stock/step')
    .sass('resources/sass/stock/step/confirm.scss', 'public/css/stock/step')
    .sass('resources/sass/stock/step/result.scss', 'public/css/stock/step')
    .sass('resources/sass/modal/item.scss', 'public/css/modal')
    .sass('resources/sass/modal/import.scss', 'public/css/modal')
    .sass('resources/sass/modal/alert.scss', 'public/css/modal')
    .sass('resources/sass/modal/log.scss', 'public/css/modal')
    .sass('resources/sass/modal/login.scss', 'public/css/modal');
