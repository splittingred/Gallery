/*
 * Ext JS Library 2.2.1
 * Copyright(c) 2006-2009, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.DataView.DragSelector = function(cfg){
    cfg = cfg || {};
    var view, regions, proxy, tracker;
    var rs, bodyRegion, dragRegion = new Ext.lib.Region(0,0,0,0);
    var dragSafe = cfg.dragSafe === true;

    this.init = function(dataView){
        view = dataView;
        view.on('render', onRender);
    };

    function fillRegions(){
        rs = [];
        view.all.each(function(el){
            rs[rs.length] = el.getRegion();
        });
        bodyRegion = view.el.getRegion();
    }

    function cancelClick(){
        return false;
    }

    function onBeforeStart(e){
        return !dragSafe || e.target == view.el.dom;
    }

    function onStart(e){
        view.on('containerclick', cancelClick, view, {single:true});
        if(!proxy){
            proxy = view.el.createChild({cls:'x-view-selector'});
        }else{
            proxy.setDisplayed('block');
        }
        fillRegions();
        view.clearSelections();
    }

    function onDrag(e){
        var startXY = tracker.startXY;
        var xy = tracker.getXY();

        var x = Math.min(startXY[0], xy[0]);
        var y = Math.min(startXY[1], xy[1]);
        var w = Math.abs(startXY[0] - xy[0]);
        var h = Math.abs(startXY[1] - xy[1]);

        dragRegion.left = x;
        dragRegion.top = y;
        dragRegion.right = x+w;
        dragRegion.bottom = y+h;

        dragRegion.constrainTo(bodyRegion);
        proxy.setRegion(dragRegion);

        for(var i = 0, len = rs.length; i < len; i++){
            var r = rs[i], sel = dragRegion.intersect(r);
            if(sel && !r.selected){
                r.selected = true;
                view.select(i, true);
            }else if(!sel && r.selected){
                r.selected = false;
                view.deselect(i);
            }
        }
    }

    function onEnd(e){
        if(proxy){
            proxy.setDisplayed(false);
        }
    }

    function onRender(view){
        tracker = new Ext.dd.DragTracker({
            onBeforeStart: onBeforeStart,
            onStart: onStart,
            onDrag: onDrag,
            onEnd: onEnd
        });
        tracker.initEl(view.el);
    }
};


/**
 * Create a DragZone instance for our JsonView
 */
MODx.ImageDragZone = function(view, config){
    this.view = view;
    MODx.ImageDragZone.superclass.constructor.call(this, view.getEl(), config);
};
Ext.extend(MODx.ImageDragZone, Ext.dd.DragZone, {
    // We don't want to register our image elements, so let's 
    // override the default registry lookup to fetch the image 
    // from the event instead
    getDragData : function(e){
        var target = e.getTarget('.thumb-wrap');
        if(target){
            var view = this.view;
            if(!view.isSelected(target)){
                view.onClick(e);
            }
            var selNodes = view.getSelectedNodes();
            var dragData = {
                nodes: selNodes
            };
            if(selNodes.length == 1){
                dragData.ddel = target;
                dragData.single = true;
            }else{
                var div = document.createElement('div'); // create the multi element drag "ghost"
                div.className = 'multi-proxy';
                for(var i = 0, len = selNodes.length; i < len; i++){
                    div.appendChild(selNodes[i].firstChild.firstChild.cloneNode(true)); // image nodes only
                    if((i+1) % 3 == 0){
                        div.appendChild(document.createElement('br'));
                    }
                }
                var count = document.createElement('div'); // selected image count
                count.innerHTML = i + ' images selected';
                div.appendChild(count);
                
                dragData.ddel = div;
                dragData.multi = true;
            }
            return dragData;
        }
        return false;
    },

    // this method is called by the TreeDropZone after a node drop
    // to get the new tree node (there are also other way, but this is easiest)
    getTreeNode : function(){
        var treeNodes = [];
        var nodeData = this.view.getRecords(this.dragData.nodes);
        for(var i = 0, len = nodeData.length; i < len; i++){
            var data = nodeData[i].data;
            treeNodes.push(new Ext.tree.TreeNode({
                text: data.name,
                icon: '../view/'+data.url,
                data: data,
                leaf:true,
                cls: 'image-node'
            }));
        }
        return treeNodes;
    },
    
    // the default action is to "highlight" after a bad drop
    // but since an image can't be highlighted, let's frame it 
    afterRepair:function(){
        for(var i = 0, len = this.dragData.nodes.length; i < len; i++){
            Ext.fly(this.dragData.nodes[i]).frame('#8db2e3', 1);
        }
        this.dragging = false;    
    },
    
    // override the default repairXY with one offset for the margins and padding
    getRepairXY : function(e){
        if(!this.dragData.multi){
            var xy = Ext.Element.fly(this.dragData.ddel).getXY();
            xy[0]+=3;xy[1]+=3;
            return xy;
        }
        return false;
    }
});



