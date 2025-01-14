// const uglify = require('rollup-plugin-uglify').uglify;
// const css = require('rollup-plugin-css-porter');

module.exports = {
    input: './src/component.js',
    output: './dist/component.js',
    namespace: 'AgrebnevWikiInside.Components',

    adjustConfigPhp: false,

    plugins: {
        resolve: true,

        custom: [
            // uglify(),
            // css(),
        ],
    }
};
