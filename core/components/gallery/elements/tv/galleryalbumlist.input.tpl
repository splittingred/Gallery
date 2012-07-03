<input id="tv{$tv->id}" type="text" />

{literal}
<script type="text/javascript">
// <![CDATA[
Ext.onReady(function() {
    var galStore{/literal}{$tv->id}{literal} = new Ext.data.ArrayStore({
        fields: ['id','name','description','cover'],
        data : {/literal}{$list}{literal}
    });
    var galTpl{/literal}{$tv->id}{literal} = new Ext.XTemplate(
        '<tpl for="."><div class="search-item" style="padding: 4px">'
        ,'<tpl if="cover"><div style="float: right;"><img src="{cover}" alt="" /></div></tpl>'
        ,'{name}'
        ,'<br /><span style="font-size: small; font-style: italic">{description}</span>'
        ,'<div style="clear: both;"></div></div></tpl>'
    );

    var fld = MODx.load({{/literal}
        xtype: 'combo'
        ,store: galStore{$tv->id}
        ,displayField: 'name'
        ,valueField: 'id'
        ,name: 'tv{$tv->id}'
        ,hiddenName: 'tv{$tv->id}'
        ,mode: 'local'
        ,triggerAction: 'all'
        ,applyTo: 'tv{$tv->id}'
        ,value: '{$tv->value}'
        ,tpl: galTpl{$tv->id}
        ,itemSelector: 'div.search-item'
        ,width: {if $params.width}{$params.width}{else}400{/if}
        ,allowBlank: {if $params.allowBlank == 1 || $params.allowBlank == 'true'}true{else}false{/if}
        {if $params.listWidth},listWidth: {$params.listWidth}{/if}
        {if $params.typeAhead}
            ,typeAhead: true
            ,typeAheadDelay: {if $params.typeAheadDelay && $params.typeAheadDelay != ''}{$params.typeAheadDelay}{else}250{/if}
        {else}
            ,editable: false
            ,typeAhead: false
        {/if}
        {if $params.listEmptyText}
            ,listEmptyText: '{$params.listEmptyText}'
        {/if}
        ,forceSelection: {if $params.forceSelection && $params.forceSelection != 'false'}true{else}false{/if}
        ,msgTarget: 'under'
        ,listeners: { 'select': { fn:MODx.fireResourceFormChange, scope:this}}
        
    {literal}});

    var pr = Ext.getCmp('modx-panel-resource');
    if (pr) {
        pr.getForm().add(fld);
    }
    MODx.makeDroppable(fld);
});
// ]]>
</script>
{/literal}