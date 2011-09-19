GAL.view.AlbumItems = function(config) {
    config = config || {};
    
    this._initTemplates();
    Ext.applyIf(config,{
        url: GAL.config.connector_url
        ,fields: ['id','album','name','description','mediatype','url','createdon','createdby','filename','filesize','thumbnail','image','image_width','image_height','tags','active','rank','absoluteImage','relativeImage','menu']
        ,ident: 'galbit'
        ,id: 'gal-album-items-view'
        ,baseParams: {
            action: 'mgr/item/getList'
            ,album: config.album
        }
        ,loadingText: _('loading')
        ,tpl: this.templates.thumb
        ,enableDD: true
        ,multiSelect: true
        ,listeners: {}
        ,prepareData: this.formatData.createDelegate(this)
    });
    GAL.view.AlbumItems.superclass.constructor.call(this,config);
    this.on('selectionchange',this.showDetails,this,{buffer: 100});
    this.addEvents('sort','select');
    this.on('sort',this.onSort,this);
    this.on('dblclick',this.onDblClick,this);
};
Ext.extend(GAL.view.AlbumItems,MODx.DataView,{
    templates: {}
    ,windows: {}
    
    ,onSort: function(o) {
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'mgr/item/sort'
                ,album: this.config.album
                ,source: o.source.id
                ,target: o.target.id
            }
            ,listeners: {
                'success':{fn:function(r) {
                    this.run();
                },scope:this}
            }
        });
    }

    ,onDblClick: function(d,idx,n) {
        var node = this.getSelectedNodes()[0];
        if (!node) return false;
        
        if (this.config.inPanel) {
            this.cm.activeNode = node;
            this.updateItem(node,n);
        } else {
            var data = this.lookup[node.id];
            this.fireEvent('select',data);
        }
    }
    
    ,updateItem: function(btn,e) {
        var node = this.cm.activeNode;
        var data = this.lookup[node.id];
        if (!data) return false;
        
        var r = data;
        if (!this.windows.updateItem) {
            this.windows.updateItem = MODx.load({
                xtype: 'gal-window-item-update'
                ,listeners: {
                    'success': {fn:function() { this.run(); },scope:this}
                }
            });
        }
        this.windows.updateItem.fp.getForm().reset();
        this.windows.updateItem.setValues(r);
        this.windows.updateItem.show(e.target);
    }
    
    ,deleteItem: function(btn,e) {
        var node = this.cm.activeNode;
        var data = this.lookup[node.id];
        if (!data) return false;
        
        MODx.msg.confirm({
            text: _('gallery.item_delete_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/item/remove'
                ,id: data.id
            }
            ,listeners: {
                'success': {fn:function(r) { this.run(); },scope:this}
            }
        });
    }

    ,deleteMultiple: function(btn,e) {
        var recs = this.getSelectedRecords();
        if (!recs) return false;

        var ids = '';
        for (var i=0;i<recs.length;i++) {
            ids += ','+recs[i].id;
        }

        MODx.msg.confirm({
            text: _('gallery.item_delete_multiple_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/item/removeMultiple'
                ,ids: ids.substr(1)
            }
            ,listeners: {
                'success': {fn:function(r) { this.run(); },scope:this}
            }
        });
        return true;
    }
    
    ,run: function(p) {
        var v = {};
        Ext.apply(v,this.store.baseParams);
        Ext.apply(v,p);
        this.pagingBar.changePage(1);
        this.store.baseParams = v;
        this.store.load();
    }
        
    ,sortBy: function(sel) {
        var v = sel.getValue();
        this.store.baseParams.sorter = v;
        this.run();
        return true;
    }
    
    ,sortDir: function(sel) {
        var v = sel.getValue();
        this.store.baseParams.dir = v;
        this.run();
    }
    
    ,showDetails : function(){
        var selNode = this.getSelectedNodes();
        var detailEl = Ext.getCmp('gal-album-items-detail').body;
        if(selNode && selNode.length > 0){
            selNode = selNode[0];
            var data = this.lookup[selNode.id];
            if (data) {
                detailEl.hide();
                this.templates.details.overwrite(detailEl, data);
                detailEl.slideIn('l', {stopFx:true,duration:'.2'});
            }
        }else{
            detailEl.update('');
        }
    }
    
    ,formatData: function(data) {
        var formatSize = function(data){
            if(data.size < 1024) {
                return data.size + ' '+_('gallery.bytes');
            } else {
                return (Math.round(((data.size*10) / 1024))/10) + " KB";
            }
        };
        data.shortName = Ext.util.Format.ellipsis(data.name, 16);
        data.sizeString = formatSize(data);
        data.releasedon = new Date(data.releasedon).format("m/d/Y g:i a");
        this.lookup['gal-item-'+data.id] = data;
        return data;
    }
    ,_initTemplates: function() {
        this.templates.thumb = new Ext.XTemplate(
            '<tpl for=".">'
                ,'<div class="modx-pb-thumb-wrap <tpl if="!active">gal-item-inactive</tpl>" id="gal-item-{id}">'
                    ,'<div class="gal-item-thumb">'
                        ,'<img src="{thumbnail}" title="{name}" />'
                    ,'</div>'
                    ,'<span>{shortName}</span>'              
                ,'</div>'
            ,'</tpl>'
        );

        this.templates.thumb.compile();
                
        this.templates.details = new Ext.XTemplate(
            '<div class="details">'
            ,'<tpl for=".">'
                ,'<div class="modx-pb-detail-thumb"><img src="{thumbnail}" alt="{shortName}" onclick="Ext.getCmp(\'gal-album-items-view\').showScreenshot(\'{id}\'); return false;" /></div>'
                ,'<div class="modx-pb-details-info">'
                    ,'<span class="gal-detail-active">'
                        ,'<tpl if="active"><span class="green">'+_('gallery.active')+'</span></tpl>'
                        ,'<tpl if="!active"><span class="red">'+_('gallery.inactive')+'</span></tpl>'
                    ,'</span>'
                    ,'<h4>{shortName}</h4><br />'
                    ,'<tpl if="description"><p>{description}</p><br /></tpl>'
                    ,'<b>'+_('id')+':</b><span>{id}</span>'
                    ,'<b>'+_('gallery.file_name')+':</b><span>{filename}</span>'
                    ,'<b>'+_('gallery.file_size')+':</b><span>{filesize}</span>'
                    ,'<tpl if="tags"><b>'+_('gallery.tags')+':</b><span>{tags}</span></tpl>'
                    ,'<tpl if="url"><b>'+_('gallery.item_url')+':</b><span>{url}</span></tpl>'
                ,'</div>'
            ,'</tpl>'
            ,'</div>'
        );
        this.templates.details.compile(); 
    }
    ,showScreenshot: function(id) {
        var data = this.lookup['gal-item-'+id];
        if (!data) return false;
        
        if (!this.ssWin) {
            this.ssWin = new Ext.Window({
                layout:'fit'
                ,width: 600
                ,height: 450
                ,closeAction:'hide'
                ,plain: true
                ,items: [{
                    id: 'gal-item-ss'
                    ,html: ''
                }]
                ,buttons: [{
                    text: _('close')
                    ,handler: function() { this.ssWin.hide(); }
                    ,scope: this
                }]
            });
        }
        this.ssWin.show();
        this.ssWin.setSize(data.image_width,data.image_height);
        this.ssWin.center();
        this.ssWin.setTitle(data.name);
        Ext.get('gal-item-ss').update('<img src="'+data.image+'" alt="" onclick="Ext.getCmp(\'gal-album-items-view\').ssWin.hide();" />');
    }

    ,_showContextMenu: function(v,i,n,e) {
        e.preventDefault();
        var data = this.lookup[n.id];
        var m = this.cm;
        m.removeAll();
        var ct = this.getSelectionCount();
        if (ct == 1) {
            m.add({
                text: _('gallery.item_update')
                ,handler: this.updateItem
                ,scope: this
            });
            m.add('-');
            m.add({
                text: _('gallery.item_delete')
                ,handler: this.deleteItem
                ,scope: this
            });
            m.show(n,'tl-c?');
        } else if (ct > 1) {
            m.add({
                text: _('gallery.item_delete_multiple')
                ,handler: this.deleteMultiple
                ,scope: this
            });
            m.show(n,'tl-c?');
        }

        m.activeNode = n;
    }
});
Ext.reg('gal-view-album-items',GAL.view.AlbumItems);



GAL.window.UpdateItem = function(config) {
    config = config || {};
    this.ident = config.ident || 'gupdit'+Ext.id();
    Ext.applyIf(config,{
        title: _('gallery.item_update')
        ,id: this.ident
        ,height: 150
        ,width: '55%'
        ,url: GAL.config.connector_url
        ,action: 'mgr/item/update'
        ,fileUpload: true
        ,fields: [{
            xtype: 'statictextfield'
            ,name: 'id'
            ,fieldLabel: _('id')
            ,submitValue: true
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
            ,width: '85%'
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
            ,width: 300
        },{
            xtype: 'textfield'
            ,fieldLabel: _('gallery.item_url')
            ,description: _('gallery.item_url_desc')
            ,name: 'url'
            ,id: 'gal-'+this.ident+'-item-url'
            ,width: 300
        }]
    });
    GAL.window.UpdateItem.superclass.constructor.call(this,config);
    this.on('activate',function() {
        if (typeof Tiny != 'undefined') { MODx.loadRTE('gal-' + this.ident + '-description'); }
    });
};
Ext.extend(GAL.window.UpdateItem,MODx.Window);
Ext.reg('gal-window-item-update',GAL.window.UpdateItem);
