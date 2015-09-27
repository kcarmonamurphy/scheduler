var gulp = require('gulp');
var less = require('gulp-less');
var path = require('path');

gulp.task('default', function() {
  // place code for your default task here
	console.log('Building...');
});

gulp.task('less', function () {
  return gulp.src('./assets/less/*.less')
    .pipe(less({
      paths: [ path.join(__dirname, 'less', 'includes') ]
    }))
    .pipe(gulp.dest('./assets/css'));
});
 