function eXcell_stree(cell){if(cell){this.cell=cell;this.grid=this.cell.parentNode.grid;if(!this.grid._sub_trees)return;this.yG=this.grid._sub_trees[cell.A];if(!this.yG)return;this.aij=this.yG[1];this.yG=this.yG[0]};this.getValue=function(){return this.cell.AB};this.setValue=function(ap){this.cell.AB=ap;ap=this.yG.fZ(this.cell.AB);this.dq((ap||"&nbsp;"),ap)};this.edit=function(){this.yG.parentObject.style.display='block';var kw=this.grid.fI(this.cell);this.yG.parentObject.style.top=kw[1]+"px";this.yG.parentObject.style.left=kw[0]+"px";this.yG.parentObject.style.position="absolute";var a=this.grid.bB;this.grid.bB=function(){};this.grid.bB=a};this.detach=function(){this.yG.parentObject.style.display='none';if(this.grid._sub_id!=null){var qw=this.cell.AB;this.setValue(this.yG.ah());this.grid._sub_id=null;return this.cell.AB!=qw}}};eXcell_stree.prototype=new gD;D.prototype.setSubTree=function(tree,ajn,aiu){if(!this._sub_trees)this._sub_trees=[];this._sub_trees[ajn]=[tree,aiu];tree.parentObject.style.display="none";var that=this;tree.parentObject.onclick=function(event){(event||window.event).cancelBubble=true;return false};tree.mY(function(id){that._sub_id=id;that.bB();return true});tree.adC=true};