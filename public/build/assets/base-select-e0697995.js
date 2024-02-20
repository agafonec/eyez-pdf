import{q as y,x as j,R as te,j as h,p as N,L as se,v as le,U as W,F as ae,y as de,D as $,N as ce,V as R,o as V,c as U,b as T,r as P,g as X,w as C,a as F,d as ve,t as H,e as pe,n as K,f as fe,B as be}from"./app-7e0a5c6a.js";import{_ as me}from"./_plugin-vue_export-helper-c27b6911.js";import{o as m,u as B,y as xe,w as ge,h as he,c as ye,l as z,f as Oe,K as Le,d as Se,H as E,T as we,t as q,p as De,N as Y,O as _e,e as g}from"./AuthenticatedLayout-8aa5f9da.js";function ke(e){throw new Error("Unexpected object: "+e)}var O=(e=>(e[e.First=0]="First",e[e.Previous=1]="Previous",e[e.Next=2]="Next",e[e.Last=3]="Last",e[e.Specific=4]="Specific",e[e.Nothing=5]="Nothing",e))(O||{});function Re(e,l){let a=l.resolveItems();if(a.length<=0)return null;let r=l.resolveActiveIndex(),t=r??-1,u=(()=>{switch(e.focus){case 0:return a.findIndex(i=>!l.resolveDisabled(i));case 1:{let i=a.slice().reverse().findIndex((p,b,o)=>t!==-1&&o.length-b-1>=t?!1:!l.resolveDisabled(p));return i===-1?i:a.length-1-i}case 2:return a.findIndex((i,p)=>p<=t?!1:!l.resolveDisabled(i));case 3:{let i=a.slice().reverse().findIndex(p=>!l.resolveDisabled(p));return i===-1?i:a.length-1-i}case 4:return a.findIndex(i=>l.resolveId(i)===e.id);case 5:return null;default:ke(e)}})();return u===-1?r:u}function G(e,l){if(e)return e;let a=l??"button";if(typeof a=="string"&&a.toLowerCase()==="button")return"button"}function Ie(e,l){let a=y(G(e.value.type,e.value.as));return j(()=>{a.value=G(e.value.type,e.value.as)}),te(()=>{var r;a.value||m(l)&&m(l)instanceof HTMLButtonElement&&!((r=m(l))!=null&&r.hasAttribute("type"))&&(a.value="button")}),a}function oe(e={},l=null,a=[]){for(let[r,t]of Object.entries(e))ie(a,ne(l,r),t);return a}function ne(e,l){return e?e+"["+l+"]":l}function ie(e,l,a){if(Array.isArray(a))for(let[r,t]of a.entries())ie(e,ne(l,r.toString()),t);else a instanceof Date?e.push([l,a.toISOString()]):typeof a=="boolean"?e.push([l,a?"1":"0"]):typeof a=="string"?e.push([l,a]):typeof a=="number"?e.push([l,`${a}`]):a==null?e.push([l,""]):oe(a,l,e)}function Te(e,l,a){let r=y(a==null?void 0:a.value),t=h(()=>e.value!==void 0);return[h(()=>t.value?e.value:r.value),function(u){return t.value||(r.value=u),l==null?void 0:l(u)}]}function J(e){return[e.screenX,e.screenY]}function Be(){let e=y([-1,-1]);return{wasMoved(l){let a=J(l);return e.value[0]===a[0]&&e.value[1]===a[1]?!1:(e.value=a,!0)},update(l){e.value=J(l)}}}let Z=/([\u2700-\u27BF]|[\uE000-\uF8FF]|\uD83C[\uDC00-\uDFFF]|\uD83D[\uDC00-\uDFFF]|[\u2011-\u26FF]|\uD83E[\uDD10-\uDDFF])/g;function ee(e){var l,a;let r=(l=e.innerText)!=null?l:"",t=e.cloneNode(!0);if(!(t instanceof HTMLElement))return r;let u=!1;for(let p of t.querySelectorAll('[hidden],[aria-hidden],[role="img"]'))p.remove(),u=!0;let i=u?(a=t.innerText)!=null?a:"":r;return Z.test(i)&&(i=i.replace(Z,"")),i}function Pe(e){let l=e.getAttribute("aria-label");if(typeof l=="string")return l.trim();let a=e.getAttribute("aria-labelledby");if(a){let r=a.split(" ").map(t=>{let u=document.getElementById(t);if(u){let i=u.getAttribute("aria-label");return typeof i=="string"?i.trim():ee(u).trim()}return null}).filter(Boolean);if(r.length>0)return r.join(", ")}return ee(e).trim()}function $e(e){let l=y(""),a=y("");return()=>{let r=m(e);if(!r)return"";let t=r.innerText;if(l.value===t)return a.value;let u=Pe(r).trim().toLowerCase();return l.value=t,a.value=u,u}}function Ce(e,l){return e===l}var Fe=(e=>(e[e.Open=0]="Open",e[e.Closed=1]="Closed",e))(Fe||{}),Ve=(e=>(e[e.Single=0]="Single",e[e.Multi=1]="Multi",e))(Ve||{}),Ae=(e=>(e[e.Pointer=0]="Pointer",e[e.Other=1]="Other",e))(Ae||{});function Ne(e){requestAnimationFrame(()=>requestAnimationFrame(e))}let re=Symbol("ListboxContext");function M(e){let l=ce(re,null);if(l===null){let a=new Error(`<${e} /> is missing a parent <Listbox /> component.`);throw Error.captureStackTrace&&Error.captureStackTrace(a,M),a}return l}let Ee=N({name:"Listbox",emits:{"update:modelValue":e=>!0},props:{as:{type:[Object,String],default:"template"},disabled:{type:[Boolean],default:!1},by:{type:[String,Function],default:()=>Ce},horizontal:{type:[Boolean],default:!1},modelValue:{type:[Object,String,Number,Boolean],default:void 0},defaultValue:{type:[Object,String,Number,Boolean],default:void 0},form:{type:String,optional:!0},name:{type:String,optional:!0},multiple:{type:[Boolean],default:!1}},inheritAttrs:!1,setup(e,{slots:l,attrs:a,emit:r}){let t=y(1),u=y(null),i=y(null),p=y(null),b=y([]),o=y(""),n=y(null),w=y(1);function D(s=d=>d){let d=n.value!==null?b.value[n.value]:null,c=_e(s(b.value.slice()),S=>m(S.dataRef.domRef)),f=d?c.indexOf(d):null;return f===-1&&(f=null),{options:c,activeOptionIndex:f}}let x=h(()=>e.multiple?1:0),[k,I]=Te(h(()=>e.modelValue),s=>r("update:modelValue",s),h(()=>e.defaultValue)),_=h(()=>k.value===void 0?B(x.value,{1:[],0:void 0}):k.value),v={listboxState:t,value:_,mode:x,compare(s,d){if(typeof e.by=="string"){let c=e.by;return(s==null?void 0:s[c])===(d==null?void 0:d[c])}return e.by(s,d)},orientation:h(()=>e.horizontal?"horizontal":"vertical"),labelRef:u,buttonRef:i,optionsRef:p,disabled:h(()=>e.disabled),options:b,searchQuery:o,activeOptionIndex:n,activationTrigger:w,closeListbox(){e.disabled||t.value!==1&&(t.value=1,n.value=null)},openListbox(){e.disabled||t.value!==0&&(t.value=0)},goToOption(s,d,c){if(e.disabled||t.value===1)return;let f=D(),S=Re(s===O.Specific?{focus:O.Specific,id:d}:{focus:s},{resolveItems:()=>f.options,resolveActiveIndex:()=>f.activeOptionIndex,resolveId:A=>A.id,resolveDisabled:A=>A.dataRef.disabled});o.value="",n.value=S,w.value=c??1,b.value=f.options},search(s){if(e.disabled||t.value===1)return;let d=o.value!==""?0:1;o.value+=s.toLowerCase();let c=(n.value!==null?b.value.slice(n.value+d).concat(b.value.slice(0,n.value+d)):b.value).find(S=>S.dataRef.textValue.startsWith(o.value)&&!S.dataRef.disabled),f=c?b.value.indexOf(c):-1;f===-1||f===n.value||(n.value=f,w.value=1)},clearSearch(){e.disabled||t.value!==1&&o.value!==""&&(o.value="")},registerOption(s,d){let c=D(f=>[...f,{id:s,dataRef:d}]);b.value=c.options,n.value=c.activeOptionIndex},unregisterOption(s){let d=D(c=>{let f=c.findIndex(S=>S.id===s);return f!==-1&&c.splice(f,1),c});b.value=d.options,n.value=d.activeOptionIndex,w.value=1},theirOnChange(s){e.disabled||I(s)},select(s){e.disabled||I(B(x.value,{0:()=>s,1:()=>{let d=R(v.value.value).slice(),c=R(s),f=d.findIndex(S=>v.compare(c,R(S)));return f===-1?d.push(c):d.splice(f,1),d}}))}};xe([i,p],(s,d)=>{var c;v.closeListbox(),ge(d,he.Loose)||(s.preventDefault(),(c=m(i))==null||c.focus())},h(()=>t.value===0)),se(re,v),ye(h(()=>B(t.value,{0:z.Open,1:z.Closed})));let L=h(()=>{var s;return(s=m(i))==null?void 0:s.closest("form")});return j(()=>{le([L],()=>{if(!L.value||e.defaultValue===void 0)return;function s(){v.theirOnChange(e.defaultValue)}return L.value.addEventListener("reset",s),()=>{var d;(d=L.value)==null||d.removeEventListener("reset",s)}},{immediate:!0})}),()=>{let{name:s,modelValue:d,disabled:c,form:f,...S}=e,A={open:t.value===0,disabled:c,value:_.value};return W(ae,[...s!=null&&_.value!=null?oe({[s]:_.value}).map(([Q,ue])=>W(Oe,Le({features:Se.Hidden,key:Q,as:"input",type:"hidden",hidden:!0,readOnly:!0,form:f,name:Q,value:ue}))):[],E({ourProps:{},theirProps:{...a,...we(S,["defaultValue","onUpdate:modelValue","horizontal","multiple","by"])},slot:A,slots:l,attrs:a,name:"Listbox"})])}}}),Me=N({name:"ListboxLabel",props:{as:{type:[Object,String],default:"label"},id:{type:String,default:()=>`headlessui-listbox-label-${q()}`}},setup(e,{attrs:l,slots:a}){let r=M("ListboxLabel");function t(){var u;(u=m(r.buttonRef))==null||u.focus({preventScroll:!0})}return()=>{let u={open:r.listboxState.value===0,disabled:r.disabled.value},{id:i,...p}=e,b={id:i,ref:r.labelRef,onClick:t};return E({ourProps:b,theirProps:p,slot:u,attrs:l,slots:a,name:"ListboxLabel"})}}}),je=N({name:"ListboxButton",props:{as:{type:[Object,String],default:"button"},id:{type:String,default:()=>`headlessui-listbox-button-${q()}`}},setup(e,{attrs:l,slots:a,expose:r}){let t=M("ListboxButton");r({el:t.buttonRef,$el:t.buttonRef});function u(o){switch(o.key){case g.Space:case g.Enter:case g.ArrowDown:o.preventDefault(),t.openListbox(),$(()=>{var n;(n=m(t.optionsRef))==null||n.focus({preventScroll:!0}),t.value.value||t.goToOption(O.First)});break;case g.ArrowUp:o.preventDefault(),t.openListbox(),$(()=>{var n;(n=m(t.optionsRef))==null||n.focus({preventScroll:!0}),t.value.value||t.goToOption(O.Last)});break}}function i(o){switch(o.key){case g.Space:o.preventDefault();break}}function p(o){t.disabled.value||(t.listboxState.value===0?(t.closeListbox(),$(()=>{var n;return(n=m(t.buttonRef))==null?void 0:n.focus({preventScroll:!0})})):(o.preventDefault(),t.openListbox(),Ne(()=>{var n;return(n=m(t.optionsRef))==null?void 0:n.focus({preventScroll:!0})})))}let b=Ie(h(()=>({as:e.as,type:l.type})),t.buttonRef);return()=>{var o,n;let w={open:t.listboxState.value===0,disabled:t.disabled.value,value:t.value.value},{id:D,...x}=e,k={ref:t.buttonRef,id:D,type:b.value,"aria-haspopup":"listbox","aria-controls":(o=m(t.optionsRef))==null?void 0:o.id,"aria-expanded":t.listboxState.value===0,"aria-labelledby":t.labelRef.value?[(n=m(t.labelRef))==null?void 0:n.id,D].join(" "):void 0,disabled:t.disabled.value===!0?!0:void 0,onKeydown:u,onKeyup:i,onClick:p};return E({ourProps:k,theirProps:x,slot:w,attrs:l,slots:a,name:"ListboxButton"})}}}),Ue=N({name:"ListboxOptions",props:{as:{type:[Object,String],default:"ul"},static:{type:Boolean,default:!1},unmount:{type:Boolean,default:!0},id:{type:String,default:()=>`headlessui-listbox-options-${q()}`}},setup(e,{attrs:l,slots:a,expose:r}){let t=M("ListboxOptions"),u=y(null);r({el:t.optionsRef,$el:t.optionsRef});function i(o){switch(u.value&&clearTimeout(u.value),o.key){case g.Space:if(t.searchQuery.value!=="")return o.preventDefault(),o.stopPropagation(),t.search(o.key);case g.Enter:if(o.preventDefault(),o.stopPropagation(),t.activeOptionIndex.value!==null){let n=t.options.value[t.activeOptionIndex.value];t.select(n.dataRef.value)}t.mode.value===0&&(t.closeListbox(),$(()=>{var n;return(n=m(t.buttonRef))==null?void 0:n.focus({preventScroll:!0})}));break;case B(t.orientation.value,{vertical:g.ArrowDown,horizontal:g.ArrowRight}):return o.preventDefault(),o.stopPropagation(),t.goToOption(O.Next);case B(t.orientation.value,{vertical:g.ArrowUp,horizontal:g.ArrowLeft}):return o.preventDefault(),o.stopPropagation(),t.goToOption(O.Previous);case g.Home:case g.PageUp:return o.preventDefault(),o.stopPropagation(),t.goToOption(O.First);case g.End:case g.PageDown:return o.preventDefault(),o.stopPropagation(),t.goToOption(O.Last);case g.Escape:o.preventDefault(),o.stopPropagation(),t.closeListbox(),$(()=>{var n;return(n=m(t.buttonRef))==null?void 0:n.focus({preventScroll:!0})});break;case g.Tab:o.preventDefault(),o.stopPropagation();break;default:o.key.length===1&&(t.search(o.key),u.value=setTimeout(()=>t.clearSearch(),350));break}}let p=De(),b=h(()=>p!==null?(p.value&z.Open)===z.Open:t.listboxState.value===0);return()=>{var o,n,w,D;let x={open:t.listboxState.value===0},{id:k,...I}=e,_={"aria-activedescendant":t.activeOptionIndex.value===null||(o=t.options.value[t.activeOptionIndex.value])==null?void 0:o.id,"aria-multiselectable":t.mode.value===1?!0:void 0,"aria-labelledby":(D=(n=m(t.labelRef))==null?void 0:n.id)!=null?D:(w=m(t.buttonRef))==null?void 0:w.id,"aria-orientation":t.orientation.value,id:k,onKeydown:i,role:"listbox",tabIndex:0,ref:t.optionsRef};return E({ourProps:_,theirProps:I,slot:x,attrs:l,slots:a,features:Y.RenderStrategy|Y.Static,visible:b.value,name:"ListboxOptions"})}}}),ze=N({name:"ListboxOption",props:{as:{type:[Object,String],default:"li"},value:{type:[Object,String,Number,Boolean]},disabled:{type:Boolean,default:!1},id:{type:String,default:()=>`headlessui-listbox.option-${q()}`}},setup(e,{slots:l,attrs:a,expose:r}){let t=M("ListboxOption"),u=y(null);r({el:u,$el:u});let i=h(()=>t.activeOptionIndex.value!==null?t.options.value[t.activeOptionIndex.value].id===e.id:!1),p=h(()=>B(t.mode.value,{0:()=>t.compare(R(t.value.value),R(e.value)),1:()=>R(t.value.value).some(v=>t.compare(R(v),R(e.value)))})),b=h(()=>B(t.mode.value,{1:()=>{var v;let L=R(t.value.value);return((v=t.options.value.find(s=>L.some(d=>t.compare(R(d),R(s.dataRef.value)))))==null?void 0:v.id)===e.id},0:()=>p.value})),o=$e(u),n=h(()=>({disabled:e.disabled,value:e.value,get textValue(){return o()},domRef:u}));j(()=>t.registerOption(e.id,n)),de(()=>t.unregisterOption(e.id)),j(()=>{le([t.listboxState,p],()=>{t.listboxState.value===0&&p.value&&B(t.mode.value,{1:()=>{b.value&&t.goToOption(O.Specific,e.id)},0:()=>{t.goToOption(O.Specific,e.id)}})},{immediate:!0})}),te(()=>{t.listboxState.value===0&&i.value&&t.activationTrigger.value!==0&&$(()=>{var v,L;return(L=(v=m(u))==null?void 0:v.scrollIntoView)==null?void 0:L.call(v,{block:"nearest"})})});function w(v){if(e.disabled)return v.preventDefault();t.select(e.value),t.mode.value===0&&(t.closeListbox(),$(()=>{var L;return(L=m(t.buttonRef))==null?void 0:L.focus({preventScroll:!0})}))}function D(){if(e.disabled)return t.goToOption(O.Nothing);t.goToOption(O.Specific,e.id)}let x=Be();function k(v){x.update(v)}function I(v){x.wasMoved(v)&&(e.disabled||i.value||t.goToOption(O.Specific,e.id,0))}function _(v){x.wasMoved(v)&&(e.disabled||i.value&&t.goToOption(O.Nothing))}return()=>{let{disabled:v}=e,L={active:i.value,selected:p.value,disabled:v},{id:s,value:d,disabled:c,...f}=e,S={id:s,ref:u,role:"option",tabIndex:v===!0?void 0:-1,"aria-disabled":v===!0?!0:void 0,"aria-selected":p.value,disabled:void 0,onClick:w,onFocus:D,onPointerenter:k,onMouseenter:k,onPointermove:I,onMousemove:I,onPointerleave:_,onMouseleave:_};return E({ourProps:S,theirProps:f,slot:L,attrs:a,slots:l,name:"ListboxOption"})}}});function qe(e,l){return V(),U("svg",{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 20 20",fill:"currentColor","aria-hidden":"true"},[T("path",{"fill-rule":"evenodd",d:"M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z","clip-rule":"evenodd"})])}function He(e,l){return V(),U("svg",{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 20 20",fill:"currentColor","aria-hidden":"true"},[T("path",{"fill-rule":"evenodd",d:"M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z","clip-rule":"evenodd"})])}const Ke={name:"BaseSelect",components:{Listbox:Ee,ListboxButton:je,ListboxLabel:Me,ListboxOption:ze,ListboxOptions:Ue,CheckIcon:qe,ChevronUpDownIcon:He},props:{id:{type:String,required:!0},label:{type:String,required:!0},currentValue:{type:Object},options:{type:Array,required:!0}},emits:["changed"],data(){return{selected:this.currentValue}}},Qe={class:"relative mt-2"},We={class:"flex items-center"},Xe={class:"ml-3 block truncate"},Ye={class:"pointer-events-none absolute inset-y-0 right-0 ml-3 flex items-center pr-2"},Ge={class:"flex items-center"};function Je(e,l,a,r,t,u){const i=P("ListboxLabel"),p=P("ChevronUpDownIcon"),b=P("ListboxButton"),o=P("CheckIcon"),n=P("ListboxOption"),w=P("ListboxOptions"),D=P("Listbox");return V(),X(D,{as:"div",modelValue:t.selected,"onUpdate:modelValue":[l[0]||(l[0]=x=>t.selected=x),l[1]||(l[1]=x=>e.$emit("changed",t.selected))]},{default:C(()=>[F(i,{class:"block text-sm font-medium leading-6 text-gray-900"},{default:C(()=>[ve(H(a.label),1)]),_:1}),T("div",Qe,[F(b,{class:"relative w-full cursor-default rounded-md bg-white py-1.5 pl-3 pr-10 text-left text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm sm:leading-6"},{default:C(()=>[T("span",We,[T("span",Xe,H(t.selected.label),1)]),T("span",Ye,[F(p,{class:"h-5 w-5 text-gray-400","aria-hidden":"true"})])]),_:1}),F(be,{"leave-active-class":"transition ease-in duration-100","leave-from-class":"opacity-100","leave-to-class":"opacity-0"},{default:C(()=>[F(w,{class:"absolute z-10 mt-1 max-h-56 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"},{default:C(()=>[(V(!0),U(ae,null,pe(a.options,(x,k)=>(V(),X(n,{as:"template",key:`${a.id}-${k}`,value:x},{default:C(({active:I,selected:_})=>[T("li",{class:K([I?"bg-green-300 text-white":"text-gray-900","relative cursor-default select-none py-2 pl-3 pr-9"])},[T("div",Ge,[T("span",{class:K([_?"font-semibold":"font-normal","ml-3 block truncate"])},H(x.label),3)]),_?(V(),U("span",{key:0,class:K([I?"text-white":"text-green-300","absolute inset-y-0 right-0 flex items-center pr-4"])},[F(o,{class:"h-5 w-5","aria-hidden":"true"})],2)):fe("",!0)],2)]),_:2},1032,["value"]))),128))]),_:1})]),_:1})])]),_:1},8,["modelValue"])}const lt=me(Ke,[["render",Je]]);export{lt as B};
