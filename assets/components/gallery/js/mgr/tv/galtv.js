
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
    config.data = config.data || {};
    this.phpthumb = MODx.config.url_scheme+MODx.config.http_host+MODx.config['gallery.phpthumb_url']+'phpThumb.php';
    this.previewTpl = new Ext.XTemplate('<tpl for=".">'
        ,'<div class="gal-tv-preview x-panel-body x-panel-body-noheader x-panel-body-noborder">'
        ,'<img src="'+this.phpthumb+'?src={src}&h={image_height}&w={image_width}&zc=0&far=C&fltr[]=rot|{rotate}&{other}" '
        ,' alt="{name}" id="tv'+config.tv+'-image" style="width: {image_width}px; height: {image_height}px" />'
        ,'</div>'
        ,'</tpl>');
    this.previewTpl.compile();
    var item;
    if (config.data.id) {
        item = this.previewTpl.applyTemplate(config.data);
    }
    Ext.applyIf(config,{
        xtype: 'panel'
        ,layout: 'column'
        ,autoHeight: true
        ,labelWidth: 160
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
            ,border: false
            ,items: [{
                layout: 'column'
                ,border: false
                ,items: [{
                    columnWidth: .5
                    ,border: false
                    ,items: [{
                        xtype: 'button'
                        ,text: _('gallery.choose_item')
                        ,tv: config.tv
                        ,handler: this.loadBrowser
                        ,scope: this
                    }]
                },{
                    columnWidth: .5
                    ,border: false
                    ,items: [{
                        xtype: 'button'
                        ,text: _('gallery.clear_image')
                        ,tv: config.tv
                        ,handler: this.clearImage
                        ,scope: this
                    }]
                }]
            },{
                html: '<br />' ,border: false
            },{
                html: item || '&nbsp;'
                ,id: 'tv'+config.tv+'-preview'
                ,border: true
                ,height: 200
            }]
        },{
            columnWidth: .6
            ,layout: 'form'
            ,bodyStyle: 'padding: 45px 10px 10px;'
            ,border: false
            ,items: [{
                xtype: 'hidden'
                ,name: 'src'
                ,value: config.data.src
            },{
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
                ,anchor: '97%'
                ,listeners: {'change':this.syncHidden,scope:this}
            },{
                xtype: 'textfield'
                ,name: 'description'
                ,fieldLabel: _('gallery.alt_text')
                ,value: config.data.description || ''
                ,anchor: '97%'
                ,listeners: {'change':this.syncHidden,scope:this}
            },{
                xtype: 'textfield'
                ,name: 'image_width'
                ,fieldLabel: _('gallery.width')
                ,value: config.data.image_width
                ,anchor: '97%'
                ,listeners: {'change':this.syncHidden,scope:this}
            },{
                xtype: 'textfield'
                ,name: 'image_height'
                ,fieldLabel: _('gallery.height')
                ,value: config.data.image_height
                ,anchor: '97%'
                ,listeners: {'change':this.syncHidden,scope:this}
            },{
                xtype: 'slider'
                ,id: 'tv'+config.tv+'-sizer'
                ,fieldLabel: _('gallery.resize')
                ,name: 'sizer'
                ,minValue: 5
                ,maxValue: 100
                ,value: config.data.slider || 100
                ,anchor: '97%'
                ,listeners: {
                    'changecomplete': {fn:function(sl,e) { this.resizeImage(sl,e,'sizer'); },scope:this}
                }
                ,plugins: [new Ext.slider.Tip({
                    getText: function(thumb){
                        return String.format('<b>{0}%</b>', thumb.value);
                    }
                })]
            },{
                 xtype: 'radiogroup'
                ,id: 'tv'+config.tv+'-rotate'
                ,fieldLabel: _('gallery.rotate')
                ,name: 'rotate'
                ,items: [
                    {boxLabel: '0',name: 'rotate',inputValue: 0,value: 0}
                    ,{boxLabel: '90',name: 'rotate',inputValue: 90,value: 90}
                    ,{boxLabel: '180',name: 'rotate',inputValue: 180,value: 180}
                    ,{boxLabel: '270',name: 'rotate',inputValue: 270,value: 270}
                ]
                ,allowBlank: false
                ,value: config.data.rotate || 0
                ,anchor: '97%'
                ,listeners: {'change':this.syncHidden,scope:this}
            },{
                xtype: 'textfield'
                ,id: 'tv'+config.tv+'-other'
                ,name: 'other'
                ,fieldLabel: _('gallery.other_opt')
                ,description: _('gallery.other_opt_desc')
                ,value: config.data.other || ''
                ,anchor: '97%'
                ,listeners: {'change':this.syncHidden,scope:this}
            },{
                xtype: 'fieldset'
                ,collapsible: true
                ,collapsed: true
                ,autoHeight: true
                ,title: _('gallery.watermark_options')
                ,items: [{
                    html: '<p>'+_('gallery.watermark_options_desc')+'</p>'
                    ,border: false
                },{
                    xtype: 'textfield'
                    ,id: 'tv'+config.tv+'-watermark-text'
                    ,fieldLabel: _('gallery.watermark_text')
                    ,description: _('gallery.watermark_text_desc')
                    ,name: 'watermark-text'
                    ,allowBlank: true
                    ,anchor: '97%'
                    ,value: config.data['watermark-text']
                    ,listeners: {'change':this.syncHidden,scope:this}
                },{
                    xtype: 'combo'
                    ,id: 'tv'+config.tv+'-watermark-text-position'
                    ,fieldLabel: _('gallery.watermark_text_position')
                    ,store: [
                        ['BR',_('gallery.pos_br')]
                        ,['BL',_('gallery.pos_bl')]
                        ,['TR',_('gallery.pos_tr')]
                        ,['TL',_('gallery.pos_tl')]
                        ,['C',_('gallery.pos_c')]
                        ,['R',_('gallery.pos_r')]
                        ,['L',_('gallery.pos_l')]
                        ,['T',_('gallery.pos_t')]
                        ,['B',_('gallery.pos_b')]
                    ]
                    ,description: _('gallery.watermark_text_position_desc')
                    ,name: 'watermark-text-position'
                    ,allowBlank: true
                    ,anchor: '97%'
                    ,typeAhead: false
                    ,forceSelection: true
                    ,triggerAction: 'all'
                    ,selectOnFocus: true
                    ,editable: false
                    ,value: config.data['watermark-text-position']
                    ,listeners: {'select':this.syncHidden,scope:this}
                }]
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
    ,syncHidden: function(tf,nv,nm) {
        var v = tf.getValue();
        
        if (typeof v != 'number' && typeof v != 'boolean' && typeof v != 'object') {
            v = v.replace("'",'&apos;');
        } else if (typeof v == 'object') {
            v = v.value;
        }
        var n = tf.getName ? tf.getName() : nm;
        var fld = Ext.get('tv'+this.config.tv);
        var js = Ext.decode(fld.dom.value);

        js[n] = ''+v;
        fld.dom.value = Ext.encode(js);
        Ext.getCmp('modx-panel-resource').markDirty();

        var vs = this.getForm().getValues();
        this.updateImage(vs);

    }
    ,resizeImage: function(sl,e,name) {
        var vs = this.getForm().getValues();

        var nv = Ext.getCmp('tv'+this.config.tv+'-'+name).getValue();
        var f = this.getForm();
        var wf = f.findField('orig_width');
        var hf = f.findField('orig_height');
        var w = wf.getValue();
        var h = hf.getValue();

        var nw = Ext.util.Format.round(w * (nv / 100),0);
        var nh = Ext.util.Format.round(h * (nv / 100),0);
        if (name == 'sizer') {
            f.findField('image_width').setValue(nw);
            f.findField('image_height').setValue(nh);
            this.syncHidden(sl,nv,'slider');
            this.syncHidden(f.findField('image_width'),nw);
            this.syncHidden(f.findField('image_height'),nh);
            this.syncHidden(f.findField('orig_width'),w);
            this.syncHidden(f.findField('orig_height'),h);
        }
        
        var p = Ext.get('tv'+this.config.tv+'-preview');
        if (p) {
            vs.image_height = nh;
            vs.image_width = nw;
            this.updateImage(vs);
        }
    }
    ,loadBrowser: function(btn,e) {
        if (this.browser === null) {
            this.browser = MODx.load({
                xtype: 'gal-browser'
                ,rootVisible: this.config.rootVisible || false
                ,listeners: {
                    'select': {fn: this.selectImage,scope:this}
                }
            });
        }
        this.browser.show(btn);
    }
    ,selectImage: function(data) {
        data.url = data.absoluteImage;

        this.fireEvent('select',data);
        data.orig_width = data.image_width;
        data.orig_height = data.image_height;
        data.title = data.description;
        data.src = data.url;
        data['watermark-text'] = '';
        data['watermark-text-position'] = 'BL';
        data['other'] = '';
        var f = this.getForm();

        f.setValues(data);

        var fld = Ext.get('tv'+this.config.tv);
        var js = Ext.decode(fld.dom.value);
        js = data || {};
        fld.dom.value = Ext.encode(js);

        Ext.getCmp('tv'+this.config.tv+'-sizer').setValue(100);

        this.updateImage(data);
        Ext.getCmp('modx-panel-resource').markDirty();
    }

    ,updateImage: function(vs) {
        if (!vs) return false;

        var p = Ext.get('tv'+this.config.tv+'-preview');
        if (p) {
            this.previewTpl.overwrite(p,vs);
        }
        return true;
    }
    ,clearImage: function() {
        var fld = Ext.get('tv'+this.config.tv);
        fld.dom.value = Ext.encode({});
        Ext.get('tv'+this.config.tv+'-preview').update('&nbsp;');
        var f = this.getForm();
        f.reset();
        f.setValues({
            name: ''
            ,description: ''
            ,image_width: 0
            ,image_height: 0
            ,slider: 100
            ,rotate: 0
            ,'watermark-text': ''
            ,'watermark-text-position': 'BL'
            ,other : ''
        });
        Ext.getCmp('modx-panel-resource').markDirty();
    }
});
Ext.reg('gal-panel-tv',GAL.TV);

