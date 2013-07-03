module.exports = function (grunt) {
  "use strict";

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

// CLEAN ///////////////////////////////////////////////////////////////////////
    clean: {
      prod: [ 'release/**' ]
    },

// JSHINT //////////////////////////////////////////////////////////////////////
    jshint: {
      jshintrc: '.jshintrc',
      gruntfile: 'Gruntfile.js',
      main:   'plugin/assets/js/script.js'
    },

// COPY ////////////////////////////////////////////////////////////////////////
    copy: {
      release: {
        files: [
          {
            expand: true,
            cwd: 'plugin/',
            src: '**',
            dest: 'release/trunk/'
          },
          {
            expand: true,
            src: 'assets/**',
            dest: 'release/'
          }
        ]
      }
    },

// UGLIFY //////////////////////////////////////////////////////////////////////
    uglify: {
      options: {
        banner: '/*! <%= pkg.name %> compiled <%= grunt.template.today("yyyy-mm-dd") %> */\n'
      },
      main: {
        files: {
          'release/trunk/assets/js/script.js' : '<%= jshint.main %>'
        }
      }
    },

// WATCH ///////////////////////////////////////////////////////////////////////
    watch: {
      gruntfile: {
        files: [ 'Gruntfile.js' ],
        tasks: [ 'jshint:gruntfile' ]
      },
      js: {
        files: [ '<%= jshint.main %>' ],
        tasks: [ 'jshint:main' ]
      }
    }
  });

// LOAD TASKS //////////////////////////////////////////////////////////////////
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-watch');

// REGISTER TASKS //////////////////////////////////////////////////////////////
  grunt.registerTask('test', [
    'jshint:gruntfile',
    'jshint:main'
  ]);

  grunt.registerTask('release', [
    'test',
    'clean',
    'copy',
    'uglify'
  ]);

  grunt.registerTask('default', [
    'test',
    'watch'
  ]);
};
