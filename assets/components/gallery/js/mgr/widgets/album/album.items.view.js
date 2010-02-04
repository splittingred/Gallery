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
    
    ,deleteItem: function(btn,e) {
        var node = this.cm.activeNode;
        var data = this.lookup[node.id];
        if (!data) return false;
        
        MODx.msg.confirm({
            text: 'Are you sure you want to delete this item entirely? This is irreversible.'
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
                return data.size + " bytes";
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
                    ,'<b>'+'File Name'+':</b><span>{filename}</span>'
                    ,'<b>'+'File Size'+':</b><span>{filesize}</span>'
                    //,'<span>{downloads} '+_('downloads')+'</span>'                    
                    ,'<b>'+'Tags'+':</b><span>{tags}</span>'
                    //,'<b>'+_('license')+':</b><span>{license}</span>'
                ,'</div>'
            ,'</tpl>'
            ,'</div>'
        );
        this.templates.details.compile(); 
    }
    ,download: function(id) {
        var data = this.lookup['gal-item-'+id];
        if (!data) return false;
        /* do download */
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'download'
                ,info: data.location+'::'+data.signature
                ,provider: MODx.provider || 1
            }
            ,scope: this
            ,listeners: {
                'success': {fn:function(r) {
                    this.run();
                },scope:this}
            }
        });
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