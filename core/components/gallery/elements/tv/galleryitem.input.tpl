<div id="tv{$tv->id}-form"></div>

{literal}
<script type="text/javascript">
// <![CDATA[
var oc = {'change':{fn:function(){Ext.getCmp('modx-panel-resource').markDirty();},scope:this}};
MODx.load({
    xtype: 'panel'
    ,layout: 'fit'
    ,autoHeight: true
    ,labelWidth: 150
    ,border: false
    ,items: [{
        xtype: 'textfield'
        ,name: 'tv{/literal}{$tv->id}{literal}'
        ,id: 'tv{/literal}{$tv->id}{literal}'
        ,value: '{/literal}{$tv->value}{literal}'
        ,width: 300
        ,listeners: oc
    }]
    ,renderTo: 'tv{/literal}{$tv->id}{literal}-form'
});
// ]]>
</script>
{/literal}