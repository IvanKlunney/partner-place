const gulp 	 = require('gulp'),
	  esli 	 = require('gulp-eslint'),
	  ugli 	 = require('gulp-uglify'),
	  pump 	 = require('pump'),
	  sass 	 = require('gulp-sass'),
	  min  	 = require('gulp-csso'),
	  dst  	 = '/var/www/project/build',
	  dstjs	 = `${dst}/js`,
	  dstcss = `${dst}/styles`;

gulp.task('lint', function(){
	return gulp.src('js/*.js')
			.pipe(esli({
				'rules': {
					'camelcase': 2,
					'comma-dangle': 2,
					'comma-spacing': 1,
					'brace-style': 2,
					'quotes': 0
				}
			}))
			.pipe(esli.format())
			.pipe(esli.result(function(result) {
        		console.log(`ESLint result: ${result.filePath}`);
        		console.log(`# Warnings: ${result.warningCount}`);
        		console.log(`# Errors: ${result.errorCount}`);
    }));
})

gulp.task('js', function(cb){
	pump([gulp.src(['js/*.js', 'third-party/materialize/js/materialize.js']), ugli(), gulp.dest(dstjs)], cb);
})

gulp.task('css', function(){
	return gulp.src(['styles/*.scss', 'third-party/materialize/sass/materialize.scss' , '!styles/templates.scss'])
			.pipe(sass())
			.pipe(min())
			.pipe(gulp.dest(dstcss))
})

gulp.task('copy', function(){
	return gulp.src(
		['!build/**/*.php', '!**/config/*.php', '**/*.php',
		 '!build/**/*.tpl', '**/*.tpl',
		 '!build/**/*.+(png|gif)','!node_modules/**/*.+(png|gif)', '**/*.+(png|gif)',
		 '!**/build/*.+(eot|woff|woff2|ttf)', 'third-party/*/*/*/*.+(eot|woff|woff2|ttf)',
		])
		.pipe(gulp.dest(dst));
})

gulp.task('default', ['css', 'js', 'lint', 'copy']);