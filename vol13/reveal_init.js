// Full list of configuration options available here:
// https://github.com/hakimel/reveal.js#configuration
Reveal.initialize({
	controls: true,
	progress: true,
	history: true,
	center: true,
	theme: Reveal.getQueryHash().theme, // available themes are in /css/theme
	transition: Reveal.getQueryHash().transition || 'default', // default/cube/page/concave/zoom/linear/none
	// Optional libraries used to extend on reveal.js
	dependencies: [
		{ src: 'plugin/highlight/highlight.js', async: true, callback: function() { hljs.initHighlightingOnLoad(); } },
		{ src: 'plugin/notes/notes.js', async: true, condition: function() { return !!document.body.classList; } }
	]
});
