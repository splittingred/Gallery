var GAL = function(config) {
    config = config || {};
    GAL.superclass.constructor.call(this,config);
};
Ext.extend(GAL,Ext.Component,{
    page:{},window:{},grid:{},tree:{},panel:{},combo:{},config: {},view: {}
});
Ext.reg('gallery',GAL);

GAL = new GAL();


GAL.window.CreateAlbum = function(config) {
    config = config || {};
    this.ident = config.ident || 'gcalb'+Ext.id();
    Ext.applyIf(config,{
        title: _('gallery.album_create')
        ,id: this.ident
        // ,height: 150
        ,width: 600
        ,url: GAL.config.connector_url
        ,action: 'mgr/album/create'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'parent'
        },{
            layout: 'column'
            ,border: false
            ,defaults: {
                layout: 'form'
                ,labelAlign: 'top'
                ,anchor: '100%'
                ,border: false
                ,labelSeparator: ''
            }
            ,items: [{
                columnWidth: .5
                ,items: [{
                    xtype: config.record['parent'] == 0 ? 'hidden' : 'statictextfield'
                    ,fieldLabel: _('gallery.parent')
                    ,name: 'parent_name'
                    ,id: this.ident+'-parent-name'
                    ,anchor: '100%'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('name')
                    ,name: 'name'
                    ,id: this.ident+'-name'
                    ,anchor: '100%'
                },{
                    xtype: 'textarea'
                    ,fieldLabel: _('description')
                    ,name: 'description'
                    ,id: this.ident+'-description'
                    ,anchor: '100%'
                }]
            },{
                columnWidth: .5
                ,items: [{
                    xtype: 'textfield'
                    ,fieldLabel: _('gallery.year')
                    ,name: 'year'
                    ,anchor: '100%'
                    ,allowBlank: true
                },{
                    xtype: 'checkbox'
                    ,boxLabel: _('gallery.active')
                    ,description: MODx.expandHelp ? '' : _('gallery.active_desc')
                    ,name: 'active'
                    ,id: this.ident+'-active'
                    ,hideLabel: true
                    ,checked: true
                    ,inputValue: 1
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: this.ident+'-active'
                    ,text: _('gallery.active_desc')
                    ,cls: 'desc-under'

                },{
                    xtype: 'checkbox'
                    ,boxLabel: _('gallery.prominent')
                    ,description: MODx.expandHelp ? '' : _('gallery.prominent_desc')
                    ,name: 'prominent'
                    ,id: this.ident+'-prominent'
                    ,hideLabel: true
                    ,checked: true
                    ,inputValue: 1
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: this.ident+'-prominent'
                    ,text: _('gallery.prominent_desc')
                    ,cls: 'desc-under'

                }]
            }]
        }]
    });
    GAL.window.CreateAlbum.superclass.constructor.call(this,config);
};
Ext.extend(GAL.window.CreateAlbum,MODx.Window);
Ext.reg('gal-window-album-create',GAL.window.CreateAlbum);



