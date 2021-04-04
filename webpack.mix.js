const mix = require('laravel-mix');


mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/watch.js', 'public/js/watch.js')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/watch.scss', 'public/css/watch.css')
    .sass('resources/sass/video.scss', 'public/css/video.css')





  mix.styles([
    'public/backend/css/bootstrap.min.css',
    'public/backend/css/dashboard.css',
    'public/backend/css/custom.css',
    'public/backend/css/slick.css',
  ],'public/backend/css/admin.css');




mix.scripts([
  'public/backend/js/bootstrap.min.js',
  'public/backend/js/material.min.js',
  'public/backend/js/jquery.validate.min.js',
  'public/backend/js/material-dashboard-v=1.3.0.js',
  'public/backend/js/jquery.bootstrap-wizard.js',
  'public/backend/js/scripts.js',
],
  'public/js/dashboard.js'
);
