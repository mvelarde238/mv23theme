/**
 *
 * Gulpfile setup
 *
 * @since 1.0.0
 * @authors @mvelarde
 */

// Project configuration
var project = 'mv23theme', // Nombre de proyecto, usado como nombre de archivo al momento de crear el zip
	url = 'mv23.com', // Local Development URL for BrowserSync. Default: './'
	build = '../', // Folder donde se guarda el zip 
	buildInclude = [
		// Archivos que se van a guardar en el zip
		'../**/*.php',
		'../**/*.html',
		'../**/*.css',
		'../**/*.scss',
		'../**/*.sass',
		'../**/*.js',
		'../**/*.json',
		'../**/*.svg',
		'../**/*.png',
		'../**/*.jpg',
		'../**/*.ico',
		'../**/*.po',
		'../**/*.mo',
		'../**/*.gif',
		'../**/*.ttf',
		'../**/*.otf',
		'../**/*.eot',
		'../**/*.woff',
		'../**/*.woff2',

		// include specific files and folders
		'../screenshot.png',

		// exclude files and folders
		'!node_modules/**/*'
	];

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
	// svgmin       = require('gulp-svgmin'),
	mergeQueries = require('gulp-merge-media-queries'),
	filelist = require('gulp-filelist'),
	//  zip          = require('gulp-zip'),
	//  runSequence  = require('run-sequence'),
	lel = null;

/*
* Tareas
*/
/*
* Lista todos los archivos js en un json
*/
var jsfiles = [
	"js/libs/materialize/*",
	"js/libs/*",
	'js/functions/*',
	'js/modules/*'
];

gulp.task('listjs', function () {
	return gulp.src(jsfiles, { nodir: true })
		.pipe(filelist('js_filelist.json'))
		.pipe(gulp.dest('./'));
});

/*
* Concatena los archivos js
* used by childthemes to override some file
*/
// const js_filelist = require('./js_filelist.json');
gulp.task('js', function () {
	return gulp.src(jsfiles)
		.pipe(concat('scripts.js'))
		.pipe(babel({ presets: ['@babel/preset-env'] }))
		// .pipe(uglifyJs())
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
	.pipe(sass({
        // includePaths: [parenttheme_path+'sass/'] this is for child themes
    }).on('error', sass.logError))
	.pipe(mergeQueries({ log: true }))
	// .pipe(minifyCSS())
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
    .pipe(sass({
        // includePaths: [parenttheme_path+'sass/'] this is for child themes
    }).on('error', sass.logError))
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



// WATCH
gulp.task('w', function () {
	gulp.watch('js/**/*.js', gulp.series('js'));
	gulp.watch(['sass/**/*.scss', 'sass/**/*.sass'], gulp.series('sass'));
});




/*
* Minimiza archivos svg
*/
gulp.task('svg', function () {
	return gulp.src('svg/*.svg')
		.pipe(svgmin())
		.pipe(gulp.dest('../assets/images/svg/'));
});



// **************************************************************************
// **************************************************************************
// ADMIN-SCRIPTS.JS
// **************************************************************************
// **************************************************************************
// var adminJSFiles = [
	// 'js/functions/helpers.js',
	// 'js/modulos/admin-custom-uploader.js',
	// 'js/modulos/repeater-fields.js',
	// 'js/modulos/datepicker_input.js',
	// 'js/admin-scripts.js'
// ];

var adminSASSFiles = [
	'sass/admin-styles.scss'
];

// gulp.task('adminjs', function () {
// 	return gulp.src(adminJSFiles)
// 		.pipe(concat('admin-scripts.js'))
// 		// .pipe(babel({ presets: ['env'] }))
// 		// .pipe(uglifyJs())
// 		.pipe(gulp.dest('../assets/js/'))
// 		.pipe(browserSync.stream());
// });

gulp.task('adminsass', function () {
	return gulp.src(adminSASSFiles)
		.pipe(sass().on('error', sass.logError))
		.pipe(minifyCSS())
		.pipe(gulp.dest('../assets/css/'))
		.pipe(browserSync.stream());
});

// gulp.task('adminwatch', function () {
// 	gulp.watch(adminJSFiles, gulp.series('adminjs'));
// 	gulp.watch(['sass/**/*.scss', 'sass/**/*.sass'], gulp.series('adminsass'));
// });

// gulp.task('adminserve', function () {
// 	var files = ['../**/*.php'];
// 	browserSync.init(files, {
// 		proxy: url,
// 		injectChanges: true
// 	});
// 	gulp.watch(adminJSFiles, gulp.series('adminjs'));
// 	gulp.watch(['sass/**/*.scss', 'sass/**/*.sass'], gulp.series('adminsass'));
// });


// **************************************************************************
// **************************************************************************
// TASKS TO ZIP
// **************************************************************************
// **************************************************************************
// create a folder with files
// gulp.task('buildFilesToZip', function () {
	// return gulp.src(buildInclude)
		// .pipe(gulp.dest(build))
	// .pipe(notify({ message: 'Copy from buildFilesToZip complete', onLast: true }));
// });

// zip files
// gulp.task('buildZip', function () {
	// return gulp.src(build + '/**/')
		// .pipe(zip(project + '.zip'))
		// .pipe(gulp.dest('../'))
	// .pipe(notify({ message: 'Zip task complete', onLast: true }));
// });

// search files and zip'em
// gulp.task('zip', function () {
	// return gulp.src(buildInclude)
		// .pipe(zip(project + '.zip'))
		// .pipe(gulp.dest('../'))
// });

// Build process: 
// compile sass and scss
// concat js files
// concat materializecss js files
// create zip
// gulp.task('build', function (cb) {
	// runSequence('sass', 'js', 'adminjs', 'editorsass', 'adminsass', 'zip', cb);
// });

