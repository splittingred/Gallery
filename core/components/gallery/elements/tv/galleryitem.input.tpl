<div id="tv{$tv->id}-form"></div>
<input type="hidden" id="tv{$tv->id}" name="tv{$tv->id}" value="{$itemjson|escape}" />

{literal}
<script type="text/javascript">
// <![CDATA[
MODx.load({{/literal}
    xtype: 'gal-panel-tv'
    ,tv: '{$tv->id}'
    ,tvValue: '{$tv->value}'
    {if $itemjson},data: {$itemjson}{/if}
{literal}});
// ]]>
</script>
{/literal}