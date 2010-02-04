<script type="text/javascript">
// <![CDATA[
jQuery(document).ready(function($) {
    var opts = [[+options]];
    
    $('div.galleriffic div.navigation').css({ 'width' : opts.navigationWidth, 'float' : 'left' });
    $('div.galleriffic div.gal_main').css('display', 'block');
    
    var onMouseOutOpacity = opts.onMouseOutOpacity;
    $(opts.thumbsContainerSel+' ul.thumbs li').opacityrollover({
        mouseOutOpacity:   onMouseOutOpacity
        ,mouseOverOpacity:  1.0
        ,fadeSpeed:         'fast'
        ,exemptionSelector: '.selected'
    });

    opts.onSlideChange = function(prevIndex, nextIndex) {
        this.find('ul.thumbs').children()
            .eq(prevIndex).fadeTo('fast', onMouseOutOpacity).end()
            .eq(nextIndex).fadeTo('fast', 1.0);
    };
    opts.onPageTransitionOut = function(callback) { this.fadeTo('fast', 0.0, callback); };
    opts.onPageTransitionIn = function() { this.fadeTo('fast', 1.0); };
    var gallery = $(opts.thumbsContainerSel).galleriffic(opts);
});
// ]]>
</script>