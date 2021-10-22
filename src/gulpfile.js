/**
 *
 * Gulpfile setup
 *
 * @since 1.0.0
 * @authors @mvelarde
 */

// Project configuration
var project       = 'mv23theme', // Nombre de proyecto, usado como nombre de archivo al momento de crear el zip
    url           = 'mv23.com/2021/pruebas', // Local Development URL for BrowserSync. Default: './'
    nodeModules_path = '../../../../../../../node_modules/',
    build         = '../', // Folder donde se guarda el zip 
    buildInclude  = [
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
var gulp         = require(nodeModules_path+'gulp'),
    sass         = require(nodeModules_path+'gulp-sass'),
    minifyCSS    = require(nodeModules_path+'gulp-clean-css'),
    concat       = require(nodeModules_path+'gulp-concat'),
    uglifyJs     = require(nodeModules_path+'gulp-uglify'),
    babel        = require(nodeModules_path+'gulp-babel'),
    browserSync  = require(nodeModules_path+'browser-sync'),
    svgmin       = require(nodeModules_path+'gulp-svgmin'),
    mergeQueries = require(nodeModules_path+'gulp-merge-media-queries'),
    filelist     = require(nodeModules_path+'gulp-filelist'),
   //  zip          = require(nodeModules_path+'gulp-zip'),
   //  runSequence  = require(nodeModules_path+'run-sequence'),
    lel = null;

/*
* Tareas
*/
/*
* Lista todos los archivos js en un json
*/
var jsfiles = [
    "js/libs/compile/*",
    // "js/libs/materialize/initial.js",
    "js/libs/materialize/jquery.easing.1.4.js",
    // "js/libs/materialize/animation.js",
    "js/libs/materialize/velocity.min.js",
    "js/libs/materialize/hammer.min.js",
    "js/libs/materialize/jquery.hammer.js",
    "js/libs/materialize/global.js",
    // "js/libs/materialize/collapsible.js",
    // "js/libs/materialize/dropdown.js",
    "js/libs/materialize/modal.js",
    // "js/libs/materialize/materialbox.js",
    // "js/libs/materialize/parallax.js",
    // "js/libs/materialize/tabs.js",
    // "js/libs/materialize/tooltip.js",
    // "js/libs/materialize/waves.js",
    // "js/libs/materialize/toasts.js",
    "js/libs/materialize/sideNav.js",
    "js/libs/materialize/scrollspy.js",
    "js/libs/materialize/forms.js",
    // "js/libs/materialize/slider.js",
    // "js/libs/materialize/cards.js",
    // "js/libs/materialize/chips.js",
    "js/libs/materialize/pushpin.js",
    // "js/libs/materialize/buttons.js",
    // "js/libs/materialize/transitions.js",
    // "js/libs/materialize/scrollFire.js",
    // "js/libs/materialize/date_picker/picker.js",
    // "js/libs/materialize/date_picker/picker.date.js",
    // "js/libs/materialize/date_picker/picker.time.js",
    // "js/libs/materialize/character_counter.js",
    // "js/libs/materialize/carousel.js",
    // "js/libs/materialize/tapTarget.js",
    "js/libs/velocity-ui.js",
    "js/libs/additional-libs.js",
    'js/functions/compile/*',
    'js/modulos/globals.js',
    'js/ultimate-fields/compile/*',
    'js/modulos/compile/*',
    'js/scripts.js'
];

gulp.task('listjs', function(){
    return gulp.src(jsfiles)
    .pipe(filelist('filelist.json'))
    .pipe(gulp.dest('./'));
});


/*
* Concatena los archivos js de Materialize
*/
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
gulp.task('sass', function () {
    return gulp.src([
      'sass/theme-header.css',
      'sass/style.scss',
    ])
    .pipe(concat('style.css'))
    .pipe(sass().on('error', sass.logError))
    .pipe(mergeQueries({log: true}))
    // .pipe(minifyCSS())
    .pipe(gulp.dest('../'))
    .pipe(browserSync.stream());
});









/*
* Browsersync for Local Development
*/
// Static Server + watching scss/html files
gulp.task('serve', function() {

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
  gulp.watch(['sass/**/*.scss','sass/**/*.sass'], gulp.series('sass'));
});



// WATCH
gulp.task('w', function() {
  gulp.watch('js/**/*.js', gulp.series('js'));
  gulp.watch(['sass/**/*.scss','sass/**/*.sass'], gulp.series('sass'));
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
var adminJSFiles = [
  'js/modulos/helpers.js',
  'js/modulos/admin-custom-uploader.js',
  'js/modulos/repeater-fields.js',
  // 'js/modulos/datepicker_input.js',
  'js/admin-scripts.js'
];

var adminSASSFiles = [
  'sass/admin-styles.scss'
];

gulp.task('adminjs', function () {
    return gulp.src(adminJSFiles)
    .pipe(concat('admin-scripts.js'))
    // .pipe(babel({ presets: ['env'] }))
    // .pipe(uglifyJs())
    .pipe(gulp.dest('../assets/js/'))
    .pipe(browserSync.stream());
});

gulp.task('adminsass', function () {
    return gulp.src('sass/admin-styles.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(minifyCSS())
    .pipe(gulp.dest('../assets/css/'))
    .pipe(browserSync.stream());
});

gulp.task('adminwatch', function () {
  gulp.watch(adminJSFiles, gulp.series('adminjs'));
  gulp.watch(['sass/**/*.scss','sass/**/*.sass'], gulp.series('adminsass'));
});

gulp.task('adminserve', function() {
  var files = ['../**/*.php'];
  browserSync.init(files, {
    proxy: url,
    injectChanges: true
  });
  gulp.watch(adminJSFiles, gulp.series('adminjs'));
  gulp.watch(['sass/**/*.scss','sass/**/*.sass'], gulp.series('adminsass'));
});




gulp.task('editorsass', function () {
    return gulp.src('sass/editor-style.scss')
    .pipe(sass().on('error', sass.logError))
    // .pipe(minifyCSS())
    .pipe(gulp.dest('../assets/css/'))
    .pipe(browserSync.stream());
});




// **************************************************************************
// **************************************************************************
// TASKS TO ZIP
// **************************************************************************
// **************************************************************************
 // create a folder with files
 gulp.task('buildFilesToZip', function() {
  return gulp.src(buildInclude)
  .pipe(gulp.dest(build))
  // .pipe(notify({ message: 'Copy from buildFilesToZip complete', onLast: true }));
});

// zip files
gulp.task('buildZip', function () {
  return gulp.src(build+'/**/')
    .pipe(zip(project+'.zip'))
    .pipe(gulp.dest('../'))
    // .pipe(notify({ message: 'Zip task complete', onLast: true }));
});

// search files and zip'em
gulp.task('zip', function() {
  return gulp.src(buildInclude)
    .pipe(zip(project+'.zip'))
    .pipe(gulp.dest('../'))
});

// Build process: 
// compile sass and scss
// concat js files
// concat materializecss js files
// create zip
gulp.task('build', function(cb) {
  runSequence('sass','js','adminjs','editorsass','adminsass','zip', cb);
});

