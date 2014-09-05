
GAL.grid.Albums = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'gal-grid-albums'
        ,url: GAL.config.connector_url
        ,baseParams: {
            action: 'mgr/album/getlist'
        }
        ,fields: ['id','name','description','items','menu']
        ,autoHeight: true
        ,paging: true
        ,remoteSort: true
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 70
        },{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 200
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,width: 250
        },{
            header: _('gallery.items')
            ,dataIndex: 'items'
            ,width: 70
        }]
        ,tbar: [{
            text: _('gallery.album_create')
            ,cls: 'primary-button'
            ,handler: this.createAlbum
            ,scope: this
        }]
    });
    GAL.grid.Albums.superclass.constructor.call(this,config);
};
Ext.extend(GAL.grid.Albums,MODx.grid.Grid,{
    windows: {}
    
    ,createAlbum: function(btn,e) {
        if (!this.windows.createAlbum) {
            this.windows.createAlbum = MODx.load({
                xtype: 'gal-window-album-create'
                ,listeners: {
                    'success': {fn:function() { this.refresh(); },scope:this}
                }
            });
        }
        this.windows.createAlbum.fp.getForm().reset();
        this.windows.createAlbum.show(e.target);
    }
    ,updateAlbum: function(btn,e) {
        if (!this.menu.record || !this.menu.record.id) return false;
        
        location.href = '?a='+MODx.request.a+'&action=album/update'+'&album='+this.menu.record.id;
    }
    
    ,removeAlbum: function(btn,e) {
        if (!this.menu.record) return false;
        
        MODx.msg.confirm({
            title: _('gallery.album_remove')
            ,text: _('gallery.album_remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/album/remove'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:function(r) { this.refresh(); },scope:this}
            }
        });
    }
});
Ext.reg('gal-grid-albums',GAL.grid.Albums);




GAL.window.CreateAlbum = function(config) {
    config = config || {};
    this.ident = config.ident || 'gcalb'+Ext.id();
    Ext.applyIf(config,{
        title: _('gallery.album_create')
        ,id: this.ident
        // ,height: 150
        // ,width: 475
        ,url: GAL.config.connector_url
        ,action: 'mgr/album/create'
        ,fields: [{
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
            xtype: 'textfield'
            ,fieldLabel: _('gallery.year')
            ,name: 'year'
            ,anchor: '100%'
            ,allowBlank: true
        },{
            xtype: 'checkbox'
            ,fieldLabel: _('gallery.active')
            ,name: 'active'
            ,id: 'gal-'+this.ident+'-active'
            ,checked: true
            ,inputValue: 1
        },{
            xtype: 'checkbox'
            ,fieldLabel: _('gallery.prominent')
            ,description: _('gallery.prominent_desc')
            ,name: 'prominent'
            ,id: 'gal-'+this.ident+'-prominent'
            ,checked: true
            ,inputValue: 1
        }]
    });
    GAL.window.CreateAlbum.superclass.constructor.call(this,config);
};
Ext.extend(GAL.window.CreateAlbum,MODx.Window);
Ext.reg('gal-window-album-create',GAL.window.CreateAlbum);
