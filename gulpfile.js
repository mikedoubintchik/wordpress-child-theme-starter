var gulp = require('gulp'),
    plugins = require('gulp-load-plugins')(),
    sass = require('gulp-sass'),
    postcss = require('gulp-postcss'),
    autoprefixer = require('autoprefixer'),
    pxtorem = require('postcss-pxtorem'),
    mqpacker = require('css-mqpacker'),
    cssnano = require('cssnano'),
    uncss = require('postcss-uncss'),
    eslint = require('gulp-eslint'),
    sourceMaps = require('gulp-sourcemaps'),
    browserSync = require('browser-sync').create(),
    uglify = require('gulp-uglify'),
    concat = require('gulp-concat'),
    rename = require('gulp-rename'),
    imagemin = require('gulp-imagemin'),
    cache = require('gulp-cache'),
    del = require('del'),
    runSequence = require('run-sequence'),
    glob = require('gulp-sass-glob'),
    notify = require('gulp-notify'),
    babel = require('gulp-babel'),
    webpackStream = require('webpack-stream'),
    webpack = require('webpack');

// SASS Paths
var sassPaths = ['./node_modules'];

// Define server
var server = 'example.dev';

// declare file and folder paths
var baseDir = 'src';
var jsFolder = baseDir + '/js';
var cssFolder = baseDir + '/styles';

var jsFiles = {
    main: jsFolder + '/main.js',
    vendor: jsFolder + '/vendor/**/*.js'
};

var sassFiles = cssFolder + '/**/*.scss';

var buildFolder = 'build';
var distFolder = 'dist';

// Test to see if a dist task was invoked to know if we're running a production-like build
var isDist = (argList => {
    const distRegex = /^dist(:.+|$)/;

    for (let i = 0; i < argList.length; i++) {
        const thisOpt = argList[i].trim();

        if (distRegex.test(thisOpt)) {
            return true;
        }
    }

    return false;
})(process.argv);


// Lint JS files
gulp.task('eslint', function () {
    return gulp.src([jsFiles.main])
        .pipe(eslint())
        .pipe(eslint.format())
        .pipe(eslint.failAfterError())
        .on('error', notify.onError({
            title: 'Gulp',
            subtitle: 'Failure!',
            message: 'Error: <%= error.message %>',
            sound: 'Beep'
        }));
});

// ES6 Support
gulp.task('bundle', function () {
    let destinationFolder, webpackMode, webpackDevtool;

    if (isDist) {
        destinationFolder = distFolder;
        webpackMode = 'production';
        webpackDevtool = false;
    } else {
        destinationFolder = buildFolder;
        webpackMode = 'development';
        webpackDevtool = 'source-map';
    }

    return gulp.src(jsFiles.main)
        .pipe(babel({
            presets: [
                [
                    "env",
                    {
                        "targets": {
                            "browsers": [
                                "last 2 versions",
                                "ie >= 11",
                                "last 3 iOS versions"
                            ]
                        }
                    }
                ]
            ]
        }))
        .pipe(webpackStream({
            entry: {
                app: './' + jsFolder + '/main.js',
            },
            output: {
                filename: '[name].bundle.js'
            },
            mode: webpackMode,
            devtool: webpackDevtool,
        }, webpack).on('error', notify.onError({message: 'webpack bundling failed'})))
        .pipe(gulp.dest(destinationFolder + '/js'));
});

/*
 * Concatenate jsFiles.vendor and jsFiles.source into one JS file,
 * run eslint before concatenating.
 */
gulp.task('js', ['eslint', 'bundle'], function () {
    return gulp.src([jsFiles.vendor, jsFiles.main])
        .pipe(sourceMaps.init())
        .pipe(concat('main.js'))
        .pipe(sourceMaps.write('maps'))
        .pipe(gulp.dest(buildFolder + '/js'));
});


// compile sass and use postcss
gulp.task('sass', function () {
    var processors = [
        autoprefixer({browsers: 'safari >= 6, ie >= 9'}),
        pxtorem({replace: false, rootValue: 14})
    ];

    return gulp.src(sassFiles)
        .pipe(glob())
        .pipe(sourceMaps.init())
        .pipe(sass({includePaths: sassPaths}))
        .pipe(sass().on('error', sass.logError))
        .pipe(postcss(processors))
        .pipe(sourceMaps.write('maps'))
        .pipe(gulp.dest(buildFolder + '/css'))
        .pipe(browserSync.stream({match: '**/*.css'}));
});

