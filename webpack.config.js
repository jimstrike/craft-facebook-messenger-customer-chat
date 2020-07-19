const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('src/assets/dist')
    
    // public path used by the web server to access the output path
    .setPublicPath('/cpresources/dist/fbmcc')
    
    // only needed for CDN's or sub-directory deploy
    .setManifestKeyPrefix('fbmcc')

    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
    .addEntry('main', './resources/js/main.js')

    // add css files
    //.addStyleEntry('css/main', './resources/scss/main.scss')

    // copy files
    .copyFiles({
        from: './resources/images',
        context: './resources',
        pattern: /\.(png|jpg|jpeg|svg)$/
    })

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    
    .enableBuildNotifications()

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()
    //.disableSingleRuntimeChunk()

    .cleanupOutputBeforeBuild()
    
    .enableSourceMaps(!Encore.isProduction())

    //.enableIntegrityHashes(Encore.isProduction())
    
    // enables hashed filenames (e.g. app.abc123.css)
    //.enableVersioning(Encore.isProduction())

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you use Vue.js
    .enableVueLoader(() => {}, { runtimeCompilerBuild: false })

    // uncomment if you use Sass/SCSS files
    .enableSassLoader()
    //.enableSassLoader(function(options) {
    //    options.includePaths = [];
    //})

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
