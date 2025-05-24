'use strict';

const gulp = require( 'gulp' );
const sass = require('gulp-sass')(require('sass'));
const browserSync = require('browser-sync');
const sourcemaps = require( 'gulp-sourcemaps' );

// Compilable directories
// const dirs = [
// 	'core',
// 	'ui',
// ];

// Prepares the GULP task for a stylesheet
// function prepareStylesheet( path ) {
// 	return gulp.src( './' + path + '/assets/sass/*.scss' )
// 		.pipe( sourcemaps.init() )
// 		.pipe( sass().on( 'error', sass.logError ) )
// 		.pipe( sourcemaps.write( '.' ) )
// 		.pipe( gulp.dest( './' + path + '/assets/css' ) );
// }

// // Create a task for every stylesheet
// dirs.forEach(function( path ) {
// 	gulp.task( path, () => prepareStylesheet( path ) );
// });

// Create a default task, which depends on the rest
// gulp.task( 'default', dirs, function () {
// 	// Nothing to do here, everything is in the dependency
// 	// Available as "npm run build"
// });

// Monitors
// gulp.task( 'watch', function () {
// 	// Available as "npm run watch"
// 	dirs.forEach( path => {
// 		gulp.watch( './' + path + '/assets/sass/*.scss', [ path ] );
// 		gulp.watch( './' + path + '/assets/sass/**/*.scss', [ path ] );
// 	});
// });

// core mv23
var url = 'mv23.com';

gulp.task('ufcoresass', function() {
	const path = 'core';
	return gulp.src( './' + path + '/assets/sass/*.scss' )
		.pipe( sourcemaps.init() )
		.pipe( sass().on( 'error', sass.logError ) )
		.pipe( sourcemaps.write( '.' ) )
		.pipe( gulp.dest( './' + path + '/assets/css' ) )
        .pipe(browserSync.stream());
});

gulp.task('ufcoreserve', function () {
	var files = [];

	browserSync.init(files, {
		proxy: url,
		injectChanges: true
	});

	gulp.watch(['./core/assets/sass/**/*.scss'], gulp.series('ufcoresass'));
});