// enable browser-sync
gulp.task('browserSync', function () {
    browserSync.init({
        injectChanges: true,
        open: true,
        // uncomment 2 lines below if not using Docker Starter
        //port: 8080,
        //proxy: server
    });
});


// gulp copy files to build folder
gulp.task('build:copy', ['build:copyImages', 'build:copyFonts']);

gulp.task('build:copyImages', function () {
    return gulp.src(baseDir + '/images/**/*')
        .pipe(gulp.dest(buildFolder + '/images'));
});

gulp.task('build:copyFonts', function () {
    return gulp.src(baseDir + '/fonts/**/*')
        .pipe(gulp.dest(buildFolder + '/fonts'));
});


// perform all build tasks
gulp.task('build', ['sass', 'js', 'build:copy']);


// watch for changes
gulp.task('watch', ['browserSync', 'build'], function () {
    gulp.watch(sassFiles, ['sass']);
    gulp.watch(jsFolder + '/**/*.js', ['js-watch']);
    gulp.watch('**/*.php', browserSync.reload);
});

gulp.task('js-watch', ['js'], function () {
    browserSync.reload();
});


/*
 * Production build tasks
 */

// optimize images
gulp.task('images', function () {
    return gulp.src(baseDir + '/images/**/*.{png,jpg,gif,svg}')
        .pipe(cache(imagemin({
            svgoPlugins: [{removeViewBox: false}]
        })))
        .pipe(gulp.dest(distFolder + '/images'));
});

// clear the images cache
gulp.task('cache:clear', function (done) {
    return cache.clearAll(done);
});

// copy fonts to dist folder
gulp.task('fonts', function () {
    return gulp.src(baseDir + '/fonts/**/*')
        .pipe(gulp.dest(distFolder + '/fonts'));
});

// clean the dist folder
gulp.task('clean:dist', function () {
    return del.sync(distFolder);
});

// prepare css files for production environment
gulp.task('dist:css', ['sass'], function () {
    var processors = [
        mqpacker,
        cssnano({
            discardComments: {
                removeAll: true
            }
        })
    ];

    return gulp.src(buildFolder + '/css/*.css')
        .pipe(postcss(processors))
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(distFolder + '/css'));
});

// strip unused css selectors
gulp.task('dist:uncss', function () {
    var processors = [
        uncss({
            html: ['./**/*.php'],
            ignore: [/\.is-loading/, /\.loader/, /\.circular/, /\.loader-overlay/,
                /\.is-active/, /\.is-animating/, /\.is-exiting/, /\.hidden/, /\.open/, /\.active/,
                /\.-scrolled/, /\.is-open/, /\.collapse/, /\.collapsing/, /\.in/, /^\.remodal/,
                /\.is-checked/, /\.has-success/, /\.has-error/, /\.-white-bg/, /\.dropdownjs/,
                /\.fakeinput /, /\.focus/, /\.selected/, /\.is-focused/, /\.check/, /\.fakeinput/, /\.form-group/,
                /\.tooltip/, /\.fade/, /\.right/, /\.in/, /\.rich-autocomplete-list/, /\.rich-autocomplete-list-item/, /\.empty/,
                /\.highlighted/, /\.rich-autocomplete-list-item-empty/, /\.has-error/, /\.has-danger/, /\.snackbar/,
                /\.snackbar-content/, /\.shake-element/, /\.red-label/, /\.-overflow/, /\.-moving/, /\.is-dragging/, /\.pulse/
            ],
            ignoreSheets: [/fonts.googleapis/, /maxcdn.bootstrapcdn/]
        })
    ];

    return gulp.src(buildFolder + '/css/**/*.css')
        .pipe(plugins.postcss(processors))
        .pipe(gulp.dest(distFolder + '/css'));
});

// prepare js files for production environment
gulp.task('dist:js', ['js'], function () {
    return gulp.src([buildFolder + '/js/main.js'])
        .pipe(rename({suffix: '.min'}))
        .pipe(uglify({preserveComments: 'license'}))
        .pipe(gulp.dest(distFolder + '/js'));
});

// main production build task
gulp.task('dist', function (done) {
    runSequence(
        'clean:dist',
        //['dist:css', 'dist:uncss', 'dist:js', 'images', 'fonts'],
        ['dist:css', 'dist:js', 'images', 'fonts'],
        done
    );
});


// default task
gulp.task('default', ['watch']);