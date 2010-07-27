
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
    config.data = config.data || {gal_src:false};

    this.previewTpl = new Ext.XTemplate('<tpl for=".">'
        ,'<tpl if="gal_src">'
            ,'<img src="'+GAL.config.connector_url+'?action=web/phpthumb&src={gal_src}&h={gal_image_height}&w={gal_image_width}&zc=0&far=C&fltr[]=rot|{gal_rotate}&{gal_other}" '
            ,' alt="{gal_name}" class="{gal_class}" id="tv'+config.tv+'-image" style="width: {gal_image_width}px; height: {gal_image_height}px" />'
        ,'</tpl>'
        ,'</tpl>');
    this.previewTpl.compile();
    var item = this.previewTpl.applyTemplate(config.data);
    Ext.applyIf(config,{
        layout: 'column'
        ,autoHeight: true
        ,labelWidth: 160
        ,maxHeight: 400
        ,autoScroll: true
        ,border: false
        ,width: Ext.getCmp('modx-panel-resource').getWidth() - 300
        ,defaults: {
            border: false
        }
        ,forceLayout: true
        ,renderTo: 'tv'+config.tv+'-form'
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
                        ,handler: this.clearValues
                        ,scope: this
                    }]
                }]
            },{
                html: '<br />' ,border: false
            },{
                id: 'tv'+config.tv+'-preview-ct'
                ,html: '<div class="gal_tv-preview x-panel-body x-panel-body-noheader x-panel-body-noborder" id="tv'+config.tv+'-image-panel" style="height: auto; overflow: auto;">'
                    +'<div class="gal-crop-wrapper" id="tv'+config.tv+'-crop-wrapper" style="overflow: visible; width: '+config.data['gal_image_width']+'px; height: '+config.data['gal_image_height']+'px; position: absolute;"></div>'
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
                ,name: 'gal_id'
                ,id: 'tv'+config.tv+'-gal_id'
                ,value: config.data['gal_id'] || 0
            },{
                xtype: 'hidden'
                ,name: 'gal_album'
                ,id: 'tv'+config.tv+'-gal_album'
                ,value: config.data['gal_album'] || 0
            },{
                xtype: 'hidden'
                ,name: 'gal_src'
                ,id: 'tv'+config.tv+'-gal_src'
                ,value: config.data['gal_src'] || ''
            },{
                xtype: 'hidden'
                ,name: 'orig_width'
                ,id: 'tv'+config.tv+'-gal_orig_width'
                ,value: config.data['gal_orig_width'] || 0
                ,listeners: {'change':this.syncHidden,scope:this}
            },{
                xtype: 'hidden'
                ,name: 'gal_orig_height'
                ,id: 'tv'+config.tv+'-gal_orig_height'
                ,value: config.data['gal_orig_height'] || 0
                ,listeners: {'change':this.syncHidden,scope:this}
            },{
                xtype: 'textfield'
                ,name: 'gal_name'
                ,id: 'tv'+config.tv+'-gal_name'
                ,fieldLabel: _('gallery.title')
                ,value: config.data['gal_name'] || ''
                ,anchor: '97%'
                ,listeners: {'change':this.syncHidden,scope:this}
            },{
                xtype: 'textfield'
                ,name: 'gal_description'
                ,id: 'tv'+config.tv+'-gal_description'
                ,fieldLabel: _('gallery.alt_text')
                ,value: config.data['gal_description'] || ''
                ,anchor: '97%'
                ,listeners: {'change':this.syncHidden,scope:this}
            },{
                xtype: 'textfield'
                ,name: 'gal_class'
                ,id: 'tv'+config.tv+'-gal_class'
                ,fieldLabel: _('gallery.class')
                ,value: config.data['gal_class'] || ''
                ,anchor: '97%'
                ,listeners: {'change':this.syncHidden,scope:this}
            },{
                xtype: 'textfield'
                ,name: 'gal_image_width'
                ,id: 'tv'+config.tv+'-gal_image_width'
                ,fieldLabel: _('gallery.width')
                ,value: config.data['gal_image_width'] || 0
                ,anchor: '97%'
                ,listeners: {'change':this.syncHidden,scope:this}
            },{
                xtype: 'textfield'
                ,name: 'gal_image_height'
                ,id: 'tv'+config.tv+'-gal_image_height'
                ,fieldLabel: _('gallery.height')
                ,value: config.data['gal_image_height'] || 0
                ,anchor: '97%'
                ,listeners: {'change':this.syncHidden,scope:this}
            },{
                xtype: 'slider'
                ,id: 'tv'+config.tv+'-gal_sizer'
                ,fieldLabel: _('gallery.resize')
                ,name: 'gal_sizer'
                ,minValue: 5
                ,maxValue: 100
                ,value: config.data['gal_sizer'] || 100
                ,anchor: '97%'
                ,listeners: {
                    'changecomplete': {fn:function(sl,e) {
                        this.resetCropValues();
                        this.disableCrop();
                        this.resizeImage(sl,e,'gal_sizer');
                    },scope:this}
                }
                ,plugins: [new Ext.slider.Tip({
                    getText: function(thumb){
                        return String.format('<b>{0}%</b>', thumb.value);
                    }
                })]
            },{
                 xtype: 'radiogroup'
                ,id: 'tv'+config.tv+'-gal_rotate'
                ,fieldLabel: _('gallery.rotate')
                ,name: 'gal_rotate'
                ,items: [
                    {boxLabel: '0',name: 'gal_rotate',inputValue: 0,value: 0, checked: config.data['gal_rotate'] == 0}
                    ,{boxLabel: '90',name: 'gal_rotate',inputValue: 90,value: 90, checked: config.data['gal_rotate'] == 90}
                    ,{boxLabel: '180',name: 'gal_rotate',inputValue: 180,value: 180, checked: config.data['gal_rotate'] == 180}
                    ,{boxLabel: '270',name: 'gal_rotate',inputValue: 270,value: 270, checked: config.data['gal_rotate'] == 270}
                ]
                ,allowBlank: false
                ,value: config.data['gal_rotate'] || 0
                ,anchor: '97%'
                ,listeners: {'change':this.syncHidden,scope:this}
            },{
                xtype: 'textfield'
                ,id: 'tv'+config.tv+'-gal_other'
                ,name: 'gal_other'
                ,fieldLabel: _('gallery.other_opt')
                ,description: _('gallery.other_opt_desc')
                ,value: config.data['gal_other'] || ''
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
                    ,id: 'tv'+config.tv+'-gal_watermark-text'
                    ,fieldLabel: _('gallery.watermark_text')
                    ,description: _('gallery.watermark_text_desc')
                    ,name: 'gal_watermark-text'
                    ,allowBlank: true
                    ,anchor: '97%'
                    ,value: config.data['gal_watermark-text'] || ''
                    ,listeners: {'change':this.syncHidden,scope:this}
                },{
                    xtype: 'combo'
                    ,id: 'tv'+config.tv+'-gal_watermark-text-position'
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
                    ,name: 'gal_watermark-text-position'
                    ,allowBlank: true
                    ,anchor: '97%'
                    ,typeAhead: false
                    ,forceSelection: true
                    ,triggerAction: 'all'
                    ,selectOnFocus: true
                    ,editable: false
                    ,value: config.data['gal_watermark-text-position'] || ''
                    ,listeners: {'select':this.syncHidden,scope:this}
                }]
            },{
                xtype: 'fieldset'
                ,collapsible: false
                ,collapsed: true
                ,checkboxToggle: true
                ,autoHeight: true
                ,title: _('gallery.crop_enable')
                ,id: 'tv'+config.tv+'-gal_cropMode'
                ,checkboxName: 'gal_cropMode'
                ,onCheckClick: this.loadCropMode.createDelegate(this)
                ,items: [{
                    xtype: 'hidden'
                    ,name: 'gal_cropCoords'
                    ,anchor: '97%'
                    ,id: 'tv'+config.tv+'-gal_cropCoords'
                    ,value: config.data['gal_cropCoords'] ? Ext.encode(config.data['gal_cropCoords']) : '{}'
                },{
                    xtype: 'statictextfield'
                    ,name: 'gal_cropTop'
                    ,id: 'tv'+config.tv+'-gal_cropTop'
                    ,fieldLabel: _('gallery.crop_top')
                    ,value: config.data['gal_cropTop'] || 0
                    ,allowBlank: true
                    ,anchor: '97%'
                    ,submitValue: true
                },{
                    xtype: 'statictextfield'
                    ,name: 'gal_cropRight'
                    ,id: 'tv'+config.tv+'-gal_cropRight'
                    ,fieldLabel: _('gallery.crop_right')
                    ,value: config.data['gal_cropRight'] || 0
                    ,allowBlank: true
                    ,anchor: '97%'
                    ,submitValue: true
                },{
                    xtype: 'statictextfield'
                    ,name: 'gal_cropBottom'
                    ,id: 'tv'+config.tv+'-gal_cropBottom'
                    ,fieldLabel: _('gallery.crop_bottom')
                    ,value: config.data['gal_cropBottom'] || 0
                    ,allowBlank: true
                    ,anchor: '97%'
                    ,submitValue: true
                },{
                    xtype: 'statictextfield'
                    ,name: 'gal_cropLeft'
                    ,id: 'tv'+config.tv+'-gal_cropLeft'
                    ,fieldLabel: _('gallery.crop_left')
                    ,value: config.data['gal_cropLeft'] || 0
                    ,allowBlank: true
                    ,anchor: '97%'
                    ,submitValue: true
                }]
            },{
                html: '&nbsp;' ,border: false
            }]
        }]
    });
    GAL.TV.superclass.constructor.call(this,config);
    this.setValues(config.data);
    if (config.data['gal_cropCoords'] && config.data['gal_cropCoords'] != '{}') {
        this.enableCrop();
    }
};
Ext.extend(GAL.TV,MODx.Panel,{
    browser: null
    ,inCropMode: false

    ,fields: ['gal_id','gal_album','gal_src','gal_orig_width','gal_orig_height'
            ,'gal_name','gal_description','gal_class'
            ,'gal_image_width','gal_image_height','gal_slider','gal_rotate'
            ,'gal_watermark-text','gal_watermark-text-position','gal_other'
            ,'gal_cropCoords','gal_cropTop','gal_cropRight','gal_cropBottom','gal_cropLeft','gal_cropMode']

    ,setValues: function(vs) {
        var id;
        for (id in vs) {
            this.setValue(id,vs[id]);
        }
        return this;
    }
    ,setValue: function(k,v) {
        var f = this.findField(k);
        if (f && f.setValue) {
            var vs = {};
            vs[k] = v;
            this.setHiddenField(vs);
            return f.setValue(v);
        }
        return false;
    }
    ,getValue: function(k) {
        var v;
        var f = this.findField(k);
        if (f && f.getValue) {
            v = f.getValue();
            if (Ext.isObject(v)) {
                v = v.value;
            }
            return v;
        }
        return null;
    }
    ,getValues: function() {
        var f,i,v;
        var vs = {};
        for (i=0;i<this.fields.length;i++) {
            vs[this.fields[i]] = this.getValue(this.fields[i]);
        }
        return vs;
    }
    ,findField: function(k) {
        var f = Ext.getCmp('tv'+this.config.tv+'-'+k);
        return f ? f : null;
    }

    ,loadCropMode: function(v,b,c) {
        var el = Ext.get(b);
        
        if (el && el.dom && el.dom.checked) {
            this.enableCrop();
        } else {this.disableCrop();}
    }

    ,disableCrop: function() {
        if (this.resizable) {
            this.resizable.el.hide();
        }
        var fld = this.findField('gal_cropMode').collapse();
        this.resetCropValues();
        //this.cropMask.hide();
    }
    ,enableCrop: function() {
        this.findField('gal_cropMode').expand();
        this.loadCropper();
        var img = Ext.get('tv'+this.config.tv+'-image');
        this.imageBox = img.getBox();
    }

    ,loadCropper: function() {
        if (this.inCropMode) {
            this.resizable.el.show();

            var cd = this.getValues();
            this.resizable.resize4(cd['gal_cropTop'],cd['gal_cropRight'],cd['gal_cropBottom'],cd['gal_cropLeft']);
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
            var d = this.getValues();
            this.resizable.resize4(d['gal_cropTop'],d['gal_cropRight'],d['gal_cropBottom'],d['gal_cropLeft']);
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

    ,onCrop: function(res) {
        var cropBox = res.el.getBox();
        var imageBox = Ext.get('tv'+this.config.tv+'-image').getBox();

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
        var vs = {
            'gal_cropCoords': Ext.encode(this.cropCoords)
            ,'gal_cropTop': this.cropCoords.top
            ,'gal_cropRight': this.cropCoords.relRight
            ,'gal_cropBottom': this.cropCoords.relBottom
            ,'gal_cropLeft': this.cropCoords.left
        };
        this.setValues(vs);
    }

    ,resetCropValues: function() {
        this.setValue('gal_cropCoords','');
        this.setValue('gal_cropTop',0);
        this.setValue('gal_cropRight',0);
        this.setValue('gal_cropBottom',0);
        this.setValue('gal_cropLeft',0);
    }

    ,syncHidden: function(tf,nv,nm) {
        var v = tf.getValue();
        
        if (typeof v != 'number' && typeof v != 'boolean' && typeof v != 'object') {
            v = v.replace("'",'&apos;');
        } else if (typeof v == 'object') {
            v = v.value;
        }
        var n = tf.getName ? tf.getName() : nm;
        this.setValue(n,v+'');

        this.updateImage();
    }
    ,resizeImage: function(sl,e,name) {
        var nv = this.getValue(name);
        var w = this.getValue('gal_orig_width');
        var h = this.getValue('gal_orig_height');

        var nw = Ext.util.Format.round(w * (nv / 100),0);
        var nh = Ext.util.Format.round(h * (nv / 100),0);
        if (name == 'gal_sizer') {
            this.setValue('gal_image_width',nw);
            this.setValue('gal_image_height',nh);
            this.syncHidden(sl,nv,'gal_sizer');
        }
        
        this.updateImage();
        this.resetCropValues();
    }

    ,updateImage: function() {
        var vs = this.getValues();
        var p = Ext.get('tv'+this.config.tv+'-preview');
        if (p) {
            this.previewTpl.overwrite(p,vs);
        }

        this.imageBox = Ext.get('tv'+this.config.tv+'-image').getBox();
        if (this.resizer) {this.resizer.imgBox = this.imageBox;}
        return true;
    }
    
    ,loadBrowser: function(btn,e) {
        var alb = this.config.data['gal_album'] || 0;
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
        data['gal_url'] = data.absoluteImage;
        data['gal_src'] = data.absoluteImage;

        this.fireEvent('select',data);
        data['gal_id'] = data.id;
        data['gal_name'] = data.name;
        data['gal_description'] = data.description;
        data['gal_image_width'] = data.image_width;
        data['gal_image_height'] = data.image_height;
        data['gal_orig_width'] = data.image_width;
        data['gal_orig_height'] = data.image_height;
        data['gal_album'] = data.album;
        data['gal_watermark-text'] = '';
        data['gal_watermark-text-position'] = 'BL';
        data['gal_other'] = '';
        data['gal_rotate'] = 0;
        data['gal_sizer'] = 100;
        this.setValues(data);

        this.updateImage();
        this.disableCrop();
    }

    ,setHiddenField: function(data) {
        var fld = Ext.get('tv'+this.config.tv);
        var js = Ext.decode(fld.dom.value);
        js = Ext.apply(js,data);
        fld.dom.value = Ext.encode(js);
        Ext.getCmp('modx-panel-resource').markDirty();
    }


    ,clearValues: function() {
        this.setValues({
            'gal_id': 0
            ,'gal_name': ''
            ,'gal_description': ''
            ,'gal_class': ''
            ,'gal_image_width': 0
            ,'gal_image_height': 0
            ,'gal_slider': 100
            ,'gal_rotate': 0
            ,'gal_watermark-text': ''
            ,'gal_watermark-text-position': 'BL'
            ,'gal_other' : ''
            ,'gal_cropCoords': ''
            ,'gal_cropTop': 0
            ,'gal_cropRight': 0
            ,'gal_cropBottom': 0
            ,'gal_cropLeft': 0
            ,'gal_cropMode': 0
        });
        this.disableCrop();
        Ext.getCmp('modx-panel-resource').markDirty();
        Ext.get('tv'+this.config.tv+'-preview').update('&nbsp;');
        var fld = Ext.get('tv'+this.config.tv);
        fld.dom.value = Ext.encode({});
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
