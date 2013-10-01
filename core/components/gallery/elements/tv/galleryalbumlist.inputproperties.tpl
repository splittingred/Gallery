<div id="tv-input-properties-form{$tv}"></div>
{literal}

<script type="text/javascript">
// <![CDATA[
var params = {
{/literal}{foreach from=$params key=k item=v name='p'}
 '{$k}': '{$v|escape:"javascript"}'{if NOT $smarty.foreach.p.last},{/if}
{/foreach}{literal}
};
var oc = {'change':{fn:function(){Ext.getCmp('modx-panel-tv').markDirty();},scope:this}};
MODx.load({
    xtype: 'panel'
    ,layout: 'form'
    ,autoHeight: true
    ,labelWidth: 150
    ,border: false
    ,items: [{
        xtype: 'combo-boolean'
        ,fieldLabel: _('required')
        ,description: _('required_desc')
        ,name: 'inopt_allowBlank'
        ,hiddenName: 'inopt_allowBlank'
        ,id: 'inopt_allowBlank{/literal}{$tv}{literal}'
        ,value: params['allowBlank'] == 0 || params['allowBlank'] == 'false' ? false : true
        ,width: 300
        ,listeners: oc
    },{
        xtype: 'combo'
        ,store: [['rank','{/literal}{$gl.rank}{literal}'],['name','{/literal}{$gl.name}{literal}']]
        ,fieldLabel: '{/literal}{$gl.sort}{literal}'
        ,description: '{/literal}{$gl.sort_desc}{literal}'
        ,name: 'inopt_sort'
        ,hiddenName: 'inopt_sort'
        ,forceSelection: true
        ,typeAhead: false
        ,editable: false
        ,triggerAction: 'all'
        ,id: 'inopt_sort{/literal}{$tv}{literal}'
        ,value: params['sort'] || 'rank'
        ,width: 300
        ,listeners: oc
    },{
        xtype: 'combo'
        ,store: [['ASC','{/literal}{$gl.ascending}{literal}'],['DESC','{/literal}{$gl.descending}{literal}']]
        ,fieldLabel: '{/literal}{$gl.sortdir}{literal}'
        ,description: '{/literal}{$gl.sortdir_desc}{literal}'
        ,name: 'inopt_dir'
        ,hiddenName: 'inopt_dir'
        ,forceSelection: true
        ,typeAhead: false
        ,editable: false
        ,triggerAction: 'all'
        ,id: 'inopt_dir{/literal}{$tv}{literal}'
        ,value: params['dir'] || 'DESC'
        ,width: 300
        ,listeners: oc
    },{
        xtype: 'textfield'
        ,fieldLabel: '{/literal}{$gl.limit}{literal}'
        ,description: '{/literal}{$gl.limit_desc}{literal}'
        ,name: 'inopt_limit'
        ,hiddenName: 'inopt_limit'
        ,id: 'inopt_limit{/literal}{$tv}{literal}'
        ,value: params['limit'] || 0
        ,width: 300
        ,listeners: oc
    },{
        xtype: 'textfield'
        ,fieldLabel: '{/literal}{$gl.start}{literal}'
        ,description: '{/literal}{$gl.start_desc}{literal}'
        ,name: 'inopt_start'
        ,hiddenName: 'inopt_start'
        ,id: 'inopt_start{/literal}{$tv}{literal}'
        ,value: params['start'] || 0
        ,width: 300
        ,listeners: oc
    },{
        xtype: 'combo-boolean'
        ,fieldLabel: '{/literal}{$gl.shownone}{literal}'
        ,description: '{/literal}{$gl.shownone_desc}{literal}'
        ,name: 'inopt_showNone'
        ,hiddenName: 'inopt_showNone'
        ,id: 'inopt_showNone{/literal}{$tv}{literal}'
        ,value: params['showNone'] == 0 || params['showNone'] == 'false' ? false : true
        ,width: 300
        ,listeners: oc
    },{
        xtype: 'combo-boolean'
        ,fieldLabel: '{/literal}{$gl.showcover}{literal}'
        ,description: '{/literal}{$gl.showcover_desc}{literal}'
        ,name: 'inopt_showCover'
        ,hiddenName: 'inopt_showCover'
        ,id: 'inopt_showCover{/literal}{$tv}{literal}'
        ,value: params['showCover'] == 0 || params['showCover'] == 'false' ? false : true
        ,width: 300
        ,listeners: oc
    },{
        xtype: 'textfield'
        ,fieldLabel: '{/literal}{$gl.parent}{literal}'
        ,description: '{/literal}{$gl.parent_desc}{literal}'
        ,name: 'inopt_parent'
        ,id: 'inopt_parent{/literal}{$tv}{literal}'
        ,value: params['parent'] || ''
        ,width: 300
        ,listeners: oc
    },{
        xtype: 'combo-boolean'
        ,fieldLabel: '{/literal}{$gl.subchilds}{literal}'
        ,description: '{/literal}{$gl.subchilds_desc}{literal}'
        ,name: 'inopt_subchilds'
        ,id: 'inopt_subchilds{/literal}{$tv}{literal}'
        ,value: params['subchilds'] || ''
        ,width: 300
        ,listeners: oc
    },{
        xtype: 'textfield'
        ,fieldLabel: '{/literal}{$gl.width}{literal}'
        ,description: '{/literal}{$gl.width_desc}{literal}'
        ,name: 'inopt_width'
        ,id: 'inopt_width{/literal}{$tv}{literal}'
        ,value: params['width'] || 400
        ,width: 300
        ,listeners: oc
    },{
        xtype: 'textfield'
        ,fieldLabel: _('combo_listwidth')
        ,description: _('combo_listwidth_desc')
        ,name: 'inopt_listWidth'
        ,id: 'inopt_listWidth{/literal}{$tv}{literal}'
        ,value: params['listWidth'] || ''
        ,width: 300
        ,listeners: oc
    },{
        xtype: 'combo-boolean'
        ,fieldLabel: _('combo_typeahead')
        ,description: _('combo_typeahead_desc')
        ,name: 'inopt_typeAhead'
        ,hiddenName: 'inopt_typeAhead'
        ,id: 'inopt_typeAhead{/literal}{$tv}{literal}'
        ,value: params['typeAhead'] || false
        ,width: 300
        ,listeners: oc
    },{
        xtype: 'textfield'
        ,fieldLabel: _('combo_typeahead_delay')
        ,description: _('combo_typeahead_delay_desc')
        ,name: 'inopt_typeAheadDelay'
        ,id: 'inopt_typeAheadDelay{/literal}{$tv}{literal}'
        ,value: params['typeAheadDelay'] || 250
        ,width: 300
        ,listeners: oc
    },{
        xtype: 'combo-boolean'
        ,fieldLabel: _('combo_forceselection')
        ,description: _('combo_forceselection_desc')
        ,name: 'inopt_forceSelection'
        ,hiddenName: 'inopt_forceSelection'
        ,id: 'inopt_forceSelection{/literal}{$tv}{literal}'
        ,value: params['forceSelection'] || false
        ,width: 300
        ,listeners: oc
    },{
        xtype: 'textfield'
        ,fieldLabel: _('combo_listempty_text')
        ,description: _('combo_listempty_text_desc')
        ,name: 'inopt_listEmptyText'
        ,id: 'inopt_listEmptyText{/literal}{$tv}{literal}'
        ,value: params['listEmptyText'] || ''
        ,width: 300
        ,listeners: oc
    }]
    ,renderTo: 'tv-input-properties-form{/literal}{$tv}{literal}'
});
// ]]>
</script>
{/literal}