// const uglify = require('rollup-plugin-uglify').uglify;
// const css = require('rollup-plugin-css-porter');

module.exports = {
    input: 'src/app.js',
    output: 'dist/app.bundle.js',
    namespace: 'AgrebnevWikiInside',

    adjustConfigPhp: false,

    plugins: {
        resolve: true,

        custom: [
            // uglify(),
            // css(),
        ],
    }
};
