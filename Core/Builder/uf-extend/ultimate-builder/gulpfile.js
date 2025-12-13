'use strict';

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

const gulp = require( 'gulp' );
const sass = require('gulp-sass')(require('sass'));
const browserSync = require('browser-sync').create();
const concat = require('gulp-concat');
const minifyCSS = require('gulp-clean-css');
const sassParms = {
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

gulp.task('buildersass', function() {
    return gulp.src( './assets/sass/builder-admin/_index.sass' )
        .pipe( concat( 'builder-admin.css') )
        .pipe(sass(sassParms).on('error', sass.logError))
        .pipe(minifyCSS())
        .pipe( gulp.dest( './assets/css/' ) )
        .pipe(browserSync.stream());
});

gulp.task('canvassass', function() {
    return gulp.src( './assets/sass/canvas/_index.sass' )
        .pipe( concat( 'canvas.css') )
        .pipe(sass(sassParms).on('error', sass.logError))
        .pipe(minifyCSS())
        .pipe( gulp.dest( './assets/css/' ) )
        .pipe(browserSync.stream());
});

gulp.task('builderserve', function () {
    browserSync.init({
        proxy: 'mv23.com',
        injectChanges: true
    });

    gulp.watch(['./assets/sass/**/*.sass'], gulp.series('buildersass', 'canvassass'));
});