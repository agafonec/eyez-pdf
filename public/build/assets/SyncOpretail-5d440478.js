import{_ as C}from"./AuthenticatedLayout-0f31fc29.js";import{o as a,c as n,b as e,t as d,f as _,l as I,Z as B,r as l,a as i,w as h,F as j,n as O,g as P,m as V,s as N,d as J}from"./app-eca9913b.js";import{_ as A}from"./PrimaryButton-dddd3eec.js";import{_ as f}from"./_plugin-vue_export-helper-c27b6911.js";import{D as Y,I as H}from"./index-d9f66829.js";import{B as F}from"./base-select-29bf248d.js";import{h as L}from"./moment-fbc5633a.js";import"./pdf-logo-c73ffd00.js";const M={name:"ProgressBar",props:{completed:{type:Number,default:0},success:{type:Number,default:0},failed:{type:Number,default:0},label:String}},$={key:0,class:"flex justify-between mb-1"},z={class:"text-base font-medium text-black"},E={class:"w-full bg-gray-200 rounded-full h-4"},T={class:"text-sm font-medium text-white"},U={class:""},Z={key:0},q={class:"text-green-400"},G=e("span",null,"Success: ",-1),K={class:"font-semibold"},Q={class:"text-red-400"},R=e("span",null,"Failed: ",-1),W={class:"font-semibold"};function X(t,r,o,u,s,c){return a(),n("div",null,[o.label?(a(),n("div",$,[e("span",z,d(o.label),1)])):_("",!0),e("div",E,[e("div",{class:"bg-green-400 h-4 flex items-center justify-center rounded-full transition-all",style:I(`width: ${o.completed}%`)},[e("span",T,d(o.completed)+"%",1)],4)]),e("div",U,[o.completed>=100?(a(),n("p",Z,"Processed: ")):_("",!0),e("ul",null,[e("li",q,[G,e("span",K,d(o.success),1)]),e("li",Q,[R,e("span",W,d(o.failed),1)])])])])}const ee=f(M,[["render",X]]),te={name:"SyncOpretail",components:{AuthenticatedLayout:C,Head:B,PrimaryButton:A,ProgressBar:ee,BaseSelect:F,DatePicker:Y,IconCalendar:H},props:{stores:{type:[Object,Array]},errors:[Object,Boolean],messages:[Array,String]},data(){var t,r;return{storesOptions:((t=this.stores)==null?void 0:t.length)>0||typeof this.stores=="object"?this.mapOptions():[],selectedStore:((r=this.stores)==null?void 0:r.length)>0||typeof this.stores=="object"?this.mapOptions()[0]:[],selectedDate:new Date,syncing:!1,fetchInterval:null,batchId:null,progress:null}},methods:{formattedDate(){return L(this.selectedDate).format("YYYY-MM-DD")},startSync(){this.syncing=!0,axios.post(route("profile.sync.start",{store:this.selectedStore.value}),{startDate:this.selectedDate}).then(t=>{this.batchId=t.data.batchId,this.fetchInterval=setInterval(this.fetchProgress,300)}).catch(t=>{console.log(t)})},mapOptions(){return typeof this.stores=="object"?Object.values(this.stores).map(t=>({value:t.id,label:t.name})):this.stores.map(t=>({value:t.id,label:t.name}))},fetchProgress(){axios.get(route("jobs.progress",{batchId:this.batchId})).then(t=>{var r,o,u,s,c,p;this.progress={percent:(r=t.data.progress)==null?void 0:r.processedJobs,success:(o=t.data.progress)==null?void 0:o.processedJobs,error:(u=t.data.progress)==null?void 0:u.failedJobs,total:(s=t.data.progress)==null?void 0:s.totalJobs,totalCompleted:((c=t.data.progress)==null?void 0:c.processedJobs)+((p=t.data.progress)==null?void 0:p.failedJobs)},this.progress.percent=Math.round(this.progress.totalCompleted/this.progress.total*100),this.progress.totalCompleted===this.progress.total&&clearInterval(this.fetchInterval)}).catch(t=>{console.error("Error fetching progress:",t)})}}},se=e("h2",{class:"font-semibold text-xl text-gray-800 leading-tight"},"Sync with opretail",-1),oe={class:"py-12"},re={class:"max-w-7xl mx-auto md:px-6 lg:px-8 space-y-6"},ae={class:"p-4 md:p-8 bg-white shadow md:rounded-lg"},ne=e("header",null,[e("h2",{class:"text-lg font-medium text-gray-900"},"Start sync process with cameras.")],-1),le={key:2,class:"max-w-md"},ce={key:0},ie={class:"mb-4"},de={class:""},me=e("label",{class:"block text-sm font-medium leading-6 text-gray-900"},"Select starting date you want to sync from",-1),ue={class:"flex justify-center overflow-hidden w-full"},pe=["onClick"],he=["value"],_e={key:1};function ge(t,r,o,u,s,c){const p=l("Head"),y=l("progress-bar"),b=l("base-select"),x=l("icon-calendar"),v=l("date-picker"),w=l("PrimaryButton"),k=l("AuthenticatedLayout");return a(),n(j,null,[i(p,{title:"Sync with opretail"}),i(k,null,{header:h(()=>[se]),default:h(()=>{var g;return[e("div",oe,[e("div",re,[e("div",ae,[ne,((g=o.messages)==null?void 0:g.length)>0?(a(),n("p",{key:0,class:O(`${o.errors===!0?"text-red-500":"text-green-500"}`)},d(o.messages),3)):_("",!0),s.progress&&s.batchId?(a(),P(y,{key:1,completed:s.progress.percent,success:s.progress.success,failes:s.progress.error,label:"Syncing store"},null,8,["completed","success","failes"])):(a(),n("div",le,[s.storesOptions.length>0?(a(),n("div",ce,[e("div",ie,[i(b,{options:s.storesOptions,id:"store",label:"נא לבחור חנות",currentValue:s.selectedStore,onChanged:r[0]||(r[0]=m=>s.selectedStore=m)},null,8,["options","currentValue"])]),e("div",de,[me,i(v,{style:{direction:"ltr"},mode:"date","max-date":new Date,modelValue:s.selectedDate,"onUpdate:modelValue":r[1]||(r[1]=m=>s.selectedDate=m),popover:{visibility:"hover-focus",placement:"bottom",isInteractive:!0}},{default:h(({togglePopover:m,inputValue:S,inputEvents:D})=>[e("div",ue,[e("button",{class:"flex items-center justify-center text-gray-900 ring-1 ring-inset ring-gray-300 rounded-md w-full py-1.5",onClick:()=>m()},[e("span",null,d(c.formattedDate()),1),i(x,{class:"mr-4"})],8,pe),e("input",V({value:S},N(D,!0),{class:"flex-grow p-0 bg-white dark:bg-gray-700 opacity-0 w-0"}),null,16,he)])]),_:1},8,["max-date","modelValue"])]),i(w,{class:"mt-4",onClick:c.startSync},{default:h(()=>[J("Start Sync")]),_:1},8,["onClick"])])):(a(),n("div",_e," You have no stores created. Contact main user or support. "))]))])])])]}),_:1})],64)}const De=f(te,[["render",ge]]);export{De as default};
