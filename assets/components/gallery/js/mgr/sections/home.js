GAL.page.Home = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'gal-panel-home'
            ,renderTo: 'gal-panel-home-div'
        }]
    }); 
    GAL.page.Home.superclass.constructor.call(this,config);
};
Ext.extend(GAL.page.Home,MODx.Component);
Ext.reg('gal-page-home',GAL.page.Home);