var jsfile = [
	'vendor/components/jquery/jquery.js',
	'vendor/twbs/bootstrap-sass/assets/javascripts/bootstrap.min.js' ,
	'vendor/components/jQote2/jquery.jqote2.js',
	'vendor/makeusabrew/bootbox/bootbox.js',
	'vendor/moxiecode/plupload/js/plupload.full.min.js',
	'vendor/components/toastr/toastr.js',
	'ui/_js/plugins/jquery.getData.js',
	'ui/_js/plugins/jquery.ba-dotimeout.min.js',
	'ui/_js/plugins/jquery.ba-bbq.js',
	'ui/_js/_.js'
];


module.exports = function (grunt) {
	require('time-grunt')(grunt);
	require('jit-grunt')(grunt, {
		sass: 'grunt-sass'
	});
	
	
	grunt.initConfig({
		
		concat: {
			js: {
				options: {
					separator: ';',
					stripBanners: true,
					sourceMap :true,
					sourceMapName : 'ui/javascript.js.map'
				},
				src: jsfile,
				dest: 'ui/javascript.js',
				nonull: true
			},
			js_quick: {
				options: {
					separator: ';',
					stripBanners: true
				},
				src: jsfile,
				dest: 'ui/javascript.js',
				nonull: true
			}
		},
		clean: {
			map: ["ui/**/*.map"],
		},
		
		uglify: {
			js: {
				
				files: {
					'ui/javascript.js': 'ui/javascript.js',
				}
			}
		},
		sass: {
			options: {
				sourceMap: true
			},
			style: {
				files: {
					"ui/style.css": "ui/_sass/base.scss"
				}
			}
		},
		cssmin: {
			options: {
				report: "min",
				keepSpecialComments: 0,
				shorthandCompacting: true
			},
			target: {
				files: {
					'ui/style.css': 'ui/style.css'
				}
			}
		},
		postcss: {
			options: {
				map: true,
				processors: [
					require('autoprefixer')({
						browsers: ['last 2 versions']
					})
				]
			},
			build: {
				src: 'ui/**/*.css'
				
			}
		},
		watch: {
			js: {
				files: ['js/*.js'],
				tasks: ['concat:js'],
				options: {
					spawn: false,
					livereload: true
				}
			},
			css: {
				files: ['sass/*.scss'],
				tasks: ['sass:style'],
				options: {
					spawn: false,
					livereload: true
				}
			}
		}
		
	});
	
	
	
	
	
	
	grunt.registerTask('jsmin', ['uglify:js']);
	grunt.registerTask('js', ['concat:js_quick','clean:map']);
	grunt.registerTask('jsmap', ['concat:js']);
	grunt.registerTask('css', ['sass:style']);
	grunt.registerTask('autoprefixer', ['postcss:build']);
	grunt.registerTask('build', ['concat:js','sass:style', 'uglify:js','postcss:build','cssmin','clean:map']);
	grunt.registerTask('default', ['watch']);

};