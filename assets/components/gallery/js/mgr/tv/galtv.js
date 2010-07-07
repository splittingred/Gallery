
Ext.override(Ext.slider.Thumb, {
    onDrag: function(e) {
        var slider   = this.slider,
            index    = this.index,
            newValue = this.getNewValue();

        if (this.constrain) {
            var above = slider.thumbs[index + 1],
                below = slider.thumbs[index - 1];

            if (below != undefined && newValue <= below.value) newValue = below.value;
            if (above != undefined && newValue >= above.value) newValue = above.value;
        }

        slider.setValue(index, newValue, false);
        slider.fireEvent('drag', slider, e, this);
    }
});

GAL.TV = function(config) {
    config = config || {};
    this.previewTpl = new Ext.XTemplate('<tpl for=".">'
        ,'<div class="gal-tv-preview">'
        //'+MODx.config.connectors_url+'system/phpthumb.php?h=200&src=
        ,'<img src="{url}" alt="{name}" id="tv'+config.tv+'-image" style="width: {image_width}px; height: {image_height}px" />'
        ,'</div>'
        ,'</tpl>');
    this.previewTpl.compile();
    var item;
    if (config.data) {
        item = this.previewTpl.applyTemplate(config.data);
    }
    Ext.applyIf(config,{
        xtype: 'panel'
        ,layout: 'column'
        ,autoHeight: true
        ,labelWidth: 150
        ,maxHeight: 400
        ,autoScroll: true
        ,border: false
        ,width: Ext.getCmp('modx-panel-resource').getWidth() - 300
        ,defaults: {
            border: false
        }
        ,items: [{
            columnWidth: .4
            ,bodyStyle: 'padding: 10px;'
            ,items: [{
                xtype: 'button'
                ,text: _('gallery.choose_item')
                ,tv: config.tv
                ,handler: this.loadBrowser
                ,scope: this
            },{
                html: '<br />' ,border: false
            },{
                html: item || '&nbsp;'
                ,id: 'tv'+config.tv+'-preview'
                ,border: false
                ,height: 400
            }]
        },{
            columnWidth: .6
            ,layout: 'form'
            ,bodyStyle: 'padding: 45px 10px 10px;'
            ,items: [{
                xtype: 'hidden'
                ,name: 'orig_width'
                ,value: config.data.orig_width
                ,listeners: {'change':this.syncHidden,scope:this}
            },{
                xtype: 'hidden'
                ,name: 'orig_height'
                ,value: config.data.orig_height
                ,listeners: {'change':this.syncHidden,scope:this}
            },{
                xtype: 'textfield'
                ,name: 'name'
                ,fieldLabel: _('gallery.title')
                ,value: config.data.name || ''
                ,listeners: {'change':this.syncHidden,scope:this}
            },{
                xtype: 'textfield'
                ,name: 'description'
                ,fieldLabel: _('gallery.alt_text')
                ,value: config.data.description || ''
                ,listeners: {'change':this.syncHidden,scope:this}
            },{
                xtype: 'textfield'
                ,name: 'image_width'
                ,fieldLabel: _('gallery.width')
                ,value: config.data.image_width
                ,listeners: {'change':this.syncHidden,scope:this}
            },{
                xtype: 'textfield'
                ,name: 'image_height'
                ,fieldLabel: _('gallery.height')
                ,value: config.data.image_height
                ,listeners: {'change':this.syncHidden,scope:this}
            },{
                xtype: 'slider'
                ,id: 'tv'+config.tv+'-slider'
                ,minValue: 10
                ,maxValue: 100
                ,increment: 5
                ,value: config.data.slider || 100
                ,listeners: {
                    'drag': {fn:this.resizeImage,scope:this}
                    ,'changecomplete': {fn:this.resizeImage,scope:this}
                }
                ,plugins: [new Ext.slider.Tip({
                    getText: function(thumb){
                        return String.format('<b>{0}%</b>', thumb.value);
                    }
                })]
            },{
                html: '&nbsp;' ,border: false
            }]
        }]
        ,renderTo: 'tv'+config.tv+'-form'
    });
    GAL.TV.superclass.constructor.call(this,config);
};
Ext.extend(GAL.TV,MODx.FormPanel,{
    browser: null
    ,syncHidden: function(tf,nv) {
        var v = tf.getValue();
        if (typeof v != 'number' && typeof v != 'boolean') {
            v = v.replace("'",'&apos;');
        }
        var n = tf.getName ? tf.getName() : 'slider';

        var fld = Ext.get('tv'+this.config.tv);

        var js = Ext.decode(fld.dom.value);
        js[n] = v;
        fld.dom.value = Ext.encode(js);
        Ext.getCmp('modx-panel-resource').markDirty();
    }
    ,resizeImage: function(sl,e) {
        var nv = sl.getValue();
    
        var f = this.getForm();
        var wf = f.findField('orig_width');
        var hf = f.findField('orig_height');
        var w = wf.getValue();
        var h = hf.getValue();

        var nw = Ext.util.Format.round(w * (nv / 100),0);
        var nh = Ext.util.Format.round(h * (nv / 100),0);
        var img = Ext.get('tv'+this.config.tv+'-image');
        img.setWidth(nw);
        img.setHeight(nh);

        f.findField('image_width').setValue(nw);
        f.findField('image_height').setValue(nh);
        this.syncHidden(sl,nv);
        this.syncHidden(f.findField('image_width'),nw);
        this.syncHidden(f.findField('image_height'),nh);
        this.syncHidden(f.findField('orig_width'),w);
        this.syncHidden(f.findField('orig_height'),h);
    }
    ,loadBrowser: function(btn,e) {
        if (this.browser === null) {
            this.browser = MODx.load({
                xtype: 'gal-browser'
                ,rootVisible: this.config.rootVisible || false
                ,listeners: {
                    'select': {fn: function(data) {
                        data.url = data.absoluteImage;

                        this.fireEvent('select',data);
                        data.orig_width = data.image_width;
                        data.orig_height = data.image_height;
                        data.title = data.description;
                        var f = this.getForm();
                        
                        f.setValues(data);

                        var fld = Ext.get('tv'+this.config.tv);
                        var js = Ext.decode(fld.dom.value);
                        js = data;
                        fld.dom.value = Ext.encode(js);

                        Ext.getCmp('tv'+this.config.tv+'-slider').setValue(100);

                        var p = Ext.get('tv'+this.config.tv+'-preview');
                        if (p) {
                            this.previewTpl.overwrite(p,data);
                        }
                        Ext.getCmp('modx-panel-resource').markDirty();
                    },scope:this}
                }
            });
        }
        this.browser.show(btn);
    }
});
Ext.reg('gal-panel-tv',GAL.TV);


