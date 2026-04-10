function camvas(ctx,callback){var self=this
this.ctx=ctx
this.callback=callback
var streamContainer=document.createElement('div')
this.video=document.createElement('video')
this.video.setAttribute('autoplay','1')
this.video.setAttribute('playsinline','1')
this.video.setAttribute('width',1)
this.video.setAttribute('height',1)
streamContainer.appendChild(this.video)
document.body.appendChild(streamContainer)
navigator.mediaDevices.getUserMedia({video:!0,audio:!1}).then(function(stream){self.video.srcObject=stream
self.update()},function(err){throw err})
this.update=function(){var self=this
var last=Date.now()
var loop=function(){var dt=Date.now()-last
self.callback(self.video,dt)
last=Date.now()
requestAnimationFrame(loop)}
requestAnimationFrame(loop)}}
lploc={}
lploc.unpack_localizer=function(bytes){const dview=new DataView(new ArrayBuffer(4));let p=0;dview.setUint8(0,bytes[p+0]),dview.setUint8(1,bytes[p+1]),dview.setUint8(2,bytes[p+2]),dview.setUint8(3,bytes[p+3]);const nstages=dview.getInt32(0,!0);p=p+4;dview.setUint8(0,bytes[p+0]),dview.setUint8(1,bytes[p+1]),dview.setUint8(2,bytes[p+2]),dview.setUint8(3,bytes[p+3]);const scalemul=dview.getFloat32(0,!0);p=p+4;dview.setUint8(0,bytes[p+0]),dview.setUint8(1,bytes[p+1]),dview.setUint8(2,bytes[p+2]),dview.setUint8(3,bytes[p+3]);const ntreesperstage=dview.getInt32(0,!0);p=p+4;dview.setUint8(0,bytes[p+0]),dview.setUint8(1,bytes[p+1]),dview.setUint8(2,bytes[p+2]),dview.setUint8(3,bytes[p+3]);const tdepth=dview.getInt32(0,!0);p=p+4;const tcodes_ls=[];const tpreds_ls=[];for(let i=0;i<nstages;++i){for(let j=0;j<ntreesperstage;++j){Array.prototype.push.apply(tcodes_ls,bytes.slice(p,p+4*Math.pow(2,tdepth)-4));p=p+4*Math.pow(2,tdepth)-4;for(let k=0;k<Math.pow(2,tdepth);++k)
for(let l=0;l<2;++l){dview.setUint8(0,bytes[p+0]),dview.setUint8(1,bytes[p+1]),dview.setUint8(2,bytes[p+2]),dview.setUint8(3,bytes[p+3]);tpreds_ls.push(dview.getFloat32(0,!0));p=p+4}}}
const tcodes=new Int8Array(tcodes_ls);const tpreds=new Float32Array(tpreds_ls);function loc_fun(r,c,s,pixels,nrows,ncols,ldim){let root=0;const pow2tdepth=Math.pow(2,tdepth)>>0;for(let i=0;i<nstages;++i){let dr=0.0,dc=0.0;for(let j=0;j<ntreesperstage;++j){let idx=0;for(var k=0;k<tdepth;++k){const r1=Math.min(nrows-1,Math.max(0,(256*r+tcodes[root+4*idx+0]*s)>>8));const c1=Math.min(ncols-1,Math.max(0,(256*c+tcodes[root+4*idx+1]*s)>>8));const r2=Math.min(nrows-1,Math.max(0,(256*r+tcodes[root+4*idx+2]*s)>>8));const c2=Math.min(ncols-1,Math.max(0,(256*c+tcodes[root+4*idx+3]*s)>>8));idx=2*idx+1+(pixels[r1*ldim+c1]>pixels[r2*ldim+c2])}
const lutidx=2*(ntreesperstage*pow2tdepth*i+pow2tdepth*j+idx-(pow2tdepth-1))
dr+=tpreds[lutidx+0];dc+=tpreds[lutidx+1];root+=4*pow2tdepth-4}
r=r+dr*s;c=c+dc*s;s=s*scalemul}
return[r,c]}
function loc_fun_with_perturbs(r,c,s,nperturbs,image){const rows=[],cols=[];for(let i=0;i<nperturbs;++i){const _s=s*(0.925+0.15*Math.random());let _r=r+s*0.15*(0.5-Math.random());let _c=c+s*0.15*(0.5-Math.random());[_r,_c]=loc_fun(_r,_c,_s,image.pixels,image.nrows,image.ncols,image.ldim)
rows.push(_r)
cols.push(_c)}
rows.sort()
cols.sort()
return[rows[Math.round(nperturbs/2)],cols[Math.round(nperturbs/2)]]}
return loc_fun_with_perturbs}
pico={}
pico.unpack_cascade=function(bytes){const dview=new DataView(new ArrayBuffer(4));let p=8;dview.setUint8(0,bytes[p+0]),dview.setUint8(1,bytes[p+1]),dview.setUint8(2,bytes[p+2]),dview.setUint8(3,bytes[p+3]);const tdepth=dview.getInt32(0,!0);p=p+4
dview.setUint8(0,bytes[p+0]),dview.setUint8(1,bytes[p+1]),dview.setUint8(2,bytes[p+2]),dview.setUint8(3,bytes[p+3]);const ntrees=dview.getInt32(0,!0);p=p+4
const tcodes_ls=[];const tpreds_ls=[];const thresh_ls=[];for(let t=0;t<ntrees;++t){Array.prototype.push.apply(tcodes_ls,[0,0,0,0]);Array.prototype.push.apply(tcodes_ls,bytes.slice(p,p+4*Math.pow(2,tdepth)-4));p=p+4*Math.pow(2,tdepth)-4;for(let i=0;i<Math.pow(2,tdepth);++i){dview.setUint8(0,bytes[p+0]),dview.setUint8(1,bytes[p+1]),dview.setUint8(2,bytes[p+2]),dview.setUint8(3,bytes[p+3]);tpreds_ls.push(dview.getFloat32(0,!0));p=p+4}
dview.setUint8(0,bytes[p+0]),dview.setUint8(1,bytes[p+1]),dview.setUint8(2,bytes[p+2]),dview.setUint8(3,bytes[p+3]);thresh_ls.push(dview.getFloat32(0,!0));p=p+4}
const tcodes=new Int8Array(tcodes_ls);const tpreds=new Float32Array(tpreds_ls);const thresh=new Float32Array(thresh_ls);function classify_region(r,c,s,pixels,ldim){r=256*r;c=256*c;let root=0;let o=0.0;const pow2tdepth=Math.pow(2,tdepth)>>0;for(let i=0;i<ntrees;++i){let idx=1;for(let j=0;j<tdepth;++j)
idx=2*idx+(pixels[((r+tcodes[root+4*idx+0]*s)>>8)*ldim+((c+tcodes[root+4*idx+1]*s)>>8)]<=pixels[((r+tcodes[root+4*idx+2]*s)>>8)*ldim+((c+tcodes[root+4*idx+3]*s)>>8)]);o=o+tpreds[pow2tdepth*i+idx-pow2tdepth];if(o<=thresh[i])
return-1;root+=4*pow2tdepth}
return o-thresh[ntrees-1]}
return classify_region}
pico.run_cascade=function(image,classify_region,params){const pixels=image.pixels;const nrows=image.nrows;const ncols=image.ncols;const ldim=image.ldim;const shiftfactor=params.shiftfactor;const minsize=params.minsize;const maxsize=params.maxsize;const scalefactor=params.scalefactor;let scale=minsize;const detections=[];while(scale<=maxsize){const step=Math.max(shiftfactor*scale,1)>>0;const offset=(scale/2+1)>>0;for(let r=offset;r<=nrows-offset;r+=step)
for(let c=offset;c<=ncols-offset;c+=step){const q=classify_region(r,c,scale,pixels,ldim);if(q>0.0)
detections.push([r,c,scale,q]);}
scale=scale*scalefactor}
return detections}
pico.cluster_detections=function(dets,iouthreshold){dets=dets.sort(function(a,b){return b[3]-a[3]});function calculate_iou(det1,det2){const r1=det1[0],c1=det1[1],s1=det1[2];const r2=det2[0],c2=det2[1],s2=det2[2];const overr=Math.max(0,Math.min(r1+s1/2,r2+s2/2)-Math.max(r1-s1/2,r2-s2/2));const overc=Math.max(0,Math.min(c1+s1/2,c2+s2/2)-Math.max(c1-s1/2,c2-s2/2));return overr*overc/(s1*s1+s2*s2-overr*overc)}
const assignments=new Array(dets.length).fill(0);const clusters=[];for(let i=0;i<dets.length;++i){if(assignments[i]==0){let r=0.0,c=0.0,s=0.0,q=0.0,n=0;for(let j=i;j<dets.length;++j)
if(calculate_iou(dets[i],dets[j])>iouthreshold){assignments[j]=1;r=r+dets[j][0];c=c+dets[j][1];s=s+dets[j][2];q=q+dets[j][3];n=n+1}
clusters.push([r/n,c/n,s/n,q])}}
return clusters}
pico.instantiate_detection_memory=function(size){let n=0;const memory=[];for(let i=0;i<size;++i)
memory.push([]);function update_memory(dets){memory[n]=dets;n=(n+1)%memory.length;dets=[];for(i=0;i<memory.length;++i)
dets=dets.concat(memory[i]);return dets}
return update_memory}
function camvas(ctx,callback){var self=this
this.ctx=ctx
this.callback=callback
var streamContainer=document.createElement('div')
this.video=document.createElement('video')
this.video.setAttribute('autoplay','1')
this.video.setAttribute('playsinline','1')
this.video.setAttribute('width',1)
this.video.setAttribute('height',1)
streamContainer.appendChild(this.video)
document.body.appendChild(streamContainer)
navigator.mediaDevices.getUserMedia({video:!0,audio:!1}).then(function(stream){self.video.srcObject=stream
self.update()},function(err){throw err})
this.update=function(){var self=this
var last=Date.now()
var loop=function(){var dt=Date.now()-last
self.callback(self.video,dt)
last=Date.now()
requestAnimationFrame(loop)}
requestAnimationFrame(loop)}}
lploc={}
lploc.unpack_localizer=function(bytes){const dview=new DataView(new ArrayBuffer(4));let p=0;dview.setUint8(0,bytes[p+0]),dview.setUint8(1,bytes[p+1]),dview.setUint8(2,bytes[p+2]),dview.setUint8(3,bytes[p+3]);const nstages=dview.getInt32(0,!0);p=p+4;dview.setUint8(0,bytes[p+0]),dview.setUint8(1,bytes[p+1]),dview.setUint8(2,bytes[p+2]),dview.setUint8(3,bytes[p+3]);const scalemul=dview.getFloat32(0,!0);p=p+4;dview.setUint8(0,bytes[p+0]),dview.setUint8(1,bytes[p+1]),dview.setUint8(2,bytes[p+2]),dview.setUint8(3,bytes[p+3]);const ntreesperstage=dview.getInt32(0,!0);p=p+4;dview.setUint8(0,bytes[p+0]),dview.setUint8(1,bytes[p+1]),dview.setUint8(2,bytes[p+2]),dview.setUint8(3,bytes[p+3]);const tdepth=dview.getInt32(0,!0);p=p+4;const tcodes_ls=[];const tpreds_ls=[];for(let i=0;i<nstages;++i){for(let j=0;j<ntreesperstage;++j){Array.prototype.push.apply(tcodes_ls,bytes.slice(p,p+4*Math.pow(2,tdepth)-4));p=p+4*Math.pow(2,tdepth)-4;for(let k=0;k<Math.pow(2,tdepth);++k)
for(let l=0;l<2;++l){dview.setUint8(0,bytes[p+0]),dview.setUint8(1,bytes[p+1]),dview.setUint8(2,bytes[p+2]),dview.setUint8(3,bytes[p+3]);tpreds_ls.push(dview.getFloat32(0,!0));p=p+4}}}
const tcodes=new Int8Array(tcodes_ls);const tpreds=new Float32Array(tpreds_ls);function loc_fun(r,c,s,pixels,nrows,ncols,ldim){let root=0;const pow2tdepth=Math.pow(2,tdepth)>>0;for(let i=0;i<nstages;++i){let dr=0.0,dc=0.0;for(let j=0;j<ntreesperstage;++j){let idx=0;for(var k=0;k<tdepth;++k){const r1=Math.min(nrows-1,Math.max(0,(256*r+tcodes[root+4*idx+0]*s)>>8));const c1=Math.min(ncols-1,Math.max(0,(256*c+tcodes[root+4*idx+1]*s)>>8));const r2=Math.min(nrows-1,Math.max(0,(256*r+tcodes[root+4*idx+2]*s)>>8));const c2=Math.min(ncols-1,Math.max(0,(256*c+tcodes[root+4*idx+3]*s)>>8));idx=2*idx+1+(pixels[r1*ldim+c1]>pixels[r2*ldim+c2])}
const lutidx=2*(ntreesperstage*pow2tdepth*i+pow2tdepth*j+idx-(pow2tdepth-1))
dr+=tpreds[lutidx+0];dc+=tpreds[lutidx+1];root+=4*pow2tdepth-4}
r=r+dr*s;c=c+dc*s;s=s*scalemul}
return[r,c]}
function loc_fun_with_perturbs(r,c,s,nperturbs,image){const rows=[],cols=[];for(let i=0;i<nperturbs;++i){const _s=s*(0.925+0.15*Math.random());let _r=r+s*0.15*(0.5-Math.random());let _c=c+s*0.15*(0.5-Math.random());[_r,_c]=loc_fun(_r,_c,_s,image.pixels,image.nrows,image.ncols,image.ldim)
rows.push(_r)
cols.push(_c)}
rows.sort()
cols.sort()
return[rows[Math.round(nperturbs/2)],cols[Math.round(nperturbs/2)]]}
return loc_fun_with_perturbs}
pico={}
pico.unpack_cascade=function(bytes){const dview=new DataView(new ArrayBuffer(4));let p=8;dview.setUint8(0,bytes[p+0]),dview.setUint8(1,bytes[p+1]),dview.setUint8(2,bytes[p+2]),dview.setUint8(3,bytes[p+3]);const tdepth=dview.getInt32(0,!0);p=p+4
dview.setUint8(0,bytes[p+0]),dview.setUint8(1,bytes[p+1]),dview.setUint8(2,bytes[p+2]),dview.setUint8(3,bytes[p+3]);const ntrees=dview.getInt32(0,!0);p=p+4
const tcodes_ls=[];const tpreds_ls=[];const thresh_ls=[];for(let t=0;t<ntrees;++t){Array.prototype.push.apply(tcodes_ls,[0,0,0,0]);Array.prototype.push.apply(tcodes_ls,bytes.slice(p,p+4*Math.pow(2,tdepth)-4));p=p+4*Math.pow(2,tdepth)-4;for(let i=0;i<Math.pow(2,tdepth);++i){dview.setUint8(0,bytes[p+0]),dview.setUint8(1,bytes[p+1]),dview.setUint8(2,bytes[p+2]),dview.setUint8(3,bytes[p+3]);tpreds_ls.push(dview.getFloat32(0,!0));p=p+4}
dview.setUint8(0,bytes[p+0]),dview.setUint8(1,bytes[p+1]),dview.setUint8(2,bytes[p+2]),dview.setUint8(3,bytes[p+3]);thresh_ls.push(dview.getFloat32(0,!0));p=p+4}
const tcodes=new Int8Array(tcodes_ls);const tpreds=new Float32Array(tpreds_ls);const thresh=new Float32Array(thresh_ls);function classify_region(r,c,s,pixels,ldim){r=256*r;c=256*c;let root=0;let o=0.0;const pow2tdepth=Math.pow(2,tdepth)>>0;for(let i=0;i<ntrees;++i){let idx=1;for(let j=0;j<tdepth;++j)
idx=2*idx+(pixels[((r+tcodes[root+4*idx+0]*s)>>8)*ldim+((c+tcodes[root+4*idx+1]*s)>>8)]<=pixels[((r+tcodes[root+4*idx+2]*s)>>8)*ldim+((c+tcodes[root+4*idx+3]*s)>>8)]);o=o+tpreds[pow2tdepth*i+idx-pow2tdepth];if(o<=thresh[i])
return-1;root+=4*pow2tdepth}
return o-thresh[ntrees-1]}
return classify_region}
pico.run_cascade=function(image,classify_region,params){const pixels=image.pixels;const nrows=image.nrows;const ncols=image.ncols;const ldim=image.ldim;const shiftfactor=params.shiftfactor;const minsize=params.minsize;const maxsize=params.maxsize;const scalefactor=params.scalefactor;let scale=minsize;const detections=[];while(scale<=maxsize){const step=Math.max(shiftfactor*scale,1)>>0;const offset=(scale/2+1)>>0;for(let r=offset;r<=nrows-offset;r+=step)
for(let c=offset;c<=ncols-offset;c+=step){const q=classify_region(r,c,scale,pixels,ldim);if(q>0.0)
detections.push([r,c,scale,q]);}
scale=scale*scalefactor}
return detections}
pico.cluster_detections=function(dets,iouthreshold){dets=dets.sort(function(a,b){return b[3]-a[3]});function calculate_iou(det1,det2){const r1=det1[0],c1=det1[1],s1=det1[2];const r2=det2[0],c2=det2[1],s2=det2[2];const overr=Math.max(0,Math.min(r1+s1/2,r2+s2/2)-Math.max(r1-s1/2,r2-s2/2));const overc=Math.max(0,Math.min(c1+s1/2,c2+s2/2)-Math.max(c1-s1/2,c2-s2/2));return overr*overc/(s1*s1+s2*s2-overr*overc)}
const assignments=new Array(dets.length).fill(0);const clusters=[];for(let i=0;i<dets.length;++i){if(assignments[i]==0){let r=0.0,c=0.0,s=0.0,q=0.0,n=0;for(let j=i;j<dets.length;++j)
if(calculate_iou(dets[i],dets[j])>iouthreshold){assignments[j]=1;r=r+dets[j][0];c=c+dets[j][1];s=s+dets[j][2];q=q+dets[j][3];n=n+1}
clusters.push([r/n,c/n,s/n,q])}}
return clusters}
pico.instantiate_detection_memory=function(size){let n=0;const memory=[];for(let i=0;i<size;++i)
memory.push([]);function update_memory(dets){memory[n]=dets;n=(n+1)%memory.length;dets=[];for(i=0;i<memory.length;++i)
dets=dets.concat(memory[i]);return dets}
return update_memory}