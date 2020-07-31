const mix = require('laravel-mix');


mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');

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