GAL.combo.Browser = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        width: 300
        ,editable: false
        ,typeAhead: false
        ,triggerAction: 'all'
        ,listeners: {
            'change': {fn:function(){Ext.getCmp('modx-panel-resource').markDirty();},scope:this}
        }
    });
    GAL.combo.Browser.superclass.constructor.call(this,config);
    this.config = config;
};
Ext.extend(GAL.combo.Browser,Ext.form.TriggerField,{
    browser: null

    ,onTriggerClick : function(){
        if (this.disabled){
            return false;
        }

        if (this.browser === null) {
            this.browser = MODx.load({
                xtype: 'gal-browser'
                ,prependPath: this.config.prependPath || null
                ,prependUrl: this.config.prependUrl || null
                ,hideFiles: this.config.hideFiles || false
                ,rootVisible: this.config.rootVisible || false
                ,listeners: {
                    'select': {fn: function(data) {
                        data.url = MODx.config.connectors_url+'system/phpthumb.php?h='+200+'&src='+data.absoluteImage;
                        this.setValue(data.id);
                        this.fireEvent('select',data);

                        var p = Ext.get('tv'+this.config.tv+'-preview');
                        if (p) {
                            this.previewTpl.overwrite(p,data);
                        }
                        Ext.getCmp('modx-panel-resource').markDirty();
                    },scope:this}
                }
            });
        }
        this.browser.show();
        return true;
    }

    ,onDestroy: function(){
        GAL.combo.Browser.superclass.onDestroy.call(this);
    }
});
Ext.reg('gal-combo-browser',GAL.combo.Browser);
