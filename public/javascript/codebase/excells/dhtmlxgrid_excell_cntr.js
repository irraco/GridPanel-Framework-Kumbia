function eXcell_cntr(cell){this.cell=cell;this.grid=this.cell.parentNode.grid;if(!this.grid.RN&& !this.nh){this.grid.RN=true;if(this.grid.rV)this.grid.rV(function(id){this.JO(0)});this.grid.attachEvent("onBeforeSorting",function(){var that=this;window.setTimeout(function(){if(that.aC&& !that.nh)that.aC.JO(0)},1);return true})};this.edit=function(){};this.getValue=function(){return this.cell.parentNode.rowIndex};this.setValue=function(ap){this.cell.style.paddingRight="2px";var cell=this.cell;window.setTimeout(function(){var ap=cell.parentNode.rowIndex;if(cell.parentNode.grid.ew)ap=ap*1+(cell.parentNode.grid.ew-1)*cell.parentNode.grid.cu;if(ap<0)ap=cell.parentNode.grid.P.bP(cell.parentNode)+1;cell.innerHTML=ap;if(cell.parentNode.grid.aC)cell.parentNode.grid.aC.cells(cell.parentNode.idd,cell.A).dq(ap);cell=null},100)}};D.prototype.JO=function(ind){var i=0;if(this.ew)i=(this.ew-1)*this.cu;for(i=0;i<this.P.length;i++)this.P[i].cells[ind].innerHTML=i+1};eXcell_cntr.prototype=new gD;