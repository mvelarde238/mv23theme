/**
 *
 * Gulpfile setup
 *
 * @since 1.0.0
 * @authors @mvelarde
 */

// Suprimir warnings específicos de Node.js
process.removeAllListeners('warning');
process.on('warning', (warning) => {
    // Ignorar warnings específicos
    if (warning.name === 'DeprecationWarning' && 
        (warning.code === 'DEP0180' || warning.message.includes('fs.Stats constructor'))) {
        return;
    }
    console.warn(warning.name + ': ' + warning.message);
});

/**
 * Project settings
 * @url Local development URL for BrowserSync
 * @builderBanner Banner for compiled files
 * @sassParms Parameters for Sass compilation
 */
const url = 'mv23.com', 
	builderBanner = '/*! Copyright (c) 2026 Miguel Velarde / velarde23.com — All Rights Reserved. Proprietary and Confidential. */\n',
	sassParms = {
	    quietDeps: true,
	    verbose: false,
	    logger: {
	        warn: function(message) {
	            // Silenciar warnings específicos
	            if (message.includes('fs.Stats constructor is deprecated') || 
	                message.includes('DEP0180')) {
	                return;
	            }
	            console.warn(message);
	        }
	    },
	    silenceDeprecations: ['legacy-js-api', 'import', 'global-builtin', 'color-functions']
	};

/*
* Dependencias
*/
var gulp = require('gulp'),
	sass = require('gulp-sass')(require('sass')),
	minifyCSS = require('gulp-clean-css'),
	concat = require('gulp-concat'),
	uglifyJs = require('gulp-uglify'),
	babel = require('gulp-babel'),
	browserSync = require('browser-sync'),
	mergeQueries = require('gulp-merge-media-queries'),
	header = require('gulp-header');

/*
* Tareas
*/
/*
* Concatena los archivos js
* used by childthemes to override some file
*/
var jsfiles = [
	"js/libs/materialize/*",
	"js/libs/*",
	'js/functions/*',
	'js/utils/*',
	'js/modules/*'
];
gulp.task('js', function () {
	return gulp.src(jsfiles)
		.pipe(concat('scripts.js'))
		.pipe(babel({ presets: ['@babel/preset-env'] }))
		.pipe(uglifyJs())
		.pipe(gulp.dest('../assets/js/'))
		.pipe(browserSync.stream());
});
/*
* Compila el archivo sass/style.scss en ../style.css
*/
var cssfiles = [
	'sass/style.scss'
];
gulp.task('sass', function () {
	return gulp.src(cssfiles)
	.pipe(concat('style.css'))
	.pipe(sass(sassParms).on('error', sass.logError))
	.pipe(mergeQueries({ log: true }))
	.pipe(minifyCSS())
	.pipe(gulp.dest('../assets/css/'))
	.pipe(browserSync.stream());
});

/*
* Compila el archivo sass/editor-style.scss
*/
var editorCssfiles = [
    'sass/editor-style.scss'
];
gulp.task('editorsass', function () {
    return gulp.src(editorCssfiles)
    .pipe(concat('editor-style.css'))
    .pipe(sass(sassParms).on('error', sass.logError))
    .pipe(minifyCSS())
    .pipe(gulp.dest('../assets/css/'))
    .pipe(browserSync.stream());
});

/*
* Browsersync for Local Development
*/
// Static Server + watching scss/html files
gulp.task('serve', function () {

	var files = [
		'../**/*.php'
	];

	browserSync.init(files, {
		// Read here http://www.browsersync.io/docs/options/
		proxy: url,
		injectChanges: true
	});

	// gulp.watch('js/**/*.js', ['js', 'adminjs']);
	gulp.watch('js/**/*.js', gulp.series('js'));
	gulp.watch(['sass/**/*.scss', 'sass/**/*.sass'], gulp.series('sass'));
});

// **************************************************************************
// **************************************************************************
// ADMIN TASKS
// **************************************************************************
// **************************************************************************
var adminJSFiles = [
	'js/functions/helpers.js',
	'js/modules/stickyHeader.js',
	'js/libs/gridstack.all.js'
];

var adminSASSFiles = [
	'sass/admin-styles.scss'
];

gulp.task('adminjs', function () {
	return gulp.src(adminJSFiles)
		.pipe(concat('admin-scripts.js'))
		.pipe(babel({ presets: ['@babel/preset-env'] }))
		.pipe(uglifyJs())
		.pipe(gulp.dest('../assets/js/'))
		.pipe(browserSync.stream());
});

gulp.task('adminsass', function () {
	return gulp.src(adminSASSFiles)
		.pipe(sass(sassParms).on('error', sass.logError))
		.pipe(minifyCSS())
		.pipe(gulp.dest('../assets/css/'))
		.pipe(browserSync.stream());
});

// **************************************************************************
// **************************************************************************
// BUILDER TASKS
// **************************************************************************
// **************************************************************************

var builderJsfiles = [
	"js/builder/utils/*",
	"js/builder/plugins/*",
	"js/builder/components/*",
	"js/builder/builder.js"
];
gulp.task('builderjs', function () {
	return gulp.src(builderJsfiles)
		.pipe(concat('builder.js'))
		.pipe(babel({ presets: ['@babel/preset-env'] }))
		.pipe(uglifyJs())
		.pipe(header(builderBanner))
		.pipe(gulp.dest('../assets/js/'))
		.pipe(browserSync.stream());
});

gulp.task('buildersass', function() {
    return gulp.src( 'sass/builder-styles.sass' )
        .pipe( concat( 'builder-styles.css') )
        .pipe(sass(sassParms).on('error', sass.logError))
        .pipe(minifyCSS())
        .pipe( gulp.dest( '../assets/css/' ) )
        .pipe(browserSync.stream());
});

gulp.task('canvassass', function() {
    return gulp.src( 'sass/canvas-styles.sass' )
        .pipe( concat( 'canvas-styles.css') )
        .pipe(sass(sassParms).on('error', sass.logError))
        .pipe(minifyCSS())
        .pipe( gulp.dest( '../assets/css/' ) )
        .pipe(browserSync.stream());
});

gulp.task('builderserve', function () {
    browserSync.init({
        proxy: url,
        injectChanges: true
    });

	gulp.watch(['sass/builder/**/*.sass','sass/canvas/**/*.sass'], gulp.series('buildersass','canvassass'));
});