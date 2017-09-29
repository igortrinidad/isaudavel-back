var elixir  = require('laravel-elixir'),
    gulp    = require('gulp'),
    htmlmin = require('gulp-htmlmin');

elixir.extend('compress', function() {
    new elixir.Task('compress', function() {
        return gulp.src('./storage/framework/views/*')
            .pipe(htmlmin({
                collapseWhitespace:    true,
                removeAttributeQuotes: true,
                removeComments:        true,
                minifyJS:              true,
                minifyCSS:              true,
            }))
            .pipe(gulp.dest('./storage/framework/views/'));
    })
    .watch('./storage/framework/views/*');
});

elixir(function(mix) {
    mix.compress();

    //STYLES PRELAUNCH
    mix.styles([

        '../../../node_modules/ionicons/css/ionicons.css',
        '../../../node_modules/bootstrap/dist/css/bootstrap.css',
        '../../../node_modules/swiper/dist/css/swiper.css',
        '../../../node_modules/sweetalert2/dist/sweetalert2.css',
        '../../../node_modules/unitegallery/dist/css/unite-gallery.css',
        '../../../node_modules/unitegallery/dist/themes/default/ug-theme-default.css',
        '../../../node_modules/vue-multiselect/dist/vue-multiselect.min.css',
        '../prelaunch/css/owl.carousel.css',
        '../prelaunch/css/font-awesome.min.css',
        '../prelaunch/css/animate.css',
        '../prelaunch/css/main.css',
        '../prelaunch/css/responsive.css',
        '../helpers.css',
        '../landing/css/general.css',
        '../landing/css/fonts.css',
    ], 'public/build/prelaunch/css/build_vendors_custom.css');

    mix.copy([
        '/node_modules/ionicons/fonts'
    ], './public/build/landing/fonts');


    //STYLES LANDING
    mix.styles([

        '../../../node_modules/ionicons/css/ionicons.css',
        '../../../node_modules/bootstrap/dist/css/bootstrap.css',
        '../../../node_modules/swiper/dist/css/swiper.css',
        '../../../node_modules/sweetalert2/dist/sweetalert2.css',
        '../../../node_modules/unitegallery/dist/css/unite-gallery.css',
        '../../../node_modules/unitegallery/dist/themes/default/ug-theme-default.css',
        '../../../node_modules/vue-multiselect/dist/vue-multiselect.min.css',
        '../../../node_modules/izitoast/dist/css/iziToast.css',
        '../prelaunch/css/owl.carousel.css',
        '../prelaunch/css/font-awesome.min.css',
        '../prelaunch/css/animate.css',
        '../prelaunch/css/main.css',
        '../prelaunch/css/responsive.css',
        '../helpers.css',
        '../landing/css/general.css',
        '../landing/css/fonts.css',
    ], 'public/build/landing/css/build_vendors_custom.css');


    //JS PRELAUNCH
    mix.scripts([
        '../../../node_modules/jquery/dist/jquery.min.js',
        '../../../node_modules/bootstrap/dist/js/bootstrap.min.js',
        '../../../node_modules/moment/moment.js',
        '../../../node_modules/vue/dist/vue.js',
        '../../../node_modules/vue-resource/dist/vue-resource.js',
        '../../../node_modules/swiper/dist/js/swiper.jquery.js',
        '../../../node_modules/sweetalert2/dist/sweetalert2.js',
        '../../../node_modules/unitegallery/dist/js/unitegallery.min.js',
        '../../../node_modules/unitegallery/dist/themes/tiles/ug-theme-tiles.js',
        '../../../node_modules/vue-multiselect/dist/vue-multiselect.min.js',
        '../../../node_modules/accounting/accounting.js',
        '../../../node_modules/slug/slug.js',
        '../prelaunch/js/vendor/modernizr-2.6.2.min.js',
        '../prelaunch/js/jquery.lwtCountdown-1.0.js',
        '../prelaunch/js/owl.carousel.min.js',
        '../prelaunch/js/jquery.validate.min.js',
        '../prelaunch/js/jquery.form.js',
        '../prelaunch/js/jquery.nav.js',
        '../prelaunch/js/jquery.sticky.js',
        '../prelaunch/js/plugins.js',
        '../prelaunch/js/wow.min.js',
        '../prelaunch/js/main.js',

    ], 'public/build/prelaunch/js/build_vendors_custom.js');


    //JS LANDING
    mix.scripts([
        '../../../node_modules/jquery/dist/jquery.min.js',
        '../../../node_modules/bootstrap/dist/js/bootstrap.min.js',
        '../../../node_modules/moment/moment.js',
        '../../../node_modules/vue/dist/vue.js',
        '../../../node_modules/vue-resource/dist/vue-resource.js',
        '../../../node_modules/swiper/dist/js/swiper.jquery.js',
        '../../../node_modules/sweetalert2/dist/sweetalert2.js',
        '../../../node_modules/unitegallery/dist/js/unitegallery.min.js',
        '../../../node_modules/unitegallery/dist/themes/tiles/ug-theme-tiles.js',
        '../../../node_modules/vue-multiselect/dist/vue-multiselect.min.js',
        '../../../node_modules/accounting/accounting.js',
        '../../../node_modules/slug/slug.js',
        '../../../node_modules/jquery-mask-plugin/dist/jquery.mask.js',
        '../../../node_modules/izitoast/dist/js/iziToast.js',
        '../../../node_modules/lodash/lodash.js',
        '../../../node_modules/vue-slider-component/dist/index.js',
        '../../../node_modules/vue2-google-maps/dist/vue-google-maps.js',
        '../prelaunch/js/vendor/modernizr-2.6.2.min.js',
        '../prelaunch/js/jquery.lwtCountdown-1.0.js',
        '../prelaunch/js/owl.carousel.min.js',
        '../prelaunch/js/jquery.validate.min.js',
        '../prelaunch/js/jquery.form.js',
        '../prelaunch/js/jquery.nav.js',
        '../prelaunch/js/jquery.sticky.js',
        '../prelaunch/js/plugins.js',
        '../prelaunch/js/wow.min.js',
        '../landing/js/main.js',

    ], 'public/build/landing/js/build_vendors_custom.js');

    mix.version([
        'public/build/prelaunch/css/build_vendors_custom.css', 
        'public/build/prelaunch/js/build_vendors_custom.js',
        
        'public/build/landing/css/build_vendors_custom.css', 
        'public/build/landing/js/build_vendors_custom.js',
    ]);

});