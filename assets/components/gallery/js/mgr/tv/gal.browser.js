
GAL.Browser = function(config) {
    if (GAL.browserOpen) return false;
    GAL.browserOpen = true;

    config = config || {};
    Ext.applyIf(config,{
        onSelect: function(data) {}
        ,scope: this
        ,cls: 'modx-browser'
    });
    GAL.Browser.superclass.constructor.call(this,config);
    this.config = config;

    this.win = new GAL.BrowserWindow(config);
    this.win.reset();
};
Ext.extend(GAL.Browser,Ext.Component,{
    show: function(el) { this.win.show(el); }
    ,hide: function() { this.win.hide(); }
});
Ext.reg('gal-browser',GAL.Browser);

GAL.BrowserWindow = function(config) {
    config = config || {};
    this.ident = Ext.id();
    this.view = MODx.load({
        xtype: 'gal-view-album-items'
        ,cls: 'modx-pb-view-ct'
        ,album: config.album || 0
        ,listeners: {'select':{fn:this.onSelect,scope:this}}
    });
    this.view.pagingBar = new Ext.PagingToolbar({
        pageSize: 24
        ,store: this.view.store
        ,displayInfo: true
        ,autoLoad: true
    });
    this.tree = MODx.load({
        xtype: 'gal-tree-album'
        ,scope: this
        ,ident: this.ident
        ,rootVisible: config.rootVisible == null ? true : config.rootVisible
        ,listeners: {
            'click': {fn:function(node,e) {
                this.load(node.attributes.pk);
                return false;
                e.stopPropagation();
                e.preventDefault();
            },scope: this}
        }
        ,tbar: []
    });
    Ext.applyIf(config,{
        title: _('gallery.browser')
        ,cls: 'modx-pb-win'
        ,layout: 'border'
        ,minWidth: 500
        ,minHeight: 300
        ,width: '90%'
        ,height: 500
        ,modal: false
        ,closeAction: 'hide'
        ,border: false
        ,items: [{
            id: this.ident+'-browser-tree'
            ,cls: 'modx-pb-browser-tree'
            ,region: 'west'
            ,width: 250
            ,height: '100%'
            ,items: this.tree
            ,border: false
            ,autoScroll: true
        },{
            id: this.ident+'-browser-view'
            ,cls: 'modx-pb-view-ct'
            ,region: 'center'
            ,autoScroll: true
            ,width: 450
            ,items: this.view
            ,border: false
            ,tbar: this.getToolbar()
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
        ,buttons: [{
            id: this.ident+'-ok-btn'
            ,text: _('ok')
            ,handler: this.onSelect
            ,scope: this
        },{
            text: _('cancel')
            ,handler: this.hide
            ,scope: this
        }]
        ,keys: {
            key: 27
            ,handler: this.hide
            ,scope: this
        }
    });
    GAL.BrowserWindow.superclass.constructor.call(this,config);
    this.config = config;
    this.addEvents({
        'select': true
    });
};
Ext.extend(GAL.BrowserWindow,Ext.Window,{
    returnEl: null

    ,filter : function(){
        var filter = Ext.getCmp('filter');
        this.view.store.filter('name', filter.getValue(),true);
        this.view.select(0);
    }

    ,setReturn: function(el) {
        this.returnEl = el;
    }

    ,load: function(id) {
        this.view.run({album: id});
    }

    ,sortImages : function(){
        var v = Ext.getCmp('sortSelect').getValue();
        this.view.store.sort(v, v == 'name' ? 'asc' : 'desc');
        this.view.select(0);
    }

    ,reset: function(){
        if(this.rendered){
            Ext.getCmp('filter').reset();
            this.view.getEl().dom.scrollTop = 0;
        }
        this.view.store.clearFilter();
        this.view.select(0);
    }

    ,getToolbar: function() {
        return [{
            text: _('filter')+':'
        },{
            xtype: 'textfield'
            ,id: 'filter'
            ,selectOnFocus: true
            ,width: 100
            ,listeners: {
                'render': {fn:function(){
                    Ext.getCmp('filter').getEl().on('keyup', function(){
                        this.filter();
                    }, this, {buffer:500});
                }, scope:this}
            }
        }, ' ', '-', {
            text: _('sort_by')+':'
        }, {
            id: 'sortSelect'
            ,xtype: 'combo'
            ,typeAhead: true
            ,triggerAction: 'all'
            ,width: 100
            ,editable: false
            ,mode: 'local'
            ,displayField: 'desc'
            ,valueField: 'name'
            ,lazyInit: false
            ,value: 'name'
            ,store: new Ext.data.SimpleStore({
                fields: ['name', 'desc'],
                data : [['name',_('name')],['createdon',_('createdon')],['rank',_('rank')]]
            })
            ,listeners: {
                'select': {fn:this.sortImages, scope:this}
            }
        },'-',{
            icon: MODx.config.template_url+'images/restyle/icons/refresh.png'
            ,cls: 'x-btn-icon'
            ,tooltip: {text: _('tree_refresh')}
            ,handler: this.load
            ,scope: this
        }];
    }

    ,onSelect: function(data) {
        var selNode = this.view.getSelectedNodes()[0];
        var callback = this.config.onSelect || this.onSelectHandler;
        var lookup = this.view.lookup;
        var scope = this.config.scope;
        this.hide(this.config.animEl || null,function(){
            if(selNode && callback){
                var data = lookup[selNode.id];
                Ext.callback(callback,scope || this,[data]);
                this.fireEvent('select',data);
                if (window.top.opener) {
                    window.top.close();
                    window.top.opener.focus();
                }
            }
        },scope);
    }

    ,onSelectHandler: function(data) {
        Ext.get(this.returnEl).dom.value = unescape(data.url);
    }
});
Ext.reg('gal-browser-window',GAL.BrowserWindow);