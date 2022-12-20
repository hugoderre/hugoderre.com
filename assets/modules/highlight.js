// Use CommonJS instead of ES6 modules for theses packages due to async behavior that causes errors 
const hljs = require( 'highlight.js/lib/core' );

hljs.registerLanguage( 'xml', require( 'highlight.js/lib/languages/xml' ) );
hljs.registerLanguage( 'css', require( 'highlight.js/lib/languages/css' ) );
hljs.registerLanguage( 'scss', require( 'highlight.js/lib/languages/scss' ) );
hljs.registerLanguage( 'javascript', require( 'highlight.js/lib/languages/javascript' ) );
hljs.registerLanguage( 'json', require( 'highlight.js/lib/languages/json' ) );
hljs.registerLanguage( 'php', require( 'highlight.js/lib/languages/php' ) );
hljs.registerLanguage( 'shell', require( 'highlight.js/lib/languages/shell' ) );
hljs.registerLanguage( 'yaml', require( 'highlight.js/lib/languages/yaml' ) );
hljs.registerLanguage( 'bash', require( 'highlight.js/lib/languages/bash' ) );
hljs.registerLanguage( 'sql', require( 'highlight.js/lib/languages/sql' ) );

window.hljs = hljs;

require( 'highlightjs-line-numbers.js' );
const CopyButtonPlugin = require( './highlightjs-copy' );

hljs.addPlugin( new CopyButtonPlugin( {
	lang: "fr",
} ) );
hljs.highlightAll();
hljs.initLineNumbersOnLoad();