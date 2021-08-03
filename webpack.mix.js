/*
 * Tarot Laravel Mix
 *
 * Check the documentation at
 * https://laravel.com/docs/5.6/mix
 */

const mix = require("laravel-mix");

// Production and Dev ENV configuration
if (!mix.inProduction()) {
    mix
        .webpackConfig({
            devtool: "source-map",
        })
        .sourceMaps();
}

// Settings
mix.options({
    processCssUrls: false,
    postCss: [require("postcss-flexbugs-fixes")()],
});

// Process Assets
// -> Common styles and scripts
mix.sass("src/scss/styles.scss", "dist/css")
    .sass("src/scss/admin.scss", "dist/css")
    .js(["src/js/admin.js"], "dist/js")
    .js(["src/js/scripts.js"], "dist/js");

// -> Gutenberg block styles and scripts
const blocks = ['block-name'];
blocks.forEach(function (block) {
	mix.react(`blocks/${block}/editor.js`, `dist/blocks/${block}`)
	mix.react(`blocks/${block}/view.js`, `dist/blocks/${block}`)
		.sass(`blocks/${block}/editor.scss`, `dist/blocks/${block}`)
		.sass(`blocks/${block}/view.scss`, `dist/blocks/${block}`);
});

mix.webpackConfig({
    output: {
        publicPath: "/wp-content/plugins/starter-npm/dist/",
    },
    externals: {
        react: "React",
        "react-dom": "ReactDOM",
    },
});
