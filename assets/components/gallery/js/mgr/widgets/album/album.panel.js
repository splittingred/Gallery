GAL.panel.Album = function(config) {
    config = config || {};
        
    Ext.apply(config,{
        id: 'gal-panel-album'
        ,url: GAL.config.connector_url
        ,baseParams: {}
        ,border: false
        ,baseCls: 'modx-formpanel'
        ,items: [{
            html: '<h2>'+_('album')+'</h2>'
            ,border: false
            ,id: 'gal-album-header'
            ,cls: 'modx-page-header'
        },{
            xtype: 'modx-tabs'
            ,bodyStyle: 'padding: 1em'
            ,defaults: { border: false ,autoHeight: true }
            ,border: true
            ,activeItem: 0
            ,hideMode: 'offsets'
            ,items: [{
                title: _('general_information')
                ,layout: 'form'
                ,items: [{                    
                    xtype: 'statictextfield'
                    ,fieldLabel: _('id')
                    ,name: 'id'
                    ,submitValue: true
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('name')
                    ,name: 'name'
                    ,width: 250
                    ,allowBlank: false  
                },{
                    xtype: 'textarea'
                    ,fieldLabel: _('description')
                    ,name: 'description'
                    ,width: 300
                    ,grow: true
                },{
                    xtype: 'checkbox'
                    ,fieldLabel: 'Active'
                    ,description: 'If false, this album will not be viewable.'
                    ,name: 'active'
                    ,inputValue: true
                },{
                    xtype: 'checkbox'
                    ,fieldLabel: 'Prominent'
                    ,description: ''
                    ,name: 'prominent'
                    ,inputValue: true
                }]
            },{
                title: 'Items'
                ,layout: 'form'
                ,items: [{
                    xtype: 'gal-panel-album-items'
                    ,album: config.album
                }]
            },{
                title: 'Context Access'
                ,layout: 'form'
                ,items: [{
                    html: '<p>Manage the Contexts that have access to this album.</p><br />'
                    ,border: false
                }]
            }]
        }]
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
            ,'success': {fn:this.success,scope:this}
        }
    });
    GAL.panel.Album.superclass.constructor.call(this,config);
};
Ext.extend(GAL.panel.Album,MODx.FormPanel,{
    setup: function() {
        if (!this.config.album) return;
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'mgr/album/get'
                ,id: this.config.album
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getForm().setValues(r.object);

                    Ext.getCmp('gal-album-header').getEl().update('<h2>'+'Album'+': '+r.object.name+'</h2>');
                },scope:this}
            }
        });
    }
    ,beforeSubmit: function(o) {
        Ext.apply(o.form.baseParams,{
        });
    }
    ,success: function(o) {
        Ext.getCmp('dis-btn-save').setDisabled(false);
    }
});
Ext.reg('gal-panel-album',GAL.panel.Album);



GAL.panel.AlbumItems = function(config) {
    config = config || {};
    
    this.view = MODx.load({
        id: 'gal-album-items-view'
        ,xtype: 'gal-view-album-items'
        ,onSelect: {fn:function() { }, scope: this}
        ,containerScroll: true
        ,ident: this.ident
        ,style:'overflow:auto; border-top: 1px solid #d4d4d4; margin-top: 4px;'
        ,album: config.album
    });
    this.view.pagingBar = new Ext.PagingToolbar({
        pageSize: 10
        ,store: this.view.store
        ,displayInfo: true
        ,autoLoad: true
    });
    
    Ext.applyIf(config,{
        id: 'gal-panel-album-items'
        ,cls: 'browser-win'
        ,layout: 'column'
        ,minWidth: 500
        ,minHeight: 350
        ,width: '90%'
        ,autoHeight: true
        ,modal: false
        ,closeAction: 'hide'
        ,border: false
        ,autoScroll: true
        ,items: [/*{
            id: this.ident+'-album-tree'
            ,cls: 'album-tree'
            ,region: 'west'
            ,width: '25%'
            ,items: [{
                xtype: 'gal-album-tree'
            }]
            ,autoScroll: true
            ,border: false
        },*/{
            id: 'gal-album-items-ct'
            ,cls: 'browser-view'
            ,region: 'center'
            ,width: '80%'
            ,height: 450
            ,autoScroll: true
            ,border: false
            ,items: [{
                xtype: 'button'
                ,text: 'Upload Item'
                ,handler: this.uploadItem
                ,scope: this
            },this.view]
            ,bbar: [this.view.pagingBar]
        },{
            html: ''
            ,id: 'gal-album-items-detail'
            ,region: 'east'
            ,split: true
            ,autoScroll: true
            ,width: '20%'
            ,minWidth: 150
            ,maxWidth: 250
            ,height: 450
            ,border: false
        }]
    });
    GAL.panel.AlbumItems.superclass.constructor.call(this,config);
};
Ext.extend(GAL.panel.AlbumItems,MODx.Panel,{
    windows: {}
    
    ,uploadItem: function(btn,e) {
        var r = {
            album: this.config.album
        };
        if (!this.windows.uploadItem) {
            this.windows.uploadItem = MODx.load({
                xtype: 'gal-window-item-upload'
                ,listeners: {
                    'success': {fn:function() { this.view.run(); },scope:this}
                }
            });
        }
        this.windows.uploadItem.fp.getForm().reset();
        this.windows.uploadItem.setValues(r);
        this.windows.uploadItem.show(e.target);
    }
});
Ext.reg('gal-panel-album-items',GAL.panel.AlbumItems);



GAL.window.UploadItem = function(config) {
    config = config || {};
    this.ident = config.ident || 'gupit'+Ext.id();
    Ext.applyIf(config,{
        title: 'Upload Item'
        ,id: this.ident
        ,height: 150
        ,width: 475
        ,url: GAL.config.connector_url
        ,action: 'mgr/item/upload'
        ,fileUpload: true
        ,fields: [{
            xtype: 'hidden'
            ,name: 'album'
        },{
            xtype: 'textfield'
            ,inputType: 'file'
            ,fieldLabel: 'File'
            ,name: 'file'
            ,id: 'gal-'+this.ident+'-file'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('name')
            ,name: 'name'
            ,id: 'gal-'+this.ident+'-name'
            ,width: 300
        },{
            xtype: 'textarea'
            ,fieldLabel: _('description')
            ,name: 'description'
            ,id: 'gal-'+this.ident+'-description'
            ,width: 300
        },{
            xtype: 'checkbox'
            ,fieldLabel: 'Active'
            ,name: 'active'
            ,description: ''
            ,id: 'gal-'+this.ident+'-active'
            ,checked: true
            ,inputValue: 1
        }]
    });
    GAL.window.UploadItem.superclass.constructor.call(this,config);
};
Ext.extend(GAL.window.UploadItem,MODx.Window);
Ext.reg('gal-window-item-upload',GAL.window.UploadItem);
