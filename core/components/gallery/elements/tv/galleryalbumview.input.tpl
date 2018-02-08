<!-- core/components/gallery/elements/tv/galleryalbumview.input.tpl -->
<div id="tv{$tv->id}-form"></div>
<input id="tv{$tv->id}" type="hidden" name="tv{$tv->id}" value="{$tv->value}"/>
{if $tv->value ne ''}
{literal}
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
        MODx.load({{/literal}
        xtype: 'gal-panel-album-items'
        ,border: false
        ,autoHeight: true
        ,autoScroll: true
        ,forceLayout: true
        ,width: Ext.getCmp('modx-panel-resource').getWidth()-200
        ,album: '{$tv->value}'
        ,renderTo: 'tv{$tv->id}-form'
        ,tv: '{$tv->id}'
        ,tvValue: '{$tv->value}'

{literal}
        });
});     
// ]]>
</script>
{/literal}
{else}
<p>Пожалуйста, сохраните документ и обновите страницу перед созданием галереи </p>
{/if}