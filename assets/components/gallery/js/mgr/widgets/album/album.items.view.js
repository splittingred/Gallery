GAL.view.AlbumItems = function(config) {
    config = config || {};
    
    this._initTemplates();
    Ext.applyIf(config,{
        url: GAL.config.connector_url
        ,fields: ['id','name','description','mediatype','createdon','createdby','filename','filesize','thumbnail','image','image_width','image_height','tags','active','rank','menu']
        ,ident: 'galbit'
        ,id: 'gal-album-items-view'
        ,baseParams: {
            action: 'mgr/album/items/getList'
            ,album: config.album
        }
        ,loadingText: _('loading')
        ,tpl: this.templates.thumb
        ,enableDD: true
        ,listeners: {
            'dblclick': {fn: this.onDblClick ,scope:this }
        }
        ,prepareData: this.formatData.createDelegate(this)
    });
    GAL.view.AlbumItems.superclass.constructor.call(this,config);
    this.on('selectionchange',this.showDetails,this,{buffer: 100});
    this.addEvents({
        'sort': true
    });
    this.on('sort',this.onSort,this);
    
};
Ext.extend(GAL.view.AlbumItems,MODx.DataView,{
    templates: {}
    ,windows: {}
    
    ,onSort: function(o) {
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'mgr/album/items/sort'
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
    
    ,run: function(p) {
        var v = {};
        Ext.applyIf(v,this.store.baseParams);
        Ext.applyIf(v,p);
        this.pagingBar.changePage(1);
        this.store.load({
            params: v
        });
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
                ,'<div class="thumb-wrap" id="gal-item-{id}">'
                    ,'<div class="pbr-thumb">'
                        ,'<img src="{thumbnail}" title="{name}" class="thumb-img">'
                    ,'</div>'
                    ,'<span>{name}</span>'              
                ,'</div>'
            ,'</tpl>'
        );

        this.templates.thumb.compile();
                
        this.templates.details = new Ext.XTemplate(
            '<div class="details">'
            ,'<tpl for=".">'
                ,'<img src="{thumbnail}" alt="" width="100" height="80" onclick="Ext.getCmp(\'gal-album-items-view\').showScreenshot(\'{id}\'); return false;" style="cursor:pointer;" />'
                ,'<div class="details-info">'
                    ,'<h4>{name}</h4><br />'
                    ,'<p>{description}</p><br />'
                    ,'<b>'+_('gallery.file_name')+':</b><span>{filename}</span>'
                    ,'<b>'+_('gallery.file_size')+':</b><span>{filesize}</span>'                    
                    ,'<b>'+_('gallery.tags')+':</b><span>{tags}</span>'
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
});
Ext.reg('gal-view-album-items',GAL.view.AlbumItems);



GAL.window.UpdateItem = function(config) {
    config = config || {};
    this.ident = config.ident || 'gupdit'+Ext.id();
    Ext.applyIf(config,{
        title: _('gallery.item_update')
        ,id: this.ident
        ,height: 150
        ,width: 475
        ,url: GAL.config.connector_url
        ,action: 'mgr/item/update'
        ,fileUpload: true
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
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
            ,width: 300
        }]
    });
    GAL.window.UpdateItem.superclass.constructor.call(this,config);
};
Ext.extend(GAL.window.UpdateItem,MODx.Window);
Ext.reg('gal-window-item-update',GAL.window.UpdateItem);
