<li>
    <a class="thumb" name="[[+name]]" href="[[+image]]" title="[[+name]]">
        <img src="[[+thumbnail]]" alt="[[+name]]" />
    </a>
    <div class="caption">
        <div class="download">
            <a href="[[+image]]">[[%gallery.download_original? &namespace=`gallery` &topic=`galleriffic`]]</a>
        </div>
        <div class="image-title">[[+name]]</div>
        <div class="image-desc">
            [[+description]]
            [[+tags:notempty=`<br /><em>[[%gallery.tags]]:</em> [[+tags]]`]]
        </div>
    </div>
</li>