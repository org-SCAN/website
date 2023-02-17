const mix = require('laravel-mix');
require('laravel-mix-purgecss');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */



mix.js('resources/js/cytoscape/*', 'public/js/cytoscape/cytoscape.js')
    .extract(['cytoscape', 'cytoscape-dagre', 'cytoscape-cise', 'cytoscape-fcose', 'cytoscape-ctxmenu'], 'public/js/cytoscape/vendor.js');

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
    ])
    .extract()
    .purgeCss();
if (mix.inProduction()) {
    mix.version();
}
