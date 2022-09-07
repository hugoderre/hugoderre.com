import hljs from 'highlight.js';

document.querySelectorAll( "code" ).forEach( function ( element ) {
	element.innerHTML = element.innerHTML.replace( /&/g, "&amp;" ).replace( /</g, "&lt;" ).replace( />/g, "&gt;" ).replace( /"/g, "&quot;" ).replace( /'/g, "&#039;" );
} );
hljs.highlightAll();