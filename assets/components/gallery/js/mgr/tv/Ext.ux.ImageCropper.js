
Ext.namespace('Ext.ux');

Ext.ux.ImageCropper = function(config){
    Ext.apply(this, config);
}
Ext.ux.ImageCropper.prototype = {
	
	src: "",		/** the to be cropped image */
	windowId: "",	/** the id the parent window */
	win: null, 		/** the parent window */
	panel: {},		/** the panel containing the cropper */
	panelId: "ImageCropperPanel",
	oriDim: {},		/** the original image size */
	cropRatio: 0,	/** the ratio original image size <> holder */
	initialCropDim: {
		width: 50,
		height: 50
	},				/** initial cropping size */
	cb: null, 		/** function to perform upon changes in cropping area */
	
	addEvents: function()	{
		
		/** run updateCropper when the parent window moves */
		this.win.on("move", function()	{
			this.updateCropper();
		},  this);
		
		this.win.on("resize", function()	{
			this.setSize(this.oriDim);
		},  this);
		
		/** run updateCropper when the cropper changes */
		this.resizable.on("resize", function()	{
			this.updateCropper();
		}, this);
		
		/** run updateCropper when the cropping area gets moved */
		var ImageCropper = this;	/** reference to self */
		this.resizable.dd.onDrag = function()	{
			ImageCropper.updateCropper();
		};
	},
	
	/**
	 * Initialises the ImageCropper
	 * 
	 * @return { ImageCropper Panel }
	 */
	init: function () {
		
		/** holder for the to be cropped image */
		this.panel = new Ext.Panel({
			id: this.panelId,
			autoWidth: true,
			autoHeight: true,
			border: false
		});
		
		/** render the panel, as we will be appending (true?) */
		this.panel.render(document.body);
		
		/** holder where the image rests in */
		this.imageHolder = Ext.get(Ext.DomHelper.append(this.panel.el, {
			tag: "img",
			cls: "ImageCropperImg"
		}));
		
		/** the resizable element, which we use as the to be cropped region */
		this.cropHelper = Ext.get(Ext.DomHelper.append(this.panel.el, {
			tag: "img",
			id: "custom",
			src: Ext.BLANK_IMAGE_URL,
			height: this.initialCropDim.width,
			width: this.initialCropDim.height
		}));
		
		/** make the above a resizable */
		this.resizable = new Ext.Resizable('custom', {
		    wrap: true,
		    minWidth: 25,
		    minHeight: 25,
		    dynamic: true,
		    transparent: true,
		    handles: 'all',
		    draggable: true,
			constrainTo: this.panelId
		});
		
		this.panel.el.select("div[id]").each(function(div)    {
		    if (div.hasClass("x-resizable-handle")) div.setOpacity(.75);
		});
		
		/** using the spotlight extension to mask */
		this.cropMask = new Ext.Spotlight({
			animate: false
		});
		
		return this.panel;
		
	},
	
	/**
	 * Preload the given src
	 * 
	 * @string src
	 */
	preloadImg: function ( src )	{
		var preload = new Image();
		/** nest the callee in the object, for handling the onload event */
		preload.ImageCropper = this;
		preload.onload = function(e)	{
			/** it's loaded; lets set the source */
			this.ImageCropper.setSrc( preload, true );
		};
		preload.src = src;
	},
	
	/**
	 * Set the source of the image holder
	 * 
	 * @param mixed img (src || { preloaded image })
	 * @boolean preloaded
	 */
	setSrc: function ( img, preloaded )	{
		
		/** if we already preloaded the image */
		if (preloaded) {
			/** we need to fetch the parent window once after rendering */
			if (!this.win) {
				this.win = Ext.getCmp(this.windowId); //this.panel.ownerCt.ownerCt;
				this.addEvents();
			}
			
			/** update the holder with the new image */
			this.imageHolder.set({
			    src: img.src
			});
			
			/** original image dimensions for cropping size calculation upon completion */
			this.oriDim = {
			    width: img.width,
			    height: img.height
			};
			
			/** update all sizes towards the new image */
			this.setSize(this.oriDim);
			
			/** resize the cropper */
			this.resizable.resizeTo(this.initialCropDim);
			
			/** realign the resizable */
			this.resizable.el.alignTo(this.imageHolder, "tl");
			
			this.updateCropper();
			
		}
		else this.preloadImg( img );
	},
	
	/**
	 * Set size
	 * @param {width, height} dim
	 * 
	 * NOTE: how we take the frame's dimensions in account to make sure we are calculating on an absolute size
	 */
	setSize: function( dim )	{
		
		var winDim = this.win.getSize();
		var winInnerWidth = winDim.width - this.win.getFrameWidth();
		var winInnerHeight = winDim.height - this.win.getFrameHeight();
		
		/** lets calculate the ratio, and thereby the dimensions */
		this.cropRatio = winInnerWidth / dim.width;
		var width = Math.round(dim.width * this.cropRatio);
		var height = Math.round(dim.height * this.cropRatio);
		
		dim = {
			width: width,
			height: height
		};
		
		var newWinDim = {
			width: width + this.win.getFrameWidth(),
			height: height + this.win.getFrameHeight()
		};
		
		/** update the image holder dimensions */
		this.imageHolder.setSize(dim);
		
		/** only update window size if it has really changed, to prevent triggering resize event */
		if (winInnerWidth != width || winInnerHeight != height) this.win.setSize( newWinDim );
		
		/** update panel dimensions before updating constraints */
		this.panel.el.setSize({
			width: width,
			height: height
		});
		
		this.updateCropper();
		
	},
	
	updateCropper: function()	{
		/** set draggable's constraints */
		this.updateDDConstraints();
		
		/** reset the coords */
		this.updateCoords();
		
		/** reposition the mask */
		this.cropMask.show(this.cropHelper);
	},
	
	/** sets draggable's constraints */
	updateDDConstraints: function()	{
		this.resizable.dd.constrainTo(this.panelId);
	},
	
	/**
	 * updates the current coordinates absolutely
	 */
	updateCoords: function()	{
		var x1, x2, y1, y2;
		/** to be cropped area */
		var cropBox = this.resizable.el.getBox();
		
		var imageBox = this.imageHolder.getBox();
		
		x1 = cropBox.x - imageBox.x;
		y1 = cropBox.y - imageBox.y;
		
		x2 = x1 + cropBox.width;
		y2 = y1 + cropBox.height;
		
		this.coords = {
			x1: Math.round(x1 / this.cropRatio),
			x2: Math.round(x2 / this.cropRatio),
			y1: Math.round(y1 / this.cropRatio),
			y2: Math.round(y2 / this.cropRatio)
		};
		
		/** inform callee with current state */
		if (typeof this.cb == "function") this.cb(this);
	}
	
};

/**
 * Ext overrides
 */
Ext.Spotlight.prototype.createElements = function(){
    //var bd = Ext.getBody();
/** changed, as we do not want to hide the whole body, but just the containing panel */
var bd = Ext.get("ImageCropperPanel");
	
    this.right = bd.createChild({cls:'x-spotlight'});
    this.left = bd.createChild({cls:'x-spotlight'});
    this.top = bd.createChild({cls:'x-spotlight'});
    this.bottom = bd.createChild({cls:'x-spotlight'});

    this.all = new Ext.CompositeElement([this.right, this.left, this.top, this.bottom]);
};

Ext.override(Ext.Resizable, {
    updateChildSize : function(){
        if(this.resizeChild){
            var el = this.el;
            var child = this.resizeChild;
            var adj = this.adjustments;
            if(el.dom.offsetWidth){
                var b = el.getSize(true);
                child.setSize(b.width+adj[0], b.height+adj[1]);
            }
            
            
            
            
            if(Ext.isIE){
                setTimeout(function(){
                    if(el.dom.offsetWidth){
                        var b = el.getSize(true);
                        child.setSize(b.width+adj[0], b.height+adj[1]);
                    }
                }, 10);
            }
        }
/** added */
this.fireEvent("resize", this);
    }
});