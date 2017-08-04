module.exports = config:
  npm:
    enabled: false
  files:
    javascripts: joinTo:
      'libraries.js': /^(?!app\/)/
      'app.js': /^app\//
    stylesheets: joinTo:
      'vendor.css': /^(bower_components|vendor)/
      'app.css': /^app\//
  modules:
    wrapper: false
    definition: false
  plugins:
    sass:
      allowCache: true
      options:
        includePaths: ['bower_components/foundation/scss']