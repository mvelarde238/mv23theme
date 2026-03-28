'use strict';

var url = 'http://mv23.com/columns-layout/wp-admin/';
const gulp = require( 'gulp' );
const sass = require('gulp-sass')(require('sass'));
const browserSync = require('browser-sync');
const minifyCSS = require('gulp-clean-css');
// const sourcemaps = require( 'gulp-sourcemaps' );

gulp.task('columns-layout-sass', function() {
    return gulp.src( './assets/sass/*.scss' )
        // .pipe( sourcemaps.init() )
        .pipe( sass().on( 'error', sass.logError ) )
        // .pipe( sourcemaps.write( '.' ) )
        .pipe(minifyCSS())
        .pipe( gulp.dest( './assets' ) )
        .pipe(browserSync.stream());
});

gulp.task('serve', function () {
    var files = [];

    browserSync.init(files, {
        proxy: url,
        injectChanges: true
    });

    gulp.watch(['./assets/sass/**/*.scss'], gulp.series('columns-layout-sass'));
});