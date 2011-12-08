GAL.panel.Home = function(config) {
    config = config || {};
    Ext.apply(config,{
        border: false
        ,baseCls: 'modx-formpanel'
        ,cls: 'container'
        ,items: [{
            html: '<h2>'+_('gallery')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
        },{
            xtype: 'modx-tabs'
            ,defaults: { border: false ,autoHeight: true }
            ,border: true
            ,activeItem: 0
            ,hideMode: 'offsets'
            ,items: [{
                title: _('gallery.albums')
                ,items: [{
                    html: '<p>'+_('gallery.intro_msg')+'</p>'
                    ,border: false
                    ,bodyCssClass: 'panel-desc'
                },{
                    xtype: 'gal-tree-album'
                    ,cls: 'main-wrapper'
                }]
            }]
        }]
    });
    GAL.panel.Home.superclass.constructor.call(this,config);
};
Ext.extend(GAL.panel.Home,MODx.Panel);
Ext.reg('gal-panel-home',GAL.panel.Home);
