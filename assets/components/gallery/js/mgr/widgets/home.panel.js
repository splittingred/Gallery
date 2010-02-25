GAL.panel.Home = function(config) {
    config = config || {};
    Ext.apply(config,{
        border: false
        ,baseCls: 'modx-formpanel'
        ,items: [{
            html: '<h2>'+_('gallery')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
        },{
            xtype: 'modx-tabs'
            ,bodyStyle: 'padding: 1em'
            ,defaults: { border: false ,autoHeight: true }
            ,border: true
            ,activeItem: 0
            ,hideMode: 'offsets'
            ,items: [{
                title: 'Albums'
                ,items: [{
                    html: '<p>'+_('gallery.intro_msg')+'</p><br />'
                    ,border: false
                },{
                    xtype: 'gal-tree-album'
                }]
            }]
        }]
    });
    GAL.panel.Home.superclass.constructor.call(this,config);
};
Ext.extend(GAL.panel.Home,MODx.Panel);
Ext.reg('gal-panel-home',GAL.panel.Home);
