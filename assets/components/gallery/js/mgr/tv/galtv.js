
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
        ,'<img src="'+this.phpthumb+'?src={src}&h={image_height}&w={image_width}&zc=0&far=C&fltr[]=rot|{rotate}&{other}" '
        ,' alt="{name}" id="tv'+config.tv+'-image" style="width: {image_width}px; height: {image_height}px" />'
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
                id: 'tv'+config.tv+'-preview-ct'
                ,html: '<div class="gal-tv-preview x-panel-body x-panel-body-noheader x-panel-body-noborder" id="tv'+config.tv+'-image-panel" style="height: auto; overflow: auto;">'
                    +'<div class="gal-crop-wrapper" id="tv'+config.tv+'-crop-wrapper" style="overflow: visible; width: '+config.data.image_width+'px; height: '+config.data.image_height+'px; position: absolute;"></div>'
                    +'<div id="tv'+config.tv+'-preview" style="height: auto; overflow: visible;">'+item+'</div>'
                    +'</div>'
                ,border: false
                ,autoScroll: true
                ,autoHeight: true
            }]
        },{
            columnWidth: .6
            ,layout: 'form'
            ,bodyStyle: 'padding: 45px 10px 10px;'
            ,border: false
            ,items: [{
                xtype: 'hidden'
                ,name: 'album'
                ,value: config.data.album
            },{
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
                    'changecomplete': {fn:function(sl,e) {
                        this.resetCropValues();
                        this.disableCrop();
                        this.resizeImage(sl,e,'sizer');
                    },scope:this}
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
                xtype: 'fieldset'
                ,collapsible: false
                ,collapsed: true
                ,checkboxToggle: true
                ,autoHeight: true
                ,title: _('gallery.crop_enable')
                ,id: 'tv'+config.tv+'-crop-opt'
                ,checkboxName: 'cropMode'
                ,onCheckClick: this.loadCropMode.createDelegate(this)
                ,items: [{
                    xtype: 'hidden'
                    ,name: 'cropCoords'
                    ,value: config.data.cropCoords || '{}'
                },{
                    xtype: 'statictextfield'
                    ,name: 'cropTop'
                    ,fieldLabel: _('gallery.crop_top')
                    ,value: config.data.cropTop || 0
                    ,allowBlank: true
                    ,anchor: '97%'
                    ,submitValue: true
                },{
                    xtype: 'statictextfield'
                    ,name: 'cropRight'
                    ,fieldLabel: _('gallery.crop_right')
                    ,value: config.data.cropRight || 0
                    ,allowBlank: true
                    ,anchor: '97%'
                    ,submitValue: true
                },{
                    xtype: 'statictextfield'
                    ,name: 'cropBottom'
                    ,fieldLabel: _('gallery.crop_bottom')
                    ,value: config.data.cropBottom || 0
                    ,allowBlank: true
                    ,anchor: '97%'
                    ,submitValue: true
                },{
                    xtype: 'statictextfield'
                    ,name: 'cropLeft'
                    ,fieldLabel: _('gallery.crop_left')
                    ,value: config.data.cropLeft || 0
                    ,allowBlank: true
                    ,anchor: '97%'
                    ,submitValue: true
                }]
            },{
                html: '&nbsp;' ,border: false
            }]
        }]
        ,renderTo: 'tv'+config.tv+'-form'
    });
    GAL.TV.superclass.constructor.call(this,config);
    if (config.data.cropCoords && config.data.cropMode == 'on') {
        this.enableCrop();
    }
};
Ext.extend(GAL.TV,MODx.FormPanel,{
    browser: null
    ,inCropMode: false

    ,loadCropMode: function(v) {
        v = this.getForm().getValues().cropMode;
        
        if (v == 'on') { 
            this.enableCrop();
        } else {this.disableCrop();}
    }

    ,disableCrop: function() {
        if (this.resizable) {
            this.resizable.el.hide();
        }
        Ext.getCmp('tv'+this.config.tv+'-crop-opt').collapse();
        this.resetCropValues();
        //this.cropMask.hide();
    }
    ,enableCrop: function() {
        Ext.getCmp('tv'+this.config.tv+'-crop-opt').expand();
        this.loadCropper();
        var img = Ext.get('tv'+this.config.tv+'-image');
        this.imageBox = img.getBox();
    }

    ,loadCropper: function() {
        if (this.inCropMode) {
            this.resizable.el.show();

            var cd = this.getForm().getValues() || this.config.data;
            this.resizable.resize4(cd.cropTop,cd.cropRight,cd.cropBottom,cd.cropLeft);
            //this.cropMask.show(Ext.get('tv'+this.config.tv+'-image'));
            return;
        }
        var cw = Ext.get('tv'+this.config.tv+'-crop-wrapper');
        var img = Ext.get('tv'+this.config.tv+'-image');
        this.imageBox = img.getBox();
        this.resizable = new Ext.Resizable(cw, {
            wrap: true
            ,minWidth: 0
            ,minHeight: 0
            ,dynamic: false
            ,transparent: false
            ,handles: 'all'
            ,draggable: false
            ,pinned: false
            ,style: 'overflow: visible;'
            ,constrainTo: 'tv'+this.config.tv+'-image'
            ,resize4 : function(t,r,b,l) {
                var imgBox = Ext.get('tv'+this.tv+'-image').getBox();
                var w = imgBox.width-(parseInt(r)+parseInt(l));
                var h = imgBox.height-(parseInt(b)+parseInt(t));

                this.el.setBox({
                    x: imgBox.x+parseInt(l)
                    ,y: imgBox.y+parseInt(t)
                    ,width: w
                    ,height: h
                },false,true);
                this.updateChildSize();
            }
            ,tv: this.config.tv
            ,imgBox: this.imageBox
            ,img: Ext.get('tv'+this.config.tv+'-image')
        });
        if (!this.inCropMode) {
            var cd = this.config.data;
            this.resizable.resize4(cd.cropTop,cd.cropRight,cd.cropBottom,cd.cropLeft);
        }
        this.resizable.on('resize',this.onCrop,this);
        this.resizable.getEl().setStyle('border','1px solid black');
        cw.select("div[id]").each(function(div) {
            if (div.hasClass("x-resizable-handle")) div.setOpacity(.75);
        });
        //this.cropMask = new Ext.Spotlight({animate: true});
        //this.cropMask.maskEl = this.resizable.getEl();
        //this.cropMask.show(img);
        this.inCropMode = true;
    }

    ,onCrop: function(res,w,h,e) {
        var cropBox = res.el.getBox();
        var imageBox = Ext.get('tv'+this.tv+'-image').getBox();

        var x1 = cropBox.x - imageBox.x;
        var y1 = cropBox.y - imageBox.y;
        var x2 = x1 + cropBox.width;
        var y2 = y1 + cropBox.height;

        var rr = imageBox.width - x2;
        var rb = imageBox.height - y2;

        this.cropCoords = {
            left: x1 > 0 ? x1 : 0
            ,right: x2 > 0 ? x2 : 0
            ,top: y1 > 0 ? y1 : 0
            ,bottom: y2 > 0 ? y2 : 0
            ,relRight: rr > 0 ? rr : 0
            ,relBottom: rb > 0 ? rb : 0
            ,on: true
        };
        var f = this.getForm();
        var vs = {
            cropCoords: this.cropCoords
            ,cropTop: this.cropCoords.top
            ,cropRight: this.cropCoords.relRight
            ,cropBottom: this.cropCoords.relBottom
            ,cropLeft: this.cropCoords.left
        };
        f.setValues(vs);
        this.setHiddenField(vs);
    }

    ,resetCropValues: function() {
        var f = this.getForm();
        f.findField('cropCoords').setValue('');
        f.findField('cropTop').setValue(0);
        f.findField('cropRight').setValue(0);
        f.findField('cropBottom').setValue(0);
        f.findField('cropLeft').setValue(0);
        this.setHiddenField({
            cropCoords: ''
            ,cropTop: 0
            ,cropRight: 0
            ,cropBottom: 0
            ,cropLeft: 0
        });
    }

    ,syncHidden: function(tf,nv,nm) {
        var v = tf.getValue();
        
        if (typeof v != 'number' && typeof v != 'boolean' && typeof v != 'object') {
            v = v.replace("'",'&apos;');
        } else if (typeof v == 'object') {
            v = v.value;
        }
        var n = tf.getName ? tf.getName() : nm;

        var vzs = {};
        vzs[n] = v+'';
        this.setHiddenField(vzs);

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
        this.resetCropValues();
    }
    ,loadBrowser: function(btn,e) {
        var alb = this.config.data.album || 0;
        if (this.browser === null) {
            this.browser = MODx.load({
                xtype: 'gal-browser'
                ,album: alb
                ,rootVisible: this.config.rootVisible || false
                ,listeners: {
                    'select': {fn: this.selectImage,scope:this}
                }
            });
        }
        this.browser.win.view.store.setBaseParam('album',alb);
        this.browser.win.view.store.load();
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
        data['rotate'] = 0;
        var f = this.getForm();

        f.setValues(data);
        this.setHiddenField(data);
        Ext.getCmp('tv'+this.config.tv+'-sizer').setValue(100);

        this.updateImage(data);
        this.disableCrop();
        Ext.getCmp('modx-panel-resource').markDirty();
    }

    ,setHiddenField: function(data) {
        var fld = Ext.get('tv'+this.config.tv);
        var js = Ext.decode(fld.dom.value);
        js = Ext.apply(js,data);
        fld.dom.value = Ext.encode(js);
        Ext.getCmp('modx-panel-resource').markDirty();
    }

    ,updateImage: function(vs) {
        if (!vs) return false;

        var p = Ext.get('tv'+this.config.tv+'-preview');
        if (p) {
            this.previewTpl.overwrite(p,vs);
        }

        var img = Ext.get('tv'+this.config.tv+'-image');
        this.imageBox = img.getBox();
        if (this.resizer) {this.resizer.imgBox = this.imageBox;}
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
            ,cropCoords: ''
            ,cropTop: 0
            ,cropRight: 0
            ,cropBottom: 0
            ,cropLeft: 0
            ,cropMode: 0
        });
        this.disableCrop();
        Ext.getCmp('modx-panel-resource').markDirty();
    }
});
Ext.reg('gal-panel-tv',GAL.TV);



Ext.Spotlight.prototype.createElements = function(){
    /** changed, as we do not want to hide the whole body, but just the containing panel */
    var bd = this.maskEl;

    this.right = bd.createChild({cls:'x-spotlight'});
    this.left = bd.createChild({cls:'x-spotlight'});
    this.top = bd.createChild({cls:'x-spotlight'});
    this.bottom = bd.createChild({cls:'x-spotlight'});

    this.all = new Ext.CompositeElement([this.right, this.left, this.top, this.bottom]);
};
