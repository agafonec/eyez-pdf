import{_ as O,a as B,b as F,P as D}from"./AuthenticatedLayout-d77ee701.js";import{Z as L,r,o as l,c as d,a as n,w as a,F as P,b as o,d as _,g as j,f as b,n as A,t as w}from"./app-e8d2e45e.js";import{_ as N}from"./InputError-fdfc1824.js";import{_ as S}from"./InputLabel-57feda78.js";import{_ as V}from"./PrimaryButton-a935a37a.js";import{D as E}from"./DangerButton-cb891795.js";import{_ as T}from"./TextInput-f3aa56d7.js";import{_ as q}from"./_plugin-vue_export-helper-c27b6911.js";import{B as H,P as z}from"./base-select-3acf7b93.js";import"./pdf-logo-00fc44ec.js";const R={name:"ImportOrders",components:{AuthenticatedLayout:O,Head:L,InputError:N,InputLabel:S,PrimaryButton:V,TextInput:T,Dropdown:B,DropdownLink:F,BaseSelect:H,PageLoader:z,PopupModal:D,DangerButton:E},props:{stores:{type:Array,required:!0}},data(){return{storesOptions:this.stores.length>0||Object.keys(this.stores).length>0?this.mapOptions():[],selectedStore:this.stores.length>0||Object.keys(this.stores).length>0?this.mapOptions()[0]:[],form:{storeId:this.stores.length>0||Object.keys(this.stores).length>0?this.mapOptions()[0].value:[],file:null},response:{errors:!1,message:""},importConfirmation:!1,importInterval:null}},methods:{uploadOrders(){this.response={errors:!1,message:""},this.form.processing=!0;let t=new FormData;t.append("file",this.form.file),t.append("storeId",this.form.storeId),this.validateCsv().then(e=>{if(console.log("validation",e),e.length>0){let i=e.join(",");this.form.processing=!1,this.response={errors:!0,message:`Required columns are missing. ${i}`}}else axios.post(route("orders.import"),t).then(i=>{this.form.processing=!1,this.response=i.data,this.importConfirmation=!1,setTimeout(()=>{window.location.reload()},2e3)}).catch(i=>{console.log(i),this.form.processing=!1,this.response={errors:!0,message:"Something went wrong, try again later."}})}).catch(e=>{console.error(e)})},handleFileChange(t){console.log(t.target.files[0]),this.form.file=t.target.files[0]},validateCsv(){return new Promise((t,e)=>{let i=["order_id","order_date","items_count","order_total"];if(this.form.file){const p=new FileReader;p.onload=s=>{const c=s.target.result.split(`\r
`)[0].split(","),u=i.filter(f=>!c.includes(f));t(u)},p.readAsText(this.form.file)}else e("No file selected")})},mapOptions(){return typeof this.stores=="object"?Object.values(this.stores).map(t=>({value:t.id,label:t.name})):this.stores.map(t=>({value:t.id,label:t.name}))}}},Y=o("h2",{class:"font-semibold text-xl text-gray-800 leading-tight"},"ייבוא הזמנות",-1),M={class:"py-12"},Z={class:"max-w-7xl mx-auto md:px-6 lg:px-8 space-y-6"},G={class:"p-4 md:p-8 bg-white shadow md:rounded-lg grid grid-cols-1 md:grid-cols-2 gap-5"},J=o("header",{class:"w-full"},[o("h2",{class:"text-lg font-medium text-gray-900"},"נא לבחור קובץ הזמנות לייבוא"),o("p",{class:"mt-1 text-sm text-gray-600"},[o("a",{href:"/download-file/eyez_download_sample.csv",target:"_blank",download:"",class:"font-semibold underline"}," כאן ניתן להוריד תבנית מתאימה להזמנות ")])],-1),K={key:0},Q={class:"mb-4"},U={class:"mb-4"},W={class:"flex items-center gap-4"},X={key:1,class:""},$={class:"text-lg font-medium text-gray-900 text-center"},ee={class:"flex justify-center gap-x-4 mt-6"};function te(t,e,i,p,s,m){const g=r("Head"),c=r("base-select"),u=r("InputLabel"),f=r("InputError"),y=r("PrimaryButton"),C=r("page-loader"),v=r("AuthenticatedLayout"),k=r("DangerButton"),I=r("popup-modal");return l(),d(P,null,[n(g,{title:"ייבוא הזמנות"}),n(v,null,{header:a(()=>[Y]),default:a(()=>{var x;return[o("div",M,[o("div",Z,[o("div",G,[J,s.storesOptions.length>0?(l(),d("div",K,[o("div",Q,[n(c,{options:s.storesOptions,id:"store",label:"נא לבחור חנות",currentValue:s.selectedStore,onChanged:e[0]||(e[0]=h=>s.form.storeId=h.value)},null,8,["options","currentValue"])]),o("div",U,[n(u,{for:"fileInput",value:"נא לבחור קובץ"}),o("input",{id:"fileInput",type:"file",class:"mt-1 block w-full ring-1 ring-inset ring-gray-300 py-1 px-4 rounded-md",onChange:e[1]||(e[1]=(...h)=>m.handleFileChange&&m.handleFileChange(...h))},null,32),n(f,{class:"mt-2",message:(x=s.form.errors)==null?void 0:x.orgId},null,8,["message"])]),o("div",W,[n(y,{onClick:e[2]||(e[2]=()=>{this.importConfirmation=!0}),disabled:s.form.processing||s.form.file===null},{default:a(()=>[_("שמירה")]),_:1},8,["disabled"]),s.form.processing?(l(),j(C,{key:0,width:"30",height:"30"})):b("",!0),s.response.message.length>0?(l(),d("p",{key:1,class:A(`${s.response.errors===!0?"text-red-500":"text-green-500"}`)},w(s.response.message),3)):b("",!0)])])):(l(),d("div",X," You didn't create opretail account or you don't have any stores created. "))])])])]}),_:1}),n(I,{show:s.importConfirmation,"max-width":"max-w-xl",onClose:e[4]||(e[4]=()=>{this.importConfirmation=!1})},{default:a(()=>[o("h2",$,"Are you sure you want to import orders to "+w(s.selectedStore.label)+"?",1),o("div",ee,[n(y,{onClick:m.uploadOrders},{default:a(()=>[_("Yes")]),_:1},8,["onClick"]),n(k,{onClick:e[3]||(e[3]=()=>{this.importConfirmation=!1})},{default:a(()=>[_("No")]),_:1})])]),_:1},8,["show"])],64)}const ce=q(R,[["render",te]]);export{ce as default};