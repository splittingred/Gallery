GAL.page.UpdateAlbum = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'gal-panel-album'
        ,buttons: [{
            text: _('save')
            ,id: 'gal-btn-save'
            ,cls: 'primary-button'
            ,process: 'mgr/album/update'
            ,method: 'remote'
            ,keys: [{
                key: 's'
                ,alt: true
                ,ctrl: true
            }]
        },'-',{
            text: _('gallery.back')
            ,id: 'gal-btn-back'
            ,handler: function() {
                location.href = '?a='+MODx.request.a+'&view=index';
            }
            ,scope: this
        }]
        ,components: [{
            xtype: 'gal-panel-album'
            ,renderTo: 'gal-panel-album-div'
            ,album: MODx.request.album
        }]
    }); 
    GAL.page.UpdateAlbum.superclass.constructor.call(this,config);
};
Ext.extend(GAL.page.UpdateAlbum,MODx.Component);
Ext.reg('gal-page-album-update',GAL.page.UpdateAlbum);