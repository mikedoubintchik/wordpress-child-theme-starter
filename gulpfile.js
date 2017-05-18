var gulp = require('gulp');

// incude the plugins
var sass = require('gulp-sass');
var postcss = require('gulp-postcss');
var autoprefixer = require('autoprefixer');
var pxtorem = require('postcss-pxtorem');
var mqpacker = require('css-mqpacker');
var cssnano = require('cssnano');
var eslint = require('gulp-eslint');
var sourceMaps = require('gulp-sourcemaps');
var browserSync = require('browser-sync').create();
var uglify = require('gulp-uglify');
var concat = require('gulp-concat');
var rename = require('gulp-rename');
var htmlReplace = require('gulp-html-replace');
var imagemin = require('gulp-imagemin');
var cache = require('gulp-cache');
var del = require('del');
var runSequence = require('run-sequence');
var glob = require('gulp-sass-glob');
var notify = require("gulp-notify");

// Define server
var server = 'example.dev';

// declare file and folder paths
var baseDir = 'src';
var jsFolder = baseDir + '/js';
var cssFolder = baseDir + '/styles';

var jsFiles = {
    main: jsFolder + '/main.js'
};

var sassFiles = cssFolder + '/**/*.scss';

var buildFolder = 'build';
var distFolder = 'dist';


// Lint JS files
gulp.task('eslint', function () {
    return gulp.src([jsFiles.main])
        .pipe(eslint())
        .pipe(eslint.format())
        .pipe(eslint.failAfterError())
        .on('error', notify.onError({ message: 'JS hint fail'}));
});

/*
 * Concatenate jsFiles.vendor and jsFiles.source into one JS file,
 * run eslint before concatenating.
 */
gulp.task('js', ['eslint'], function () {
    return gulp.src(jsFiles.main)
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
        port: 8080,
        proxy: server
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
        ['dist:css', 'dist:js', 'images', 'fonts'],
        done
    );
});


// default task
gulp.task('default', ['watch']);