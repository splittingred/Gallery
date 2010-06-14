<div id="tv-wprops-form{$tv}"></div>
{literal}

<script type="text/javascript">
// <![CDATA[
var params = {/literal}{$params}{literal};
var oc = {'change':{fn:function(){Ext.getCmp('modx-panel-tv').markDirty();},scope:this}};
var tv = '{/literal}{$tv}{literal}';
MODx.load({
    xtype: 'panel'
    ,layout: 'form'
    ,autoHeight: true
    ,labelWidth: 150
    ,border: false
    ,items: [{
        xtype: 'textfield'
        ,fieldLabel: 'Text'
        ,description: ''
        ,name: 'prop_text'
        ,id: 'prop_text'+tv
        ,value: params['text'] || 'test'
        ,width: 300
        ,listeners: oc
    },{
        xtype: 'combo-boolean'
        ,fieldLabel: 'TestBoolean'
        ,description: ''
        ,name: 'prop_boolean'
        ,hiddenName: 'prop_boolean'
        ,id: 'prop_boolean'+tv
        ,value: params['boolean'] || 0
        ,width: 100
        ,listeners: oc
    }]
    ,renderTo: 'tv-wprops-form{/literal}{$tv}{literal}'
});
// ]]>
</script>
{/literal}