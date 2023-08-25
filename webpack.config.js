const Encore = require('@symfony/webpack-encore');
const path = require('path');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.scripts file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or subdirectory deploy
    //.setManifestKeyPrefix('build/')
    /*
     * ENTRY CONFIG
     *
     * Each entry will result in one JavaScript file (e.g. app.scripts)
     * and one CSS file (e.g. app.scss) if your JavaScript imports CSS.
     */
    .addEntry('app', './assets/scripts/app.js')
    .addEntry('announcements-list/script', './assets/scripts/pages/announcement-list.js')
    .addEntry('announcements-favorite/script', './assets/scripts/pages/announcement-favorite.js')
    .addEntry('homepage/script', './assets/scripts/pages/homepage.js')
    .addEntry('user-account/script', './assets/scripts/pages/user-account.js')
    .addEntry('registration/script', './assets/scripts/pages/registration.js')
    .addEntry('login/script', './assets/scripts/pages/login.js')


    .addStyleEntry('announcements-list/style', './assets/styles/pages/announcements-list.scss')
    .addStyleEntry('announcements-favorite/style', './assets/styles/pages/announcements-favorite.scss')
    .addStyleEntry('homepage/style', './assets/styles/pages/homepage.scss')
    .addStyleEntry('user-account/style', './assets/styles/pages/user-account.scss')
    .addStyleEntry('registration/style', './assets/styles/pages/registration.scss')
    .addStyleEntry('login/style', './assets/styles/pages/login.scss')
    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()
    .addAliases({
        // SCRIPTS
        '@atoms-scripts': path.resolve(__dirname, 'assets/scripts/components/atoms'),
        '@forms-scripts': path.resolve(__dirname, 'assets/scripts/components/forms'),
        '@molecules-scripts': path.resolve(__dirname, 'assets/scripts/components/molecules'),
        '@sections-scripts': path.resolve(__dirname, 'assets/scripts/components/sections'),
        '@helpers-scripts': path.resolve(__dirname, 'assets/scripts/helpers'),
        '@layouts-scripts': path.resolve(__dirname, 'assets/scripts/layouts'),
        '@pages-scripts': path.resolve(__dirname, 'assets/scripts/pages'),

        // STYLES
        '@atoms-styles': path.resolve(__dirname, 'assets/styles/components/atoms'),
        '@forms-styles': path.resolve(__dirname, 'assets/styles/components/forms'),
        '@molecules-styles': path.resolve(__dirname, 'assets/styles/components/molecules'),
        '@sections-styles': path.resolve(__dirname, 'assets/styles/components/sections'),
        '@helpers-styles': path.resolve(__dirname, 'assets/styles/helpers'),
        '@layouts-styles': path.resolve(__dirname, 'assets/styles/layouts'),
        '@pages-styles': path.resolve(__dirname, 'assets/styles/pages'),
    })
    // will require an extra script tag for runtime.scripts
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // configure Babel
    // .configureBabel((config) => {
    //     config.plugins.push('@babel/a-babel-plugin');
    // })

    // enables and configure @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.23';
    })

    // enables Sass/SCSS support
    .enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you use React
    //.enableReactPreset()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes(Encore.isProduction())
    .addExternals('TomSelect', 'TomSelect')
    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
