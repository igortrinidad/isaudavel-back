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


    mix.copy([
        '/node_modules/ionicons/fonts'
    ], './public/build/landing/fonts');

    mix.copy([
        './node_modules/summernote/dist/font',
    ], './public/build/build/landing/css/font');

    mix.copy('./resources/assets/landing/js/firebase-messaging-sw.js', './public/build/build/landing/js');


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
        '../../../node_modules/summernote/dist/summernote.css',
        '../../../node_modules/font-awesome/css/font-awesome.css',
        '../../../node_modules/owl.carousel/dist/assets/owl.carousel.css',
        '../landing/css/animate.css',
        '../landing/css/general.css',
        '../landing/css/fonts.css',
        '../landing/css/main.css',
        '../landing/css/buttons.css',
        '../helpers.css',

        //Oracle only
        '../../../node_modules/fullpage.js/dist/jquery.fullpage.css',

    ], 'public/build/landing/css/build_vendors_custom.css');


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
        '../../../node_modules/owl.carousel/dist/owl.carousel.js',
        '../landing/js/wow.min.js',
        '../landing/js/main.js',
        '../landing/js/prototypes.js',

        //Oracle only
        '../../../node_modules/summernote/dist/summernote.js',
        '../../../node_modules/screenfull/dist/screenfull.js',
        '../../../node_modules/fullpage.js/dist/jquery.fullpage.extensions.min.js',
        '../../../node_modules/chart.js/dist/Chart.js',

    ], 'public/build/landing/js/build_vendors_custom.js');

    mix.version([

        'public/build/landing/css/build_vendors_custom.css',
        'public/build/landing/js/build_vendors_custom.js',
    ]);

        mix.copy([
        '/node_modules/font-awesome/fonts',
    ], 'public/build/landing/fonts');

});
