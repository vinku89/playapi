const { mix } = require('laravel-mix').mix;

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

    mix.copy('packages/contus/base/src/resources/assets/css', 'public/contus/base/css/');
    mix.copy('packages/contus/base/src/resources/assets/fonts', 'public/contus/base/fonts/');
    mix.copy('packages/contus/base/src/resources/assets/js', 'public/contus/base/js/');
    mix.copy('packages/contus/cms/src/resources/assets/js', 'public/contus/cms/js/');
    mix.copy('packages/contus/customer/src/resources/assets/js', 'public/contus/customer/js/');
    mix.copy('packages/contus/customer/src/resources/assets/css', 'public/contus/customer/css/');
    mix.copy('packages/contus/notification/src/resources/assets/js', 'public/contus/notification/js/');
    mix.copy('packages/contus/payment/src/resources/assets/js', 'public/contus/payment/js/');
    mix.copy('packages/contus/user/src/resources/assets/js', 'public/contus/user/js/');
    mix.copy('packages/contus/base/src/resources/assets/images', 'public/contus/base/images/');
    mix.copy('packages/contus/video/src/resources/assets/js', 'public/contus/video/js/');


   mix.styles([
        'resources/assets/css/normalize.min.css',
        'resources/assets/css/owl.carousel.css',
        'resources/assets/css/flipclock.css',
        'resources/assets/css/customize.css',
        'resources/assets/css/responsive.css',
        'resources/assets/css/jquery.mCustomScrollbar.min.css',
        'resources/assets/css/angular-socialshare.min.css',
        'resources/assets/css/angularjs-datetime-picker.css',
        'resources/assets/css/skin/flowplayer.quality-selector.css',
        'resources/assets/css/skin/skins.css',
        'resources/assets/css/toastcommon.css'
    ],'public/contus/base/css/common.css');
    mix.scripts([
        'resources/assets/js/jquery-1.10.2.min.js',
        'resources/assets/js/angular/libs/angular.min.js',
        'resources/assets/js/angularjs-datetime-picker.js',
        'resources/assets/js/angular/libs/angular-ui-router.min.js',
        'resources/assets/js/angular/libs/angular-animate.min.js',
        'resources/assets/js/ng-flow-standalone.js',
        'resources/assets/js/angular/libs/angular-sanitize.min.js',
        'resources/assets/js/angular/libs/toast/ngToast.min.js',
        'resources/assets/js/angular/libs/ocLazyLoad.min.js',
        'resources/assets/js/angular/libs/loading-bar.min.js',
        'resources/assets/js/angular/libs/ui-bootstrap/ui-bootstrap-tpls-1.3.3.min.js',
        'resources/assets/flowplayer/flowplayer.min.js',
        'resources/assets/flowplayer/flowplayer.hlsjs.min.js',
        'resources/assets/js/bootstrap.min.js',
        'resources/assets/js/owl.carousel.min.js',
        'resources/assets/js/jquery.mCustomScrollbar.min.js',
        'resources/assets/js/flipclock.min.js',
        'resources/assets/js/requestFactory.js',
        'resources/assets/js/Validate.js',
        'resources/assets/js/validatorDirective.js',
        'resources/assets/js/intializeOwlCarouselDirective.js',
        'resources/assets/js/app.js',
        'resources/assets/js/extra.js',
        'resources/assets/js/angular-socialshare.min.js'
    ],'public/contus/base/js/global.js');