// Use CommonJS instead of ES6 modules for theses packages due to async behavior that causes errors 
const hljs = require( 'highlight.js/lib/core' );

hljs.registerLanguage( 'xml', require( 'highlight.js/lib/languages/xml' ) );
hljs.registerLanguage( 'css', require( 'highlight.js/lib/languages/css' ) );
hljs.registerLanguage( 'javascript', require( 'highlight.js/lib/languages/javascript' ) );
hljs.registerLanguage( 'php', require( 'highlight.js/lib/languages/php' ) );

window.hljs = hljs;

require( 'highlightjs-line-numbers.js' );

hljs.highlightAll();
hljs.initLineNumbersOnLoad();