GAL.window.UpdateItem = function(config) {
    config = config || {};
    this.ident = config.ident || 'gupdit'+Ext.id();
    Ext.applyIf(config,{
        title: _('gallery.item_update')
        ,id: this.ident
        ,closeAction: 'close'
        // ,height: 150
        // ,width: '55%'
        ,width: 600
        ,url: GAL.config.connector_url
        ,action: 'mgr/item/update'
        ,fileUpload: true
        ,fields: [{
            layout: 'column'
            ,border: false
            ,defaults: {
                layout: 'form'
                ,labelAlign: 'top'
                ,anchor: '100%'
                ,border: false
                ,labelSeparator: ''
            }
            ,items: [{
                columnWidth: .5
                ,items: [{
                    xtype: 'textfield'
                    ,fieldLabel: _('name')
                    ,name: 'name'
                    ,id: this.ident+'-name'
                    ,anchor: '100%'
                },{
                    xtype: 'textarea'
                    ,fieldLabel: _('description')
                    ,name: 'description'
                    ,id: this.ident+'-description'
                    ,anchor: '100%'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('gallery.item_url')
                    ,description: MODx.expandHelp ? '' : _('gallery.item_url_desc')
                    ,name: 'url'
                    ,id: this.ident+'-item-url'
                    ,anchor: '100%'
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: this.ident+'-item-url'
                    ,text: _('gallery.item_url_desc')
                    ,cls: 'desc-under'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('gallery.tags')
                    ,description: MODx.expandHelp ? '' : _('gallery.comma_separated_list')
                    ,name: 'tags'
                    ,id: this.ident+'-tags'
                    ,anchor: '100%'

                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: this.ident+'-tags'
                    ,text: _('gallery.comma_separated_list')
                    ,cls: 'desc-under'

                }]

            },{
                columnWidth: .5
                ,items: [{
                    xtype: 'hidden'
                    ,name: 'thumbnail'
                },{
                    xtype: 'hidden'
                    ,name: 'image'
                },{
                    html: ''
                    ,id: this.ident+'-preview'
                },{
                    xtype: 'statictextfield'
                    ,name: 'id'
                    ,fieldLabel: _('id')
                    ,submitValue: true
                    ,anchor: '100%'
                },{
                    xtype: 'checkbox'
                    ,boxLabel: _('gallery.active')
                    ,description: MODx.expandHelp ? '' : _('gallery.item_active_desc')
                    ,name: 'active'
                    ,id: this.ident+'-active'
                    ,hideLabel: true
                    ,checked: true
                    ,inputValue: 1
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: this.ident+'-active'
                    ,text: _('gallery.item_active_desc')
                    ,cls: 'desc-under'

                }]
            }]
        }]
    });
    GAL.window.UpdateItem.superclass.constructor.call(this,config);
    this.on('activate',function(w,e) {
        if (typeof Tiny != 'undefined') { MODx.loadRTE(this.ident + '-description'); }
        var d = this.fp.getForm().getValues();
        if (d && d.image) {
            var p = Ext.getCmp(this.ident+'-preview');
            var u = d.image+'&h=200&w=200&zc=1&q=100&f=png';
            p.update('<div class="gal-item-update-preview"><img src="'+u+'" alt="" onclick="Ext.getCmp(\'gal-album-items-view\').showScreenshot(\''+d.id+'\'); return false;" /></div>');
        }
    },this);
};
Ext.extend(GAL.window.UpdateItem,MODx.Window);
Ext.reg('gal-window-item-update',GAL.window.UpdateItem);



GAL.window.UploadItem = function(config) {
    config = config || {};
    this.ident = config.ident || 'gupit'+Ext.id();
    Ext.applyIf(config,{
        title: _('gallery.item_upload')
        ,id: this.ident
        // ,height: 150
        // ,width: '55%'
        ,width: 600
        // ,minWidth: 650
        ,url: GAL.config.connector_url
        ,action: 'mgr/item/upload'
        ,fileUpload: true
        ,fields: [{
            xtype: 'hidden'
            ,name: 'album'
        },{
            layout: 'column'
            ,border: false
            ,defaults: {
                layout: 'form'
                ,labelAlign: 'top'
                ,anchor: '100%'
                ,border: false
                ,cls: (MODx.config.connector_url) ? '' : 'main-wrapper' // check for 2.3
                ,labelSeparator: ''
            }
            ,items: [{
                columnWidth: .5
                ,items: [{
                    xtype: 'textfield'
                    ,fieldLabel: _('name')
                    ,name: 'name'
                    ,id: this.ident+'-name'
                    ,anchor: '100%'
                },{
                    xtype: 'textarea'
                    ,fieldLabel: _('description')
                    ,name: 'description'
                    ,id: this.ident+'-description'
                    ,anchor: '100%'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('gallery.item_url')
                    ,description: _('gallery.item_url_desc')
                    ,name: 'url'
                    ,id: this.ident+'-item-url'
                    ,anchor: '100%'
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: this.ident+'-item-url'
                    ,text: _('gallery.item_url_desc')
                    ,cls: 'desc-under'
                }]
            },{
                columnWidth: .5
                ,items: [{
                    xtype: (MODx.config.connector_url) ? 'fileuploadfield' : 'textfield' // check for 2.3
                    ,inputType: (MODx.config.connector_url) ? 'text' : 'file' // check for 2.3
                    ,fieldLabel: _('gallery.file')
                    ,description: MODx.expandHelp ? '' : _('gallery.item_upload_file_desc')
                    ,name: 'file'
                    ,id: this.ident+'-file'
                    ,anchor: '100%'
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: this.ident+'-file'
                    ,text: _('gallery.item_upload_file_desc')
                    ,cls: 'desc-under'

                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('gallery.tags')
                    ,description: MODx.expandHelp ? '' : _('gallery.comma_separated_list')
                    ,name: 'tags'
                    ,id: this.ident+'-tags'
                    ,anchor: '100%'
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: this.ident+'-tags'
                    ,text: _('gallery.comma_separated_list')
                    ,cls: 'desc-under'

                },{
                    xtype: 'checkbox'
                    ,boxLabel: _('gallery.active')
                    ,name: 'active'
                    ,description: ''
                    ,id: this.ident+'-active'
                    ,hideLabel: true
                    ,checked: true
                    ,inputValue: 1
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: this.ident+'-active'
                    ,text: _('gallery.item_active_desc')
                    ,cls: 'desc-under'

                }]
            }]
        }]
    });
    GAL.window.UploadItem.superclass.constructor.call(this,config);
    this.on('activate',function() {
        if (typeof Tiny != 'undefined') { MODx.loadRTE(this.ident + '-description'); }
    });
};
Ext.extend(GAL.window.UploadItem,MODx.Window);
Ext.reg('gal-window-item-upload',GAL.window.UploadItem);

