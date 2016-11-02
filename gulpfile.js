const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(mix => {
    mix.sass('app.scss')
        .webpack('app.js');

    mix.sass('admin.scss')
        .webpack('admin.js');

    mix.version([
        'css/admin.css',
        'css/app.css',

        'js/admin.js',
        'js/app.js',
    ]);

    mix.copy('node_modules/font-awesome/fonts', 'public/fonts');
});
