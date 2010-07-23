<div id="tv{$tv->id}-form"></div>
<input type="hidden" id="tv{$tv->id}" name="tv{$tv->id}" value="{if $itemjson}{$itemjson|escape}{else}{literal}{}{/literal}{/if}" />

{literal}
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
    MODx.load({{/literal}
        xtype: 'gal-panel-tv'
        ,tv: '{$tv->id}'
        ,tvValue: '{$tv->value}'
        {if $itemjson},data: {$itemjson}{/if}
    {literal}});
});
// ]]>
</script>
{/literal}