GAL.window.UploadCover = function(config) {
    config = config || {};
    this.ident = config.ident || 'gupit'+Ext.id();
    Ext.applyIf(config,{
        title: _('gallery.cover_upload')
        ,id: this.ident
        // ,height: 150
        ,height: 300 // account for the preview thumbnail that is rendered after the window is opened
        // ,width: 350
        // ,minWidth: 350
        ,saveBtnText:_('gallery.upload_cover')
        ,url: GAL.config.connector_url
        ,action: 'mgr/album/uploadcover'
        ,fileUpload: true
        ,fields: [{
            xtype: 'hidden'
            ,name: 'albumid'
        },{
            layout: 'column'
            ,border: false
            ,defaults: {
                layout: 'form'
                ,labelAlign: 'top'
                ,border: false
                ,cls: (MODx.config.connector_url) ? '' : 'main-wrapper' // check for 2.3
                ,labelSeparator: ''
            }
            ,items: [{
                columnWidth: 1
                ,items: [{
                    xtype:'hidden'
                    ,name:'id'
                },{
                    xtype: (MODx.config.connector_url) ? 'fileuploadfield' : 'textfield' // check for 2.3
                    ,inputType: (MODx.config.connector_url) ? 'text' : 'file' // check for 2.3
                    ,fieldLabel: _('gallery.file')
                    ,description: MODx.expandHelp ? '' : _('gallery.item_upload_file_desc')
                    ,name: 'file'
                    ,id: this.ident+'-file'
                    ,anchor: '100%'
                },{
                    xtype:'panel'
                    ,fieldLabel: _('gallery.current_cover')
                    ,html: ''
                    ,id: this.ident+'-preview'
                }]
            }]
        }]
    });
    GAL.window.UploadCover.superclass.constructor.call(this,config);
};
Ext.extend(GAL.window.UploadCover,MODx.Window);
Ext.reg('gal-window-cover-update',GAL.window.UploadCover);


GAL.window.UploadMultiItems = function(config) {
    config = config || {};
    this.ident = config.ident || 'gupmuit'+Ext.id();
    Ext.applyIf(config,{
        title: _('gallery.multi_item_upload')
        ,id: this.ident
        ,height: 350
        // ,width: 475
        ,fields: [{
            xtype: 'hidden'
            ,name: 'album'
        },{
            layout: 'column'
            ,border: false
            ,defaults: {
                layout: 'form'
                ,labelAlign: 'top'
                ,anchor: '100%'
                ,border: false
                ,labelSeparator: ''
            }
            ,items: [{
                columnWidth: .5
                ,items: [{
                    xtype: 'textfield'
                    ,fieldLabel: _('gallery.tags')
                    ,description: MODx.expandHelp ? '' : _('gallery.comma_separated_list')
                    ,name: 'tags'
                    ,id: this.ident+'-tags'
                    ,anchor: '100%'
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: this.ident+'-tags'
                    ,text: _('gallery.comma_separated_list')
                    ,cls: 'desc-under'

                }]
            },{
                columnWidth: .5
                ,items: [{
                    xtype: 'checkbox'
                    ,boxLabel: _('gallery.active')
                    ,hideLabel: true
                    ,name: 'active'
                    ,description: ''
                    ,id: this.ident+'-active'
                    ,checked: true
                    ,inputValue: 1
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: this.ident+'-active'
                    ,text: _('gallery.item_active_desc')
                    ,cls: 'desc-under'

                }]
            }]
        },{
            html: '<div id="file-upload" />'+_('gallery.loading_ellipsis')+'</div>'
            ,id: 'file-upload-field'
            ,xtype: 'panel'
        }]
        ,buttons: [{
            text: _('done')
            ,scope: this
            ,handler: function() { this.hide(); }
        }]
        ,keys: [] // Prevent enter triggering the window submit
    });
    GAL.window.UploadMultiItems.superclass.constructor.call(this,config);
    this.on('show',this.setup,this)
};
Ext.extend(GAL.window.UploadMultiItems,MODx.Window,{
    setup: function() {
        if (typeof GAL.uploader == 'undefined') {
            GAL.uploader = new qq.FileUploader({
                element: document.getElementById('file-upload')
                ,action: GAL.config.connector_url
                ,params: {
                    action: 'mgr/item/ajaxupload'
                    ,album: this.config.album
                    ,HTTP_MODAUTH: MODx.siteId
                }
                ,onComplete: function() {
                    GAL.uploader.win.fireEvent('success');
                }
                ,onSubmit: function() {
                    var f = GAL.uploader.win.fp.getForm();
                    var data = {
                        active: f.findField('active').getValue() ? 1 : 0
                        ,tags: f.findField('tags').getValue()
                    };
                    var p = this.params;
                    Ext.apply(p,data);
                    GAL.uploader.setParams(p);
                }
            });
            GAL.uploader.win = this;
        }
    }
});
Ext.reg('gal-window-multi-item-upload',GAL.window.UploadMultiItems);

