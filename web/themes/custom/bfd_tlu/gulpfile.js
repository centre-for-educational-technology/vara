/**
 * @file
 * Provides Gulp configurations and tasks for Bootstrap for Drupal theme.
 */
'use strict';
const { src, watch, series, dest } = require('gulp')
const browserSync = require('browser-sync').create()
const sass = require('gulp-sass')(require('sass'))
const autoprefixer = require('gulp-autoprefixer')

let browserSyncInitialised = false

// Initialise browser-sync.
function browserSyncInit(cb) {
  browserSyncInitialised = true

  browserSync.init({
    proxy: process.env.npm_config_url || process.env.npm_package_config_url
  })
  cb()
}

// Reload browser-sync.
function browserSyncReload(cb) {
  browserSync.reload()
  cb()
}

// Watch files, run sass task and reload browser-sync.
function watchTask() {
  const tasks = [sassTask]

  if (browserSyncInitialised) {
    tasks.push(browserSyncReload)
  }

  watch('assets/scss/**/*.scss', series(...tasks))
}

// Compile SASS into CSS & auto-inject into browsers.
function sassTask() {
  const stream = src('assets/scss/style.scss')
    .pipe(sass())
    .pipe(autoprefixer())
    .pipe(dest('assets/css'))

    if (browserSyncInitialised) {
      stream
        .pipe(browserSync.stream())
    }

    return stream
}

exports.build = sassTask
exports.watch = series(sassTask, watchTask)
exports.serve = series(sassTask, browserSyncInit, watchTask)
exports.default = exports.serve
