module.exports = function(grunt) {

    //configure tasks
    grunt.initConfig ({
        pkg: grunt.file.readJSON('package.json'),

        // Project configuration.
        uglify: {
            my_target: {
                files: {
                    'js/source.min.js': ['js/core/**/*.js']
                }
            }
        },

        cssmin: {
            target: {
                files: [{
                    expand: true,
                    cwd: 'styles/',
                    src: ['*.css', '!*.min.css'],
                    dest: '.',
                    ext: '.css'
                }]
            }
        },

        watch: {
            scripts: {
                files: ['**/*.scss','js/**/*.js'],
                tasks: ['compile'],
                options: {
                    spawn: false,
                },
            },
        },

        sass: {                              // Task
            dist: {                            // Target
                options: {                       // Target options
                    style: 'expanded'
                },
                files: {                         // Dictionary of files
                    'styles/style.css': 'styles/main.scss'  // 'destination': 'source'
                }
            }
        },

        autoprefixer: {
          options: {
            browsers: ['last 8 versions']
          },
          dist: { // Target
            files: {
              'style-release.css': 'style.css'
            }
          }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-autoprefixer');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.registerTask('compile',['uglify','sass','autoprefixer','cssmin','watch']);

}
