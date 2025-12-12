'use strict';

const gulp = require( 'gulp' );
const sass = require('gulp-sass')(require('sass'));
const browserSync = require('browser-sync').create();
const concat = require('gulp-concat');

var url = 'mv23.com';

gulp.task('buildersass', function() {
    const corePath = 'core';
    return gulp.src( './assets/sass/index.sass' )
        .pipe( concat( 'builder-admin.css') )
        .pipe( sass().on( 'error', sass.logError ) )
        .pipe( gulp.dest( './assets/css/' ) )
        .pipe(browserSync.stream());
});

gulp.task('builderserve', function () {
    var files = [];

    browserSync.init({
        proxy: url,
        injectChanges: true
    });

    gulp.watch(['./assets/sass/**/*.sass'], gulp.series('buildersass'));
});