import{P as h}from"./PrimaryButton-d0987c58.js";import{_ as k}from"./Checkbox-b232b356.js";import{_}from"./_plugin-vue_export-helper-c27b6911.js";import{k as c,o as r,f as a,b as t,F as i,C as g,a as d,w as f,n as x,t as l,g as w,d as D}from"./app-48b00a51.js";const C={name:"WorkingDays",components:{PrimaryButton:h,Checkbox:k},props:{workdays:[Object,Array]},data(){return{processing:!1,workDays:this.workdays??[],response:{errors:!1,message:""}}},methods:{saveWorkingDays(){this.processing=!0,this.response.message="",axios.post(route("profile.workdays.update"),{workdays:this.workDays}).then(e=>{this.processing=!1,this.response=e.data})},updateDays(e){this.workDays.includes(e)?this.workDays.splice(this.workDays.indexOf(e),1):this.workDays.push(e)}},setup(){return{days:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"]}}},b=t("header",null,[t("h2",{class:"text-lg font-medium text-gray-900"},"Working days"),t("p",{class:"mt-1 text-sm text-gray-600"}," Set your working days. ")],-1),v={class:"flex items-center gap-4"},B={class:"flex items-center"},W={class:"ms-2 text-sm text-gray-600"};function S(e,N,P,m,s,n){const p=c("Checkbox"),y=c("PrimaryButton");return r(),a(i,null,[b,t("div",v,[(r(!0),a(i,null,g(m.days,(u,o)=>(r(),a("label",B,[d(p,{name:"remember","onUpdate:checked":V=>n.updateDays(o),value:o,checked:s.workDays.includes(o)},null,8,["onUpdate:checked","value","checked"]),t("span",W,l(u),1)]))),256)),d(y,{onClick:n.saveWorkingDays,disabled:s.processing},{default:f(()=>[D("Save")]),_:1},8,["onClick","disabled"]),s.response.message.length>0?(r(),a("p",{key:0,class:x(`${s.response.errors===!0?"text-red-500":"text-green-500"}`)},l(s.response.message),3)):w("",!0)])],64)}const j=_(C,[["render",S]]);export{j as default};
