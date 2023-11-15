import{b as I,_ as v,a as w}from"./TextInput-e81c5bf4.js";import{P as b}from"./PrimaryButton-6903f434.js";import{j as U,l as n,o as p,f as g,b as r,a as t,w as k,z as q,F as O,d as B,g as F}from"./app-930c4b1b.js";import{_ as C}from"./_plugin-vue_export-helper-c27b6911.js";const P={name:"OpretailnformationForm",components:{InputError:I,InputLabel:v,PrimaryButton:b,TextInput:w,Link:U},props:{form:{type:Object,default:{username:"",password:"",secret_key:"",_akey:"",_aid:"",enterpriseId:"",orgId:""}}},methods:{submitForm(){axios.post(route("profile.opretail.update"),this.form)}}},T=r("header",null,[r("h2",{class:"text-lg font-medium text-gray-900"},"Opretail Information"),r("p",{class:"mt-1 text-sm text-gray-600"}," Update your Opretail credentials. ")],-1),E={class:"mt-6 space-y-6 max-w-xl"},L={class:"flex items-center gap-4"},N={key:0,class:"text-sm text-gray-600"};function S(j,l,e,z,K,V){var d,u,i,f,c,_,y;const s=n("InputLabel"),o=n("TextInput"),m=n("InputError"),x=n("PrimaryButton");return p(),g(O,null,[T,r("form",E,[r("div",null,[t(s,{for:"username",value:"Opretail Username"}),t(o,{id:"username",type:"text",class:"mt-1 block w-full",modelValue:e.form.username,"onUpdate:modelValue":l[0]||(l[0]=a=>e.form.username=a),required:"",autofocus:"",autocomplete:"username"},null,8,["modelValue"]),t(m,{class:"mt-2",message:(d=e.form.errors)==null?void 0:d.username},null,8,["message"])]),r("div",null,[t(s,{for:"opretail_password",value:"Opretail Password"}),t(o,{id:"opretail_password",type:"password",class:"mt-1 block w-full",modelValue:e.form.password,"onUpdate:modelValue":l[1]||(l[1]=a=>e.form.password=a),required:""},null,8,["modelValue"]),t(m,{class:"mt-2",message:(u=e.form.errors)==null?void 0:u.password},null,8,["message"])]),r("div",null,[t(s,{for:"secret_key",value:"Secret Key"}),t(o,{id:"secret_key",type:"text",class:"mt-1 block w-full",modelValue:e.form.secret_key,"onUpdate:modelValue":l[2]||(l[2]=a=>e.form.secret_key=a),required:""},null,8,["modelValue"]),t(m,{class:"mt-2",message:(i=e.form.errors)==null?void 0:i.secret_key},null,8,["message"])]),r("div",null,[t(s,{for:"_akey",value:"_akey"}),t(o,{id:"_akey",type:"text",class:"mt-1 block w-full",modelValue:e.form._akey,"onUpdate:modelValue":l[3]||(l[3]=a=>e.form._akey=a),required:""},null,8,["modelValue"]),t(m,{class:"mt-2",message:(f=e.form.errors)==null?void 0:f._akey},null,8,["message"])]),r("div",null,[t(s,{for:"_aid",value:"_aid"}),t(o,{id:"_aid",type:"text",class:"mt-1 block w-full",modelValue:e.form._aid,"onUpdate:modelValue":l[4]||(l[4]=a=>e.form._aid=a),required:""},null,8,["modelValue"]),t(m,{class:"mt-2",message:(c=e.form.errors)==null?void 0:c._aid},null,8,["message"])]),r("div",null,[t(s,{for:"enterpriseId",value:"enterpriseId"}),t(o,{id:"enterpriseId",type:"text",class:"mt-1 block w-full",modelValue:e.form.enterpriseId,"onUpdate:modelValue":l[5]||(l[5]=a=>e.form.enterpriseId=a),required:""},null,8,["modelValue"]),t(m,{class:"mt-2",message:(_=e.form.errors)==null?void 0:_.enterpriseId},null,8,["message"])]),r("div",null,[t(s,{for:"orgId",value:"orgId"}),t(o,{id:"orgId",type:"text",class:"mt-1 block w-full",modelValue:e.form.orgId,"onUpdate:modelValue":l[6]||(l[6]=a=>e.form.orgId=a),required:""},null,8,["modelValue"]),t(m,{class:"mt-2",message:(y=e.form.errors)==null?void 0:y.orgId},null,8,["message"])]),r("div",L,[t(x,{onClick:V.submitForm,disabled:e.form.processing},{default:k(()=>[B("Save")]),_:1},8,["onClick","disabled"]),t(q,{"enter-active-class":"transition ease-in-out","enter-from-class":"opacity-0","leave-active-class":"transition ease-in-out","leave-to-class":"opacity-0"},{default:k(()=>[e.form.recentlySuccessful?(p(),g("p",N,"Saved.")):F("",!0)]),_:1})])])],64)}const J=C(P,[["render",S]]);export{J as default};
