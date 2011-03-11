GAL.panel.Album = function(config) {
    config = config || {};
        
    Ext.apply(config,{
        id: 'gal-panel-album'
        ,url: GAL.config.connector_url
        ,baseParams: {}
        ,border: false
        ,baseCls: 'modx-formpanel'
        ,items: [{
            html: '<h2>'+_('gallery.album')+'</h2>'
            ,border: false
            ,id: 'gal-album-header'
            ,cls: 'modx-page-header'
        },{
            xtype: 'modx-tabs'
            ,bodyStyle: 'padding: 10px'
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
                    ,width: 400
                },{
                    xtype: 'checkbox'
                    ,fieldLabel: _('gallery.active')
                    ,description: _('gallery.active_desc')
                    ,name: 'active'
                    ,inputValue: true
                },{
                    xtype: 'checkbox'
                    ,fieldLabel: _('gallery.prominent')
                    ,description: _('gallery.prominent_desc')
                    ,name: 'prominent'
                    ,inputValue: true
                },{
                    html: '<hr />',border: false
                },{
                    xtype: 'gal-panel-album-items'
                    ,cls: 'modx-pb-view-ct'
                    ,album: config.album
                }]
            }/*,{
                title: 'Context Access'
                ,layout: 'form'
                ,items: [{
                    html: '<p>Manage the Contexts that have access to this album.</p><br />'
                    ,border: false
                }]
            }*/]
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

                    Ext.getCmp('gal-album-header').getEl().update('<h2>'+_('gallery.album')+': '+r.object.name+'</h2>');
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
        ,cls: 'gal-view-album-items'
        ,album: config.album
        ,inPanel: true
        ,style: 'overflow: auto;'
    });
    this.view.pagingBar = new Ext.PagingToolbar({
        pageSize: 24
        ,store: this.view.store
        ,displayInfo: true
        ,autoLoad: true
    });
    var dv = this.view;
    
    
    dv.on('render', function() {
        dv.dragZone = new MODx.DataView.dragZone(dv);
        dv.dropZone = new MODx.DataView.dropZone(dv);
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
        ,items: [{
            id: 'gal-album-items-ct'
            ,cls: 'browser-view'
            ,region: 'center'
            ,width: '75%'
            ,height: 450
            ,autoScroll: true
            ,border: false
            ,items: [{
                xtype: 'toolbar'
                ,items: [{
                    xtype: 'button'
                    ,text: _('gallery.item_upload')
                    ,handler: this.uploadItem
                    ,scope: this
                },'-',{
                    xtype: 'button'
                    ,text: _('gallery.batch_upload')
                    ,handler: this.batchUpload
                    ,scope: this
                },'-',{
                    xtype: 'button'
                    ,text: _('gallery.zip_upload')
                    ,handler: this.zipUpload
                    ,scope: this
                }]
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

    ,handleSort: function(o) {
        var s = this.view.store;
        console.log(o);
        return;
        var origRec = s.getAt(o.sourceId);
        var lastRec = s.getAt(o.targetId);

        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'item/sort'
                ,album: this.config.album
                ,source: o.sourceIndex
                ,target: o.targetIndex
            }
        });
    }
    
    ,uploadItem: function(btn,e) {
        var r = {
            album: this.config.album
            ,active: true
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

    ,batchUpload: function(btn,e) {
        var r = {
            album: this.config.album
            ,active: true
        };
        if (!this.windows.batchUpload) {
            this.windows.batchUpload = MODx.load({
                xtype: 'gal-window-batch-upload'
                ,listeners: {
                    'success': {fn:function() { this.view.run(); },scope:this}
                }
            });
        } else {
            this.windows.batchUpload.fp.getForm().reset();
        }
        this.windows.batchUpload.setValues(r);
        this.windows.batchUpload.show(e.target);
    }

    ,zipUpload: function(btn,e) {
        var r = {
            album: this.config.album
            ,active: true
        };
        if (!this.windows.zipUpload) {
            this.windows.zipUpload = MODx.load({
                xtype: 'gal-window-zip-upload'
                ,listeners: {
                    'success': {fn:function() { this.view.run(); },scope:this}
                }
            });
        } else {
            this.windows.zipUpload.fp.getForm().reset();
        }
        this.windows.zipUpload.setValues(r);
        this.windows.zipUpload.show(e.target);
    }
});
Ext.reg('gal-panel-album-items',GAL.panel.AlbumItems);



GAL.window.UploadItem = function(config) {
    config = config || {};
    this.ident = config.ident || 'gupit'+Ext.id();
    Ext.applyIf(config,{
        title: _('gallery.item_upload')
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
            ,fieldLabel: _('gallery.file')
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
            ,fieldLabel: _('gallery.active')
            ,name: 'active'
            ,description: ''
            ,id: 'gal-'+this.ident+'-active'
            ,checked: true
            ,inputValue: 1
        },{
            xtype: 'textfield'
            ,fieldLabel: _('gallery.tags')
            ,description: _('gallery.comma_separated_list')
            ,name: 'tags'
            ,id: 'gal-'+this.ident+'-tags'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('gallery.item_url')
            ,description: _('gallery.item_url_desc')
            ,name: 'url'
            ,id: 'gal-'+this.ident+'-item-url'
            ,width: 300
        }]
    });
    GAL.window.UploadItem.superclass.constructor.call(this,config);
};
Ext.extend(GAL.window.UploadItem,MODx.Window);
Ext.reg('gal-window-item-upload',GAL.window.UploadItem);

GAL.window.BatchUpload = function(config) {
    config = config || {};
    this.ident = config.ident || 'gupbu'+Ext.id();
    Ext.applyIf(config,{
        title: _('gallery.batch_upload')
        ,id: this.ident
        ,height: 150
        ,width: 475
        ,url: GAL.config.connector_url
        ,action: 'mgr/item/batchupload'
        ,fileUpload: true
        ,fields: [{
            xtype: 'hidden'
            ,name: 'album'
        },{
            html: _('gallery.batch_upload_intro')
            ,border: false
        },MODx.PanelSpacer,{
            xtype: 'textfield'
            ,fieldLabel: _('gallery.directory')
            ,name: 'directory'
            ,id: 'gal-'+this.ident+'-directory'
            ,width: 300
            ,value: MODx.config['gallery.default_batch_upload_path'] || '{assets_path}images/'
        },{
            xtype: 'checkbox'
            ,fieldLabel: _('gallery.active')
            ,name: 'active'
            ,description: ''
            ,id: 'gal-'+this.ident+'-active'
            ,checked: true
            ,inputValue: 1
        },{
            xtype: 'textfield'
            ,fieldLabel: _('gallery.tags')
            ,description: _('gallery.batch_upload_tags')
            ,name: 'tags'
            ,id: 'gal-'+this.ident+'-tags'
            ,width: 300
        }]
    });
    GAL.window.BatchUpload.superclass.constructor.call(this,config);
};
Ext.extend(GAL.window.BatchUpload,MODx.Window);
Ext.reg('gal-window-batch-upload',GAL.window.BatchUpload);

GAL.window.ZipUpload = function(config) {
    config = config || {};
    this.ident = config.ident || 'gupbu'+Ext.id();
    Ext.applyIf(config,{
        title: _('gallery.zip_upload')
        ,id: this.ident
        ,height: 150
        ,width: 475
        ,url: GAL.config.connector_url
        ,action: 'mgr/item/zipupload'
        ,fileUpload: true
        ,fields: [{
            xtype: 'hidden'
            ,name: 'album'
        },{
            html: _('gallery.zip_upload_intro')
            ,border: false
        },MODx.PanelSpacer,{
            xtype: 'textfield'
            ,inputType: 'file'
            ,fieldLabel: _('gallery.zip_file')
            ,name: 'zip'
            ,id: 'gal-'+this.ident+'-zip'
            ,anchor: '97%'
        },{
            xtype: 'checkbox'
            ,fieldLabel: _('gallery.active')
            ,name: 'active'
            ,description: ''
            ,id: 'gal-'+this.ident+'-active'
            ,checked: true
            ,inputValue: 1
        },{
            xtype: 'textfield'
            ,fieldLabel: _('gallery.tags')
            ,description: _('gallery.batch_upload_tags')
            ,name: 'tags'
            ,id: 'gal-'+this.ident+'-tags'
            ,anchor: '97%'
        }]
    });
    GAL.window.ZipUpload.superclass.constructor.call(this,config);
};
Ext.extend(GAL.window.ZipUpload,MODx.Window);
Ext.reg('gal-window-zip-upload',GAL.window.ZipUpload);
