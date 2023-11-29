import{P as _}from"./PrimaryButton-f6a2b288.js";import{_ as x}from"./Checkbox-24c0a734.js";import{_ as k,a as b}from"./TextInput-58a0fa8b.js";import{_ as v}from"./_plugin-vue_export-helper-c27b6911.js";import{j as u,o as d,f as i,b as s,F as c,B as w,a,w as V,n as D,t as y,g as G,d as C}from"./app-9e0f9ab0.js";const Y={name:"WorkingDays",components:{PrimaryButton:_,Checkbox:x,InputLabel:k,TextInput:b},props:{settings:[Object,Array]},data(){var o,t;return{processing:!1,workDays:((o=this.settings)==null?void 0:o.workdays)??[],ageGroups:((t=this.settings)==null?void 0:t.ageGroups)??{earlyYouth:"Early Youth",youth:"Youth",middleAge:"Middle Age",elder:"Elderly"},response:{errors:!1,message:""}}},methods:{saveWorkingDays(){this.processing=!0,this.response.message="",axios.post(route("profile.workdays.update"),{workdays:this.workDays,ageGroups:this.ageGroups}).then(o=>{this.processing=!1,this.response=o.data})},updateDays(o){this.workDays.includes(o)?this.workDays.splice(this.workDays.indexOf(o),1):this.workDays.push(o)}},setup(){return{days:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"]}}},A=s("header",null,[s("h2",{class:"text-lg font-medium text-gray-900"},"Off Days"),s("p",{class:"mt-1 text-sm text-gray-600"}," Set your off days. ")],-1),B={class:"flex items-center gap-4 mt-4"},T={class:"flex items-center"},I={class:"ms-2 text-sm text-gray-600"},S=s("header",{class:"mt-8"},[s("h2",{class:"text-lg font-medium text-gray-900"},"Title for age groups."),s("p",{class:"mt-1 text-sm text-gray-600"}," Change the titles for age groups in dount chart. ")],-1),U={class:"max-w-lg mt-4"},E={class:"mb-2"},q={class:"mb-2"},L={class:"mb-2"},N={class:"mb-2"};function P(o,t,W,g,e,p){const h=u("Checkbox"),l=u("InputLabel"),n=u("TextInput"),f=u("PrimaryButton");return d(),i(c,null,[A,s("div",B,[(d(!0),i(c,null,w(g.days,(r,m)=>(d(),i("label",T,[a(h,{name:"off_days","onUpdate:checked":F=>p.updateDays(m),value:m,checked:e.workDays.includes(m)},null,8,["onUpdate:checked","value","checked"]),s("span",I,y(r),1)]))),256))]),S,s("div",U,[s("div",E,[a(l,{for:"earlyYouth",value:"Early Youth"}),a(n,{id:"earlyYouth",type:"text",class:"mt-1 block w-full",modelValue:e.ageGroups.earlyYouth,"onUpdate:modelValue":t[0]||(t[0]=r=>e.ageGroups.earlyYouth=r),required:""},null,8,["modelValue"])]),s("div",q,[a(l,{for:"youth",value:"Youth"}),a(n,{id:"youth",type:"text",class:"mt-1 block w-full",modelValue:e.ageGroups.youth,"onUpdate:modelValue":t[1]||(t[1]=r=>e.ageGroups.youth=r),required:""},null,8,["modelValue"])]),s("div",L,[a(l,{for:"middleAge",value:"Middle Age"}),a(n,{id:"middleAge",type:"text",class:"mt-1 block w-full",modelValue:e.ageGroups.middleAge,"onUpdate:modelValue":t[2]||(t[2]=r=>e.ageGroups.middleAge=r),required:""},null,8,["modelValue"])]),s("div",N,[a(l,{for:"elder",value:"Elderly"}),a(n,{id:"elder",type:"text",class:"mt-1 block w-full",modelValue:e.ageGroups.elder,"onUpdate:modelValue":t[3]||(t[3]=r=>e.ageGroups.elder=r),required:""},null,8,["modelValue"])]),a(f,{class:"mt-2",onClick:p.saveWorkingDays,disabled:e.processing},{default:V(()=>[C("Save")]),_:1},8,["onClick","disabled"]),e.response.message.length>0?(d(),i("p",{key:0,class:D(`${e.response.errors===!0?"text-red-500":"text-green-500"}`)},y(e.response.message),3)):G("",!0)])],64)}const J=v(Y,[["render",P]]);export{J as default};
