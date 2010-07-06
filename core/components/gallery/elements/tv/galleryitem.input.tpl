<div id="tv{$tv->id}-form"></div>
<div id="tv{$tv->id}-preview">
{if $item}
<div class="gal-tv-preview" style="margin-top: 5px;">
    <img src="{$connectors_url}system/phpthumb.php?h=200&src={$item->url}" alt="{$item->name}" style="float: left; margin-right: 10px;" />
    <span style="font-weight: bold;">{$item->name}</span><br />
    <p>{$item->description}</p>
</div>
{/if}
</div>

{literal}
<script type="text/javascript">
// <![CDATA[
MODx.load({
    xtype: 'gal-panel-tv'
    ,tv: '{/literal}{$tv->id}{literal}'
    ,tvValue: '{/literal}{$tv->value}{literal}'
});
// ]]>
</script>
{/literal}