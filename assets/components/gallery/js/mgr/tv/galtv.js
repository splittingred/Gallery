
GAL.TV = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        xtype: 'panel'
        ,layout: 'fit'
        ,autoHeight: true
        ,labelWidth: 150
        ,border: false
        ,anchor: '97%'
        ,items: [{
            xtype: 'gal-combo-browser'
            ,name: 'tv'+config.tv
            ,id: 'tv'+config.tv
            ,value: config.tvValue
            ,tv: config.tv
        }]
        ,renderTo: 'tv'+config.tv+'-form'
    });
    GAL.TV.superclass.constructor.call(this,config);
};
Ext.extend(GAL.TV,MODx.Panel);
Ext.reg('gal-panel-tv',GAL.TV);


GAL.combo.Browser = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        width: 300
        ,editable: false
        ,typeAhead: false
        ,triggerAction: 'all'
        ,listeners: {
            'change': {fn:function(){Ext.getCmp('modx-panel-resource').markDirty();},scope:this}
        }
    });
    GAL.combo.Browser.superclass.constructor.call(this,config);
    this.config = config;
    this.previewTpl = new Ext.XTemplate('<tpl for=".">'
        ,'<div class="gal-tv-preview" style="margin-top: 5px;">'
        ,'<img src="{url}" alt="{name}" style="float: left; margin-right: 10px;"/>'
        ,'<span style="font-weight: bold;">{name}</span><br />'
        ,'<p>{description}</p>'
        ,'</div>'
        ,'</tpl>');
    this.previewTpl.compile();
};
Ext.extend(GAL.combo.Browser,Ext.form.TriggerField,{
    browser: null

    ,onTriggerClick : function(){
        if (this.disabled){
            return false;
        }

        if (this.browser === null) {
            this.browser = MODx.load({
                xtype: 'gal-browser'
                ,prependPath: this.config.prependPath || null
                ,prependUrl: this.config.prependUrl || null
                ,hideFiles: this.config.hideFiles || false
                ,rootVisible: this.config.rootVisible || false
                ,listeners: {
                    'select': {fn: function(data) {
                        data.url = MODx.config.connectors_url+'system/phpthumb.php?h='+200+'&src='+data.absoluteImage;
                        this.setValue(data.id);
                        this.fireEvent('select',data);

                        var p = Ext.get('tv'+this.config.tv+'-preview');
                        if (p) {
                            this.previewTpl.overwrite(p,data);
                        }
                        Ext.getCmp('modx-panel-resource').markDirty();
                    },scope:this}
                }
            });
        }
        this.browser.show();
        return true;
    }

    ,onDestroy: function(){
        GAL.combo.Browser.superclass.onDestroy.call(this);
    }
});
Ext.reg('gal-combo-browser',GAL.combo.Browser);

