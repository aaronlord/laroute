module.exports = function (grunt) {
    grunt.initConfig({
        pkg : grunt.file.readJSON('package.json'),

        uglify : {
            options : {
                banner : '/*! Generaged by <%= pkg.name %> { <%= pkg.url %> } */\n'
            },
            build : {
                files : {
                    'src/Lord/Laroute/templates/laroute.min.js' : 'src/Lord/Laroute/templates/laroute.js'
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.registerTask('default', ['uglify']);
};
