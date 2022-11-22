// Use CommonJS instead of ES6 modules for theses packages due to async behavior that causes errors 
const hljs = require( 'highlight.js' );

window.hljs = hljs;

// require( 'highlightjs-line-numbers.js' );

hljs.highlightAll();
// hljs.initLineNumbersOnLoad();