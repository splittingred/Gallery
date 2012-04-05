var galTreeHandlerClass = function(config) {
    config = config || {};
    Ext.apply(config,{
        id: 'gal-tree-handler'
    });
    galTreeHandlerClass.superclass.constructor.call(this,config);
};
Ext.extend(galTreeHandlerClass,Ext.Component,{
    tree: null
    ,data: {}
    ,windows: {}

    ,getMenu: function(t,node,e) {
        this.tree = t;
        this.data = node.attributes && node.attributes.data ? node.attributes.data : {};
        var m = [];
        switch (node.attributes.type) {
            case 'root':
                m = this.getRootMenu(node,e);
                break;
            case 'gallery-album':
                m = this.getAlbumMenu(node,e);
                break;
            case 'gallery-item':
                m = this.getItemMenu(node,e);
                break;
        }

        return m;
    }
    /* custom methods here */

    ,getItemMenu: function(node,e) {
        return [{
            text: _('gallery.item_update')
            ,handler: this.updateItem
            ,scope: this
        },'-',{
            text: _('gallery.item_remove')
            ,handler: this.removeItem
            ,scope: this
        }];
    }
    ,getAlbumMenu: function(node,e) {
        return [{
            text: _('gallery.album_create')
            ,handler: this.createAlbum
            ,scope: this
        },'-',{
            text: _('gallery.album_update')
            ,handler: this.updateAlbum
            ,scope: this
        },{
            text: _('gallery.upload')
            ,menu: {
                items: [{
                    text: _('gallery.item_upload')
                    ,handler: this.uploadItem
                    ,scope: this
                },{
                    text: _('gallery.multi_item_upload')
                    ,handler: this.uploadMultiItems
                    ,scope: this
                },{
                    text: _('gallery.batch_upload')
                    ,handler: this.batchUpload
                    ,scope: this
                },{
                    text: _('gallery.zip_upload')
                    ,handler: this.zipUpload
                    ,scope: this
                }]
            }
        },'-',{
            text: _('gallery.album_remove')
            ,handler: this.removeAlbum
            ,scope: this
        }];
    }
    ,getRootMenu: function(node,e) {
        return [{
            text: _('gallery.album_create')
            ,handler: this.createAlbum
            ,scope: this
        }];
    }

    ,createAlbum: function(btn,e) {
        var r;
        if (this.data.id) {
            var n = this.tree.cm.activeNode.attributes.data;
            r = {
                'parent': n.id
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
                    'success': {fn:function() { this.tree.refreshParentNode(); },scope:this}
                }
            });
        }
        this.windows.createAlbum.fp.getForm().reset();
        this.windows.createAlbum.setValues(r);
        this.windows.createAlbum.show(e.target);
    }
    ,updateAlbum: function(btn,e) {
        var id = this.data.id ? this.data.id : 0;
        location.href = '?a='+MODx.action['gallery:index']+'&album='+id+'&action=album/update';
    }
    ,removeAlbum: function(btn,e) {
        MODx.msg.confirm({
            text: _('gallery.album_remove_confirm')
            ,url: GAL.config.connector_url
            ,params: {
                action: 'mgr/album/remove'
                ,id: this.tree.cm.activeNode.attributes.data.id
            }
            ,listeners: {
                'success': {fn:function(r) { this.tree.refreshParentNode(); },scope:this}
            }
        });
    }

    ,updateItem: function(btn,e) {
        this.windows.updateItem = MODx.load({
            xtype: 'gal-window-item-update'
            ,listeners: {
                'success': {fn:function() { this.tree.refreshParentNode(); },scope:this}
            }
        });
        this.windows.updateItem.setValues(this.data);
        this.windows.updateItem.show(e.target);
    }

    ,removeItem: function(btn,e) {
        MODx.msg.confirm({
            text: _('gallery.item_delete_confirm')
            ,url: GAL.config.connector_url
            ,params: {
                action: 'mgr/item/remove'
                ,id: this.data.id
            }
            ,listeners: {
                'success': {fn:function(r) { this.tree.refreshParentNode(); },scope:this}
            }
        });
    }

    ,uploadMultiItems: function(btn,e) {
        var r = {
            album: this.data.id
            ,active: true
        };
        if (!this.windows.uploadMultiItems) {
            this.windows.uploadMultiItems = MODx.load({
                xtype: 'gal-window-multi-item-upload'
                ,album: this.data.id
                ,record: r
                ,listeners: {
                    'success': {fn:function() { this.tree.refreshParentNode(); },scope:this}
                }
            });
        }
        this.windows.uploadMultiItems.fp.getForm().reset();
        this.windows.uploadMultiItems.setValues(r);
        this.windows.uploadMultiItems.show(e.target);
    }

    ,uploadItem: function(btn,e) {
        var r = {
            album: this.data.id
            ,active: true
        };
        if (!this.windows.uploadItem) {
            this.windows.uploadItem = MODx.load({
                xtype: 'gal-window-item-upload'
                ,record: r
                ,listeners: {
                    'success': {fn:function() { this.tree.refreshParentNode(); },scope:this}
                }
            });
        }
        this.windows.uploadItem.fp.getForm().reset();
        this.windows.uploadItem.setValues(r);
        this.windows.uploadItem.show(e.target);
    }

    ,batchUpload: function(btn,e) {
        var r = {
            album: this.data.id
            ,active: true
        };
        if (!this.windows.batchUpload) {
            this.windows.batchUpload = MODx.load({
                xtype: 'gal-window-batch-upload'
                ,record: r
                ,listeners: {
                    'success': {fn:function() { this.tree.refreshParentNode(); },scope:this}
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
            album: this.data.id
            ,active: true
        };
        if (!this.windows.zipUpload) {
            this.windows.zipUpload = MODx.load({
                xtype: 'gal-window-zip-upload'
                ,record: r
                ,listeners: {
                    'success': {fn:function() { this.tree.refreshParentNode(); },scope:this}
                }
            });
        } else {
            this.windows.zipUpload.fp.getForm().reset();
        }
        this.windows.zipUpload.setValues(r);
        this.windows.zipUpload.show(e.target);
    }

    ,handleDrop: function(t,dropEvent) {
        this.tree = t;
        var dropNode = dropEvent.dropNode;
        var target = dropEvent.target;
        if (!target.attributes.type) return false;
        if (target.attributes.type == 'gallery-album' && dropNode.attributes.type == 'gallery-item' && dropEvent.point != 'append') return false;
        if (dropNode.attributes.type == 'gallery-album' && target.attributes.type == 'gallery-item') return false;
        return true;
    }
});
Ext.reg('gal-tree-handler',galTreeHandlerClass);
var galTreeHandler = new galTreeHandlerClass();

var galItemDropHandlerClass = function(config) {
    config = config || {};
    Ext.apply(config,{
        id: 'gallery-item-drop-handler'
    });
    galItemDropHandlerClass.superclass.constructor.call(this,config);
};
Ext.extend(galItemDropHandlerClass,Ext.Component,{
    handle: function(target,opt) {
        var na = target.node.attributes;
        var cfg = {
            ddTargetEl: opt.ddTargetEl
            ,cfg: opt.cfg
            ,iframe: opt.cfg.iframe
            ,iframeEl: opt.cfg.iframeEl
            ,onInsert: opt.cfg.onInsert
            ,panel: opt.cfg.panel
            ,classKey: 'modSnippet'
            ,pk: GAL.snippetGalleryItem
            ,name: 'GalleryItem'
        };
        GAL.elProps = {
            id: na.data.id
        };
        MODx.loadInsertElement(cfg);
        setTimeout("galTreeWorkaround(GAL.elProps);",600);
        return true;
    }
});
var galItemDropHandler = new galItemDropHandlerClass();

var galAlbumDropHandlerClass = function(config) {
    config = config || {};
    Ext.apply(config,{
        id: 'gallery-album-drop-handler'
    });
    galAlbumDropHandlerClass.superclass.constructor.call(this,config);
};
Ext.extend(galAlbumDropHandlerClass,Ext.Component,{
    handle: function(target,opt) {
        var na = target.node.attributes;
        var cfg = {
            ddTargetEl: opt.ddTargetEl
            ,cfg: opt.cfg
            ,iframe: opt.cfg.iframe
            ,iframeEl: opt.cfg.iframeEl
            ,onInsert: opt.cfg.onInsert
            ,panel: opt.cfg.panel
            ,classKey: 'modSnippet'
            ,pk: GAL.snippetGallery
            ,name: 'Gallery'
        };
        GAL.elProps = {
            album: na.data.id
        };
        MODx.loadInsertElement(cfg);
        setTimeout("galTreeWorkaround(GAL.elProps);",600);
        return true;
    }
});
var galAlbumDropHandler = new galAlbumDropHandlerClass();

/* because im an idiot and forgot win show events in the insert element window */
function galTreeWorkaround(props) {
    var w = Ext.getCmp('modx-window-insert-element');
    w.modps = [];
    if (w) {
        for (var k in props) {
            var fld = Ext.getCmp('modx-iprop-'+k);
            if (fld) {
                fld.setValue(props[k]);
                w.changeProp(k);
            }
        }
    }
};

/* because im an idiot and prevented drag/drop of non-leaf nodes in modx core */
setTimeout('galleryFixLeafDrag();',1200);
function galleryFixLeafDrag() {
    var t = Ext.getCmp('modx-file-tree');
    if (t) {
        t.on('startdrag',function(t,n,e) {
            if (n.attributes.type == 'gallery-album') {
                n.attributes.leaf = true;
            }
        },this);
    }
}