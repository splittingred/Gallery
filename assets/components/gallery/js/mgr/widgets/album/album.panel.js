GAL.panel.Album = function(config) {
    config = config || {};

    Ext.apply(config,{
        id: 'gal-panel-album'
        ,url: GAL.config.connector_url
        ,baseParams: {}
        ,border: false
        ,baseCls: 'modx-formpanel'
        ,cls: 'container form-with-labels'
        ,items: [{
            html: '<h2>'+_('gallery.album')+'</h2>'
            ,border: false
            ,id: 'gal-album-header'
            ,cls: 'modx-page-header'
        },{
            xtype: 'modx-tabs'
            ,defaults: { border: false ,autoHeight: true }
            ,border: true
            ,activeItem: 0
            ,hideMode: 'offsets'
            ,items: [{
                title: _('general_information')
                ,border: false
                ,items: [{
                    layout: 'column'
                    ,border: false
                    ,defaults: {
                        layout: 'form'
                        ,labelAlign: 'top'
                        ,anchor: '100%'
                        ,border: false
                        ,cls:'main-wrapper'
                        ,labelSeparator: ''
                    }
                    ,items: [{
                        columnWidth: .6
                        ,items: [{
                            xtype: 'hidden'
                            ,fieldLabel: _('id')
                            ,name: 'id'
                            ,submitValue: true
                        },{
                            xtype: 'textfield'
                            ,fieldLabel: _('name')
                            ,name: 'name'
                            ,anchor: '100%'
                            ,allowBlank: false
                        },{
                            xtype: 'textfield'
                            ,fieldLabel: _('gallery.year')
                            ,name: 'year'
                            ,anchor: '100%'
                            ,allowBlank: true
                        },{
                            xtype: 'textarea'
                            ,fieldLabel: _('description')
                            ,name: 'description'
                            ,anchor: '100%'
                        },{
                            layout: 'column'
                            ,border:false
						    ,fieldLabel: _('gallery.cover_filename')
                            ,items: [{
                                xtype: 'textfield'
                                ,name: 'cover_filename'
                                ,id: 'cover_filename'
                                ,readOnly: true
                                ,allowBlank: true
                                ,columnWidth: .6
                            },{
                                xtype:'hidden'
                                ,name:'cover_filename_url'
                                ,id:'cover_filename_url'
                            },{
                                xtype:'button'
                                ,text: _('gallery.upload_cover')
                                ,cls: 'primary-button'
                                // ,height: 39
                                ,handler: this.updateCover
                            },{
                                xtype:'button'
                                ,text: _('gallery.delete_cover')
                                // ,height: 39
                                ,handler:function() {
                                    var panel=Ext.getCmp('gal-panel-album').getForm();
                                    panel.findField('cover_filename').setValue('');
                                    panel.findField('cover_filename_url').setValue('');
                                }
                            }]
                        }]
                    },{
                        columnWidth: .4
                        ,items: [{
                            xtype: 'checkbox'
                            ,boxLabel: _('gallery.active')
                            ,description: MODx.expandHelp ? '' : _('gallery.active_desc')
                            ,id: 'gallery-album-active'
                            ,name: 'active'
                            ,hideLabel: true
                            ,inputValue: true
                        },{
                            xtype: MODx.expandHelp ? 'label' : 'hidden'
                            ,forId: 'gallery-album-active'
                            ,text: _('gallery.active_desc')
                            ,cls: 'desc-under'
                        },{
                            xtype: 'checkbox'
                            ,boxLabel: _('gallery.prominent')
                            ,description: MODx.expandHelp ? '' : _('gallery.prominent_desc')
                            ,id: 'gallery-album-prominent'
                            ,name: 'prominent'
                            ,hideLabel: true
                            ,inputValue: true
                        },{
                            xtype: MODx.expandHelp ? 'label' : 'hidden'
                            ,forId: 'gallery-album-prominent'
                            ,text: _('gallery.prominent_desc')
                            ,cls: 'desc-under'
                        }]
                    }]
                },{
                    html: '<hr />',border: false
                },{
                    xtype: 'gal-panel-album-items'
                    ,cls: 'modx-pb-view-ct main-wrapper'
                    ,album: config.album
                    ,anchor: '100%'
                }]
            }]
            /*,{
                title: 'Context Access'
                ,layout: 'form'
                ,items: [{
                    html: '<p>Manage the Contexts that have access to this album.</p><br />'
                    ,border: false
                }]
            }*/
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
    initialized: false
    ,windows: {}
    ,setup: function() {
        if (!this.config.album || this.initialized) return;
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
                    this.initialized = true;
                },scope:this}
            }
        });
    }
    ,beforeSubmit: function(o) {
        Ext.apply(o.form.baseParams,{
        });
    }
    ,updateCover:function(btn,e) {
        var form=this.findParentByType('gal-panel-album');
        var data=form.getForm().getValues();
        /**
         * We'll need a "fresh" window when using Tiny for the description field,
         * so we don't check if it exists but just load a new window.
         */
        form.windows.updateCover = MODx.load({
            xtype: 'gal-window-cover-update'
            ,listeners: {
                'success': function(o) {
                    if(o.a.result.object) {
                        var panel=Ext.getCmp('gal-panel-album');
                        panel.getForm().setValues(o.a.result.object);
                    }
                    this.close();
                 }
            }
        });
        form.windows.updateCover.setValues(data);
        var previewDivName=form.windows.updateCover.ident+'-preview';
        var preview=form.windows.updateCover.find('id',previewDivName);
        if(preview.length>0) {
	        if(data.cover_filename_url!='') {
	            var now=new Date();
	            preview[0].html='<img src="'+data.cover_filename_url+'&time='+now.getTime()+'"/>';
	        } else {
		        preview[0].setVisible(false);
	        }
		}
        form.windows.updateCover.show(e.target);
    }
    ,success: function(o) {
        Ext.getCmp('gal-btn-save').setDisabled(false);
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
        pageSize: config.pageSize || (parseInt(MODx.config.default_per_page) || 24)
        ,store: this.view.store
        ,displayInfo: true
        ,autoLoad: true
        ,items: [
            '-'
            ,_('per_page')+':'
            ,{
                xtype: 'textfield'
                ,value: config.pageSize || (parseInt(MODx.config.default_per_page) || 20)
                ,width: 40
                ,listeners: {
                    'change': {fn:function(tf,nv,ov) {
                        if (Ext.isEmpty(nv)) return false;
                        nv = parseInt(nv);
                        this.view.pagingBar.pageSize = nv;
                        this.view.store.load({params:{
                            start:0
                            ,limit: nv
                        }});
                    },scope:this}
                    ,'render': {fn: function(cmp) {
                        new Ext.KeyMap(cmp.getEl(), {
                            key: Ext.EventObject.ENTER
                            ,fn: function() {
                                this.fireEvent('change',this.getValue());
                                this.blur();
                                return true;}
                            ,scope: cmp
                        });
                    },scope:this}
                }
            }
            ,'-'
        ]
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
            ,minHeight: 450
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
                    ,text: _('gallery.multi_item_upload')
                    ,handler: this.uploadMultiItems
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
            ,width: '25%'
            ,minWidth: 150
            // ,maxWidth: 250
            ,height: 450
            ,border: false
        }]
    });
    GAL.panel.AlbumItems.superclass.constructor.call(this,config);

};
Ext.extend(GAL.panel.AlbumItems,MODx.Panel,{
    windows: {}

    ,uploadMultiItems: function(btn,e) {
        var r = {
            album: this.config.album
            ,active: true
        };
        if (!this.windows.uploadMultiItems) {
            this.windows.uploadMultiItems = MODx.load({
                xtype: 'gal-window-multi-item-upload'
                ,album: this.config.album
                ,listeners: {
                    'success': {fn:function() { this.view.run(); },scope:this}
                }
            });
        }
        this.windows.uploadMultiItems.fp.getForm().reset();
        this.windows.uploadMultiItems.setValues(r);
        this.windows.uploadMultiItems.show(e.target);
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
