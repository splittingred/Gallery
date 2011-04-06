
GAL.tree.Album = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'gal-tree-album'
        ,url: GAL.config.connector_url
        ,action: 'mgr/album/getNodes'
        ,tbar: [{
            text: _('gallery.album_create')
            ,handler: function(btn,e) { this.createAlbum(btn,e,true); }
            ,scope: this
        },'-',{
            text: _('gallery.refresh')
            ,handler: this.refresh
            ,scope: this
        }]
        ,sortAction: 'mgr/album/sort'
        ,rootVisible: false
    })
    GAL.tree.Album.superclass.constructor.call(this,config);    
};
Ext.extend(GAL.tree.Album,MODx.tree.Tree,{
    windows: {}

    ,createAlbum: function(btn,e,b) {
        b = b || false;
        var r;
        if (this.cm.activeNode && !b) {
            var n = this.cm.activeNode.attributes;
            r = {
                'parent': n.pk
                ,parent_name: n.name
            };
        } else {
            r = {'parent':0,parent_name:_('none')};
        }
        
        if (!this.windows.createAlbum) {
            this.windows.createAlbum = MODx.load({
                xtype: 'gal-window-album-create'
                ,record: r
                ,listeners: {
                    'success': {fn:function() { this.refresh(); },scope:this}
                }
            });
        }
        this.windows.createAlbum.fp.getForm().reset();
        this.windows.createAlbum.setValues(r);
        this.windows.createAlbum.show(e.target);
    }
    
    ,updateAlbum: function(btn,e) {
        var id = this.cm.activeNode ? this.cm.activeNode.attributes.pk : 0;
        location.href = '?a='+GAL.action+'&album='+id+'&action=album/update';
    }
    
    ,removeAlbum: function(btn,e) {
        if (!this.cm.activeNode) return false;
        
        MODx.msg.confirm({
            text: _('gallery.album_remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/album/remove'
                ,id: this.cm.activeNode.attributes.pk
            }
            ,listeners: {
                'success': {fn:function(r) { this.refresh(); },scope:this}
            }
        });
    }
        
    
    ,_handleDrag: function(dropEvent) {
        var encNodes = this.encode();
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                data: encNodes
                ,action: this.config.sortAction
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.reloadNode(dropEvent.target.parentNode);
                },scope:this}
                ,'failure': {fn:function(r) {
                    MODx.form.Handler.errorJSON(r);
                    return false;
                },scope:this}
            }
        });
    }
        
    ,_handleDrop: function(e) {
        var target = e.target;
        var source = e.dropNode;
        
        var ap = true;
        return target.getDepth() <= source.getDepth() && ap;
    }
    
});
Ext.reg('gal-tree-album',GAL.tree.Album);



GAL.window.CreateAlbum = function(config) {
    config = config || {};
    this.ident = config.ident || 'gcalb'+Ext.id();
    Ext.applyIf(config,{
        title: _('gallery.album_create')
        ,id: this.ident
        ,height: 150
        ,width: 475
        ,url: GAL.config.connector_url
        ,action: 'mgr/album/create'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'parent'
        },{
            xtype: 'statictextfield'
            ,fieldLabel: _('gallery.parent')
            ,name: 'parent_name'
            ,id: 'gal-'+this.ident+'-parent-name'
            ,width: 200
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

