module.exports = function( grunt ) {
	'use strict';

	require( 'load-grunt-tasks' )( grunt );
	var autoprefixer = require( 'autoprefixer' );

	grunt.initConfig( {
		pkg: grunt.file.readJSON( 'package.json' ),

		makepot: {
			options: {
				exclude: ['node_modules/.*'],
				domainPath: '/languages',
				type: 'wp-theme',
				processPot: function( pot, options ) {
					pot.headers['report-msgid-bugs-to'] = 'https://feastdesignco.com/';
					pot.headers['plural-forms'] = 'nplurals=2; plural=n != 1;';
					pot.headers['last-translator'] = 'Shay Bocks (https://feastdesignco.com)\n';
					pot.headers['language-team'] = 'Shay Bocks (https://feastdesignco.com)\n';
					pot.headers['x-poedit-basepath'] = '.\n';
					pot.headers['x-poedit-language'] = 'English\n';
					pot.headers['x-poedit-country'] = 'UNITED STATES\n';
					pot.headers['x-poedit-sourcecharset'] = 'utf-8\n';
					pot.headers['x-poedit-keywordslist'] = '__;_e;__ngettext:1,2;_n:1,2;__ngettext_  noop:1,2;_n_noop:1,2;_c,_nc:4c,1,2;_x:1,2c;_ex:1,2c;_nx:4c,1,2;_nx_noop:4c,1,2;\n';
					pot.headers['x-textdomain-support'] = 'yes\n';
					return pot;
				}
			},
			files: {
				src: [ '**/*.php' ],
			}
		},

		addtextdomain: {
			options: {
				textdomain: 'cookd',
				updateDomains: ['all']
			},
			files: {
				src: [ '**/*.php', '!node_modules/**/*.php' ],
			}
		},

		version: {
			project: {
				src: [
					'package.json'
				]
			},
			functions: {
				options: {
					prefix: 'THEME_VERSION\'\,\\s+\''
				},
				src: [
					'functions.php'
				]
			},
			style: {
				options: {
					prefix: '\\s+\\*\\s+Version:\\s+'
				},
				src: [
					'style.css'
				]
			}
		},

		wpcss: {
			style: {
				options: {
					commentSpacing: true,
					config: 'alphabetical'
				},
				src: 'style.css',
				dest: 'style.css'
			}
		},

		postcss: {
			options: {
				processors: [
					autoprefixer( {
						browsers: [
							'Android >= 2.1',
							'Chrome >= 21',
							'Explorer >= 8',
							'Firefox >= 17',
							'Opera >= 12.1',
							'Safari >= 6.0'
						]
					} )
				]
			},
			style: {
				src: 'style.css',
				dest: 'style.css'
			}
		},

		watch: {
			scripts: {
				files: [ '**/*.php' ],
				tasks: 'makepot',
				options: {
					spawn: false,
				}
			}
		}

	});

	grunt.registerTask('default', ['watch']);
	grunt.registerTask('build', [
		'addtextdomain',
		'makepot',
		'postcss',
		'wpcss'
	]);

};