GAL.window.BatchUpload = function(config) {
    config = config || {};
    this.ident = config.ident || 'gupbu'+Ext.id();
    Ext.applyIf(config,{
        title: _('gallery.batch_upload')
        ,id: this.ident
        // ,height: 150
        // ,width: 600
        ,url: GAL.config.connector_url
        ,action: 'mgr/item/batchupload'
        ,fileUpload: true
        ,fields: [{
            xtype: 'hidden'
            ,name: 'album'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('gallery.directory')
            ,description: MODx.expandHelp ? '' : _('gallery.batch_upload_intro')
            ,name: 'directory'
            ,id: this.ident+'-directory'
            ,anchor: '100%'
            ,value: MODx.config['gallery.default_batch_upload_path'] || '{assets_path}images/'

        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: this.ident+'-directory'
            ,text: _('gallery.batch_upload_intro')
            ,cls: 'desc-under'

        },{
            xtype: 'textfield'
            ,fieldLabel: _('gallery.tags')
            ,description: MODx.expandHelp ? '' : _('gallery.batch_upload_tags')
            ,name: 'tags'
            ,id: this.ident+'-tags'
            ,anchor: '100%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: this.ident+'-tags'
            ,text: _('gallery.batch_upload_tags')
            ,cls: 'desc-under'

        },{
            xtype: 'checkbox'
            ,boxLabel: _('gallery.active')
            ,description: MODx.expandHelp ? '' : _('gallery.item_active_desc')
            ,name: 'active'
            ,id: this.ident+'-active'
            ,hideLabel: true
            ,checked: true
            ,inputValue: 1
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: this.ident+'-active'
            ,text: _('gallery.item_active_desc')
            ,cls: 'desc-under'

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
        // ,height: 150
        // ,width: 600
        ,url: GAL.config.connector_url
        ,action: 'mgr/item/zipupload'
        ,fileUpload: true
        ,fields: [{
            xtype: 'hidden'
            ,name: 'album'
        },{
            xtype: (MODx.config.connector_url) ? 'fileuploadfield' : 'textfield' // check for 2.3
            ,inputType: (MODx.config.connector_url) ? 'text' : 'file' // check for 2.3
            ,fieldLabel: _('gallery.zip_file')
            ,description: MODx.expandHelp ? '' : _('gallery.zip_upload_intro')
            ,name: 'zip'
            ,id: this.ident+'-zip'
            ,anchor: '100%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: this.ident+'-zip'
            ,text: _('gallery.zip_upload_intro')
            ,cls: 'desc-under'

        },{
            xtype: 'textfield'
            ,fieldLabel: _('gallery.tags')
            ,description: MODx.expandHelp ? '' : _('gallery.batch_upload_tags')
            ,name: 'tags'
            ,id: this.ident+'-tags'
            ,anchor: '100%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: this.ident+'-tags'
            ,text: _('gallery.batch_upload_tags')
            ,cls: 'desc-under'

        },{
            xtype: 'checkbox'
            ,boxLabel: _('gallery.active')
            ,description: MODx.expandHelp ? '' : _('gallery.item_active_desc')
            ,name: 'active'
            ,id: this.ident+'-active'
            ,hideLabel: true
            ,checked: true
            ,inputValue: 1
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: this.ident+'-active'
            ,text: _('gallery.item_active_desc')
            ,cls: 'desc-under'

        }]
    });
    GAL.window.ZipUpload.superclass.constructor.call(this,config);
};
Ext.extend(GAL.window.ZipUpload,MODx.Window);
Ext.reg('gal-window-zip-upload',GAL.window.ZipUpload);
