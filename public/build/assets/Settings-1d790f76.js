import{P as b}from"./PrimaryButton-314db279.js";import{_ as v}from"./Checkbox-fd59a90d.js";import{_ as V,a as S}from"./TextInput-347c1fc9.js";import{_ as w}from"./_plugin-vue_export-helper-c27b6911.js";import{r as c,o as r,c as a,b as e,F as h,e as _,a as d,w as D,n as G,t as y,f as O,d as A}from"./app-8d227d84.js";const C={name:"WorkingDays",components:{PrimaryButton:b,Checkbox:v,InputLabel:V,TextInput:S},props:{user:[Object],stores:[Object,Array],settings:[Object,Array]},data(){var o,l,u;return{processing:!1,hiddenStores:((o=this.settings)==null?void 0:o.hiddenStores)??[],workDays:((l=this.settings)==null?void 0:l.workdays)??[],ageGroups:((u=this.settings)==null?void 0:u.ageGroups)??{earlyYouth:"Early Youth",youth:"Youth",middleAge:"Middle Age",middleOld:"Middle Old",elderly:"Elderly"},response:{errors:!1,message:""}}},methods:{saveWorkingDays(){this.processing=!0,this.response.message="",axios.post(route("profile.settings.update"),{workdays:this.workDays,ageGroups:this.ageGroups,hiddenStores:this.hiddenStores,user:this.user}).then(o=>{this.processing=!1,this.response=o.data})},updateDays(o){this.workDays.includes(o)?this.workDays.splice(this.workDays.indexOf(o),1):this.workDays.push(o)},updateHiddenStores(o){this.hiddenStores.includes(o)?this.hiddenStores.splice(this.hiddenStores.indexOf(o),1):this.hiddenStores.push(o)}},setup(){return{days:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"]}}},U=e("header",null,[e("h2",{class:"text-lg font-medium text-gray-900"},"Hide Stores for user")],-1),Y={class:"flex items-center gap-4 mt-4"},T={class:"flex items-center"},B={class:"ms-2 text-sm text-gray-600"},I=e("header",{class:"mt-8"},[e("h2",{class:"text-lg font-medium text-gray-900"},"Off Days"),e("p",{class:"mt-1 text-sm text-gray-600"}," Set your off days. ")],-1),q={class:"flex items-center gap-4 mt-4"},E={class:"flex items-center"},M={class:"ms-2 text-sm text-gray-600"},L=e("header",{class:"mt-8"},[e("h2",{class:"text-lg font-medium text-gray-900"},"Title for age groups."),e("p",{class:"mt-1 text-sm text-gray-600"}," Change the titles for age groups in dount chart. ")],-1),N={class:"max-w-lg mt-4"},P={class:"mb-2"},W={class:"mb-2"},j={class:"mb-2"},F={class:"mb-2"},H={class:"mb-2"};function z(o,l,u,f,s,p){const g=c("Checkbox"),n=c("InputLabel"),i=c("TextInput"),x=c("PrimaryButton");return r(),a(h,null,[U,e("div",Y,[(r(!0),a(h,null,_(u.stores,(t,m)=>(r(),a("label",T,[d(g,{name:"hidden_stores","onUpdate:checked":k=>p.updateHiddenStores(t.dep_id),value:t.dep_id,checked:s.hiddenStores.includes(t.dep_id)},null,8,["onUpdate:checked","value","checked"]),e("span",B,y(t.name),1)]))),256))]),I,e("div",q,[(r(!0),a(h,null,_(f.days,(t,m)=>(r(),a("label",E,[d(g,{name:"off_days","onUpdate:checked":k=>p.updateDays(m),value:m,checked:s.workDays.includes(m)},null,8,["onUpdate:checked","value","checked"]),e("span",M,y(t),1)]))),256))]),L,e("div",N,[e("div",P,[d(n,{for:"earlyYouth",value:"Early Youth"}),d(i,{id:"earlyYouth",type:"text",class:"mt-1 block w-full",modelValue:s.ageGroups.earlyYouth,"onUpdate:modelValue":l[0]||(l[0]=t=>s.ageGroups.earlyYouth=t),required:""},null,8,["modelValue"])]),e("div",W,[d(n,{for:"youth",value:"Youth"}),d(i,{id:"youth",type:"text",class:"mt-1 block w-full",modelValue:s.ageGroups.youth,"onUpdate:modelValue":l[1]||(l[1]=t=>s.ageGroups.youth=t),required:""},null,8,["modelValue"])]),e("div",j,[d(n,{for:"middleAge",value:"Middle Age"}),d(i,{id:"middleAge",type:"text",class:"mt-1 block w-full",modelValue:s.ageGroups.middleAge,"onUpdate:modelValue":l[2]||(l[2]=t=>s.ageGroups.middleAge=t),required:""},null,8,["modelValue"])]),e("div",F,[d(n,{for:"middleAge",value:"Middle Old"}),d(i,{id:"middleOld",type:"text",class:"mt-1 block w-full",modelValue:s.ageGroups.middleOld,"onUpdate:modelValue":l[3]||(l[3]=t=>s.ageGroups.middleOld=t),required:""},null,8,["modelValue"])]),e("div",H,[d(n,{for:"elderly",value:"Elderly"}),d(i,{id:"elderly",type:"text",class:"mt-1 block w-full",modelValue:s.ageGroups.elderly,"onUpdate:modelValue":l[4]||(l[4]=t=>s.ageGroups.elderly=t),required:""},null,8,["modelValue"])]),d(x,{class:"mt-2",onClick:p.saveWorkingDays,disabled:s.processing},{default:D(()=>[A("Save")]),_:1},8,["onClick","disabled"]),s.response.message.length>0?(r(),a("p",{key:0,class:G(`${s.response.errors===!0?"text-red-500":"text-green-500"}`)},y(s.response.message),3)):O("",!0)])],64)}const Z=w(C,[["render",z]]);export{Z as default};