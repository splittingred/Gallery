<script type="text/javascript">
if (!/android|iphone|ipod|series60|symbian|windows ce|blackberry/i.test(navigator.userAgent)) {
	jQuery(function($) {
	    var opts = [[+options]];
		$("a[rel^='lightbox']").slimbox(opts, function(el) {
			return [encodeURI(el.href), el.title];
		}, function(el) {
			return (this == el) || ((this.rel.length > 8) && (this.rel == el.rel));
		});
	});
}
</script>