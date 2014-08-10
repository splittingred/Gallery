
GAL.tree.Album = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'gal-tree-album'
        ,url: GAL.config.connector_url
        ,action: 'mgr/album/getNodes'
        ,tbar: [{
            text: _('gallery.album_create')
            ,cls: 'primary-button'
            ,handler: function(btn,e) { this.createAlbum(btn,e,true); }
            ,scope: this
        },'-',{
            text: _('gallery.refresh')
            ,handler: this.refresh
            ,scope: this
        }]
        ,sortAction: 'mgr/album/sort'
        ,rootVisible: false
    });
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