MODx.SortableDataView = function(config) {
    Ext.apply(this, config || {}, {
        dragCls : 'x-view-sortable-drag',
        viewDragCls : 'x-view-sortable-dragging'
    });
    MODx.SortableDataView.superclass.constructor.call(this);
};
Ext.extend(MODx.SortableDataView,Ext.Component,{
    events: {
        'drop' : true
    }

    ,init: function(view) {
        window.sdv = this;
        this.view = view;
        view.on('render', this.onRender, this);
    }

    ,onRender: function() {

        var self        = this,
            v           = this.view,
            ds          = v.store,
            dd          = new Ext.dd.DragDrop(v.el),
            dragCls     = this.dragCls
            viewDragCls = this.viewDragCls;

        /* onMouseDown : if found an element, record it for future startDrag */
        dd.onMouseDown = function(e) {

            var t,idx,record;
            this.dragData = null;

            try {
                t = e.getTarget(v.itemSelector);
                idx = v.indexOf(t);
                record = ds.getAt(idx);

                /* Found a record to move */
                if (t && record) {
                    this.dragData = {
                        origIdx : idx,
                        lastIdx : idx,
                        record  : record
                    };
                    return true;
                }
            } catch (ex) { this.dragData = null; }
            return false;
        };

        /* startDrag: add dragCls to the element */
        dd.startDrag = function(x, y) {
            if (!this.dragData) { return false; }
            Ext.fly(v.getNode(this.dragData.origIdx)).addClass(dragCls);
            v.el.addClass(viewDragCls);
        };

        /* endDrag : remove dragCls and fire "drop" event */
        dd.endDrag = function(e) {
            if (!this.dragData) { return true; }
            var n = v.getNode(this.dragData.lastIdx);
            Ext.fly(n).removeClass(dragCls);
            v.el.removeClass(viewDragCls);
            Ext.fly(n).frame('#8db2e3', 1);
            //self.fireEvent('drop', this.dragData.origIdx,this.dragData.lastIdx, this.dragData.record);
            self.fireEvent('drop',{
                sourceIndex: this.dragData.origIdx
                ,targetIndex: this.dragData.lastIdx
                ,record: this.dragData.record
            });
            return true;
        };

        /* onDrag : if correct position, move record */
        dd.onDrag = function(e) {

            var t,idx,record,data = this.dragData;
            if (!data) { return false; }

            try {
                t = e.getTarget(v.itemSelector);
                idx = v.indexOf(t);
                record = ds.getAt(idx);

                if (idx === data.lastIdx) { return true; }

                /* found new position : move record and re-add dragCls */
                if (t && record) {
                    data.lastIdx = idx;
                    ds.remove(data.record);
                    ds.insert(idx, [data.record]);
                    Ext.fly(v.getNode(idx)).addClass(dragCls);
                    return true;
                }
            } catch (ex) { return false; }
            return false;
        };

        this.dd = dd;

    }
});

MODx.shortName = function(name) {
    if(name.length > 15){
        return name.substr(0, 12) + '...';
    }
    return name;
};
