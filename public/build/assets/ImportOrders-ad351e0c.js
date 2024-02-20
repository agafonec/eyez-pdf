import{_ as B,a as F,b as D,P as L}from"./AuthenticatedLayout-8aa5f9da.js";import{o as l,c as m,m as N,b as o,Z as P,r as n,a as i,w as a,F as S,d as g,g as j,f as w,n as A,t as b}from"./app-7e0a5c6a.js";import{_ as T}from"./InputError-86d41fc1.js";import{_ as V,a as E}from"./TextInput-958ab645.js";import{_ as q}from"./PrimaryButton-72f4ad8f.js";import{D as H}from"./DangerButton-01c67e3e.js";import{_ as v}from"./_plugin-vue_export-helper-c27b6911.js";import{B as M}from"./base-select-e0697995.js";import"./pdf-logo-949d1450.js";const R={name:"PageLoader",props:{width:{type:[Number,String],default:100},height:{type:[Number,String],default:100}}},Y=o("circle",{cx:"50",cy:"50",r:"32","stroke-width":"8",stroke:"#5ed615","stroke-dasharray":"50.26548245743669 50.26548245743669",fill:"none","stroke-linecap":"round"},[o("animateTransform",{attributeName:"transform",type:"rotate",repeatCount:"indefinite",dur:"1s",keyTimes:"0;1",values:"0 50 50;360 50 50"})],-1),$=[Y];function z(t,e,r,p,s,d){return l(),m("svg",N({xmlns:"http://www.w3.org/2000/svg"},{width:r.width,height:r.height},{"xmlns:xlink":"http://www.w3.org/1999/xlink",style:{display:"block","shape-rendering":"auto"},viewBox:"0 0 100 100",preserveAspectRatio:"xMidYMid"}),$,16)}const Z=v(R,[["render",z]]),G={name:"ImportOrders",components:{AuthenticatedLayout:B,Head:P,InputError:T,InputLabel:V,PrimaryButton:q,TextInput:E,Dropdown:F,DropdownLink:D,BaseSelect:M,PageLoader:Z,PopupModal:L,DangerButton:H},props:{stores:{type:Array,required:!0}},data(){return{storesOptions:this.stores.length>0||Object.keys(this.stores).length>0?this.mapOptions():[],selectedStore:this.stores.length>0||Object.keys(this.stores).length>0?this.mapOptions()[0]:[],form:{storeId:this.stores.length>0||Object.keys(this.stores).length>0?this.mapOptions()[0].value:[],file:null},response:{errors:!1,message:""},importConfirmation:!1,importInterval:null}},methods:{uploadOrders(){this.response={errors:!1,message:""},this.form.processing=!0;let t=new FormData;t.append("file",this.form.file),t.append("storeId",this.form.storeId),this.validateCsv().then(e=>{if(console.log("validation",e),e.length>0){let r=e.join(",");this.form.processing=!1,this.response={errors:!0,message:`Required columns are missing. ${r}`}}else axios.post(route("orders.import"),t).then(r=>{this.form.processing=!1,this.response=r.data,this.importConfirmation=!1,setTimeout(()=>{window.location.reload()},2e3)}).catch(r=>{console.log(r),this.form.processing=!1,this.response={errors:!0,message:"Something went wrong, try again later."}})}).catch(e=>{console.error(e)})},handleFileChange(t){console.log(t.target.files[0]),this.form.file=t.target.files[0]},validateCsv(){return new Promise((t,e)=>{let r=["order_id","order_date","items_count","order_total"];if(this.form.file){const p=new FileReader;p.onload=s=>{const c=s.target.result.split(`\r
`)[0].split(","),u=r.filter(f=>!c.includes(f));t(u)},p.readAsText(this.form.file)}else e("No file selected")})},mapOptions(){return typeof this.stores=="object"?Object.values(this.stores).map(t=>({value:t.id,label:t.name})):this.stores.map(t=>({value:t.id,label:t.name}))}}},J=o("h2",{class:"font-semibold text-xl text-gray-800 leading-tight"},"ייבוא הזמנות",-1),K={class:"py-12"},Q={class:"max-w-7xl mx-auto md:px-6 lg:px-8 space-y-6"},U={class:"p-4 md:p-8 bg-white shadow md:rounded-lg grid grid-cols-1 md:grid-cols-2 gap-5"},W=o("header",{class:"w-full"},[o("h2",{class:"text-lg font-medium text-gray-900"},"נא לבחור קובץ הזמנות לייבוא"),o("p",{class:"mt-1 text-sm text-gray-600"},[o("a",{href:"/download-file/eyez_download_sample.csv",target:"_blank",download:"",class:"font-semibold underline"}," כאן ניתן להוריד תבנית מתאימה להזמנות ")])],-1),X={key:0},ee={class:"mb-4"},te={class:"mb-4"},se={class:"flex items-center gap-4"},oe={key:1,class:""},re={class:"text-lg font-medium text-gray-900 text-center"},ne={class:"flex justify-center gap-x-4 mt-6"};function ie(t,e,r,p,s,d){const _=n("Head"),c=n("base-select"),u=n("InputLabel"),f=n("InputError"),y=n("PrimaryButton"),k=n("page-loader"),C=n("AuthenticatedLayout"),I=n("DangerButton"),O=n("popup-modal");return l(),m(S,null,[i(_,{title:"ייבוא הזמנות"}),i(C,null,{header:a(()=>[J]),default:a(()=>{var x;return[o("div",K,[o("div",Q,[o("div",U,[W,s.storesOptions.length>0?(l(),m("div",X,[o("div",ee,[i(c,{options:s.storesOptions,id:"store",label:"נא לבחור חנות",currentValue:s.selectedStore,onChanged:e[0]||(e[0]=h=>s.form.storeId=h.value)},null,8,["options","currentValue"])]),o("div",te,[i(u,{for:"fileInput",value:"נא לבחור קובץ"}),o("input",{id:"fileInput",type:"file",class:"mt-1 block w-full ring-1 ring-inset ring-gray-300 py-1 px-4 rounded-md",onChange:e[1]||(e[1]=(...h)=>d.handleFileChange&&d.handleFileChange(...h))},null,32),i(f,{class:"mt-2",message:(x=s.form.errors)==null?void 0:x.orgId},null,8,["message"])]),o("div",se,[i(y,{onClick:e[2]||(e[2]=()=>{this.importConfirmation=!0}),disabled:s.form.processing||s.form.file===null},{default:a(()=>[g("שמירה")]),_:1},8,["disabled"]),s.form.processing?(l(),j(k,{key:0,width:"30",height:"30"})):w("",!0),s.response.message.length>0?(l(),m("p",{key:1,class:A(`${s.response.errors===!0?"text-red-500":"text-green-500"}`)},b(s.response.message),3)):w("",!0)])])):(l(),m("div",oe," You didn't create opretail account or you don't have any stores created. "))])])])]}),_:1}),i(O,{show:s.importConfirmation,"max-width":"max-w-xl",onClose:e[4]||(e[4]=()=>{this.importConfirmation=!1})},{default:a(()=>[o("h2",re,"Are you sure you want to import orders to "+b(s.selectedStore.label)+"?",1),o("div",ne,[i(y,{onClick:d.uploadOrders},{default:a(()=>[g("Yes")]),_:1},8,["onClick"]),i(I,{onClick:e[3]||(e[3]=()=>{this.importConfirmation=!1})},{default:a(()=>[g("No")]),_:1})])]),_:1},8,["show"])],64)}const ge=v(G,[["render",ie]]);export{ge as default};
