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
            }))
            .pipe(gulp.dest('./storage/framework/views/'));
    })
    .watch('./storage/framework/views/*');
});

elixir(function(mix) {
    mix.compress();

    //STYLES
    mix.styles([

        '../../../node_modules/ionicons/dist/css/ionicons.min.css',
        '../../../node_modules/bootstrap/dist/css/bootstrap.min.css',
        '../prelaunch/css/owl.carousel.css',
        '../prelaunch/css/font-awesome.min.css',
        '../prelaunch/css/animate.css',
        '../prelaunch/css/main.css',
        '../prelaunch/css/responsive.css',
        '../helpers.css',
    ], 'public/build/prelaunch/css/build_vendors_custom.css');


    //JS
    mix.scripts([
        '../../../node_modules/jquery/dist/jquery.min.js',
        '../../../node_modules/bootstrap/dist/js/bootstrap.min.js',
        '../../../node_modules/moment/moment.js',
        '../../../node_modules/vue/dist/vue.js',
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

    mix.version([
        'public/build/prelaunch/css/build_vendors_custom.css', 
        'public/build/prelaunch/js/build_vendors_custom.js'
    ]);

});