var GAL = function(config) {
    config = config || {};
    GAL.superclass.constructor.call(this,config);
};
Ext.extend(GAL,Ext.Component,{
    page:{},window:{},grid:{},tree:{},panel:{},combo:{},config: {},view: {}
});
Ext.reg('gallery',GAL);

GAL = new GAL();