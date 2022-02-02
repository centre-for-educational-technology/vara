/**
 * @file
 * Provides Gulp configurations and tasks for Bootstrap for Drupal theme.
 */
'use strict';
const { src, watch, series, dest } = require('gulp');
const browserSync = require('browser-sync').create();
const sass = require('gulp-sass')(require('sass'));
const autoprefixer = require('gulp-autoprefixer');

// Initialise browser-sync
function browserSyncInit(cb) {
  browserSync.init({
    proxy: 'http://YOUR--DEV-URL.COM'
  })
  cb()
}

// Reload browser-sync
function browserSyncReload(cb) {
  browserSync.reload()
  cb()
}

// Watch files, run sass task and reload browser-sync
function watchTask() {
  watch('assets/scss/**/*.scss', series(sassTask, browserSyncReload))
}

// Compile SASS into CSS & auto-inject into browsers.
function sassTask() {
  return src('assets/scss/style.scss')
    .pipe(sass())
    .pipe(autoprefixer())
    .pipe(dest('assets/css'))
    .pipe(browserSync.stream())
}

exports.sass = sassTask
exports.serve = series(sassTask, browserSyncInit, watchTask)
exports.default = exports.serve
