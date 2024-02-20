import{_ as b}from"./InputError-86d41fc1.js";import{_ as U,a as O}from"./TextInput-958ab645.js";import{_ as B}from"./PrimaryButton-72f4ad8f.js";import{i as h,r as n,o as i,c as u,b as l,a as o,w as v,B as C,t as S,f as d,d as T}from"./app-7e0a5c6a.js";import{_ as E}from"./_plugin-vue_export-helper-c27b6911.js";const F={name:"OpretailnformationForm",components:{InputError:b,InputLabel:U,PrimaryButton:B,TextInput:O,Link:h},props:{user:{type:Object},opretail:{type:Object,default:{username:"",password:"",secret_key:"",_akey:"",_aid:"",enterpriseId:"",orgId:""}}},data(){return{form:this.opretail}},methods:{submitForm(){console.log(this.form),delete this.form.errors,axios.post(route("profile.opretail.update"),{form:this.form,user:this.user}).then(p=>{let r=p.data.errors;console.log(r),r?this.form.errors=r:window.location.reload()})}}},L={key:0,class:"p-4 md:p-8 bg-white shadow md:rounded-lg"},N=l("header",null,[l("h2",{class:"text-lg font-medium text-gray-900"},"Opretail Information"),l("p",{class:"mt-1 text-sm text-gray-600"}," Update your Opretail credentials. ")],-1),P={class:"mt-6 space-y-6 max-w-xl"},j={class:"flex items-center gap-4"},q={key:0,class:"text-sm text-gray-600"},D={key:0};function K(p,r,x,z,e,I){var f,c,_,y,g,k,V;const t=n("InputLabel"),m=n("TextInput"),a=n("InputError"),w=n("PrimaryButton");return x.user.parent_user_id===null?(i(),u("div",L,[N,l("div",P,[l("div",null,[o(t,{for:"o_username",value:"Opretail Username"}),o(m,{id:"o_username",type:"text",class:"mt-1 block w-full",modelValue:e.form.username,"onUpdate:modelValue":r[0]||(r[0]=s=>e.form.username=s),required:""},null,8,["modelValue"]),o(a,{class:"mt-2",message:(f=e.form.errors)==null?void 0:f.username},null,8,["message"])]),l("div",null,[o(t,{for:"opretail_password",value:"Opretail Password"}),o(m,{id:"opretail_password",type:"password",class:"mt-1 block w-full",modelValue:e.form.password,"onUpdate:modelValue":r[1]||(r[1]=s=>e.form.password=s)},null,8,["modelValue"]),o(a,{class:"mt-2",message:(c=e.form.errors)==null?void 0:c.password},null,8,["message"])]),l("div",null,[o(t,{for:"secret_key",value:"Secret Key"}),o(m,{id:"secret_key",type:"text",class:"mt-1 block w-full",modelValue:e.form.secret_key,"onUpdate:modelValue":r[2]||(r[2]=s=>e.form.secret_key=s)},null,8,["modelValue"]),o(a,{class:"mt-2",message:(_=e.form.errors)==null?void 0:_.secret_key},null,8,["message"])]),l("div",null,[o(t,{for:"_akey",value:"_akey"}),o(m,{id:"_akey",type:"text",class:"mt-1 block w-full",modelValue:e.form._akey,"onUpdate:modelValue":r[3]||(r[3]=s=>e.form._akey=s)},null,8,["modelValue"]),o(a,{class:"mt-2",message:(y=e.form.errors)==null?void 0:y._akey},null,8,["message"])]),l("div",null,[o(t,{for:"_aid",value:"_aid"}),o(m,{id:"_aid",type:"text",class:"mt-1 block w-full",modelValue:e.form._aid,"onUpdate:modelValue":r[4]||(r[4]=s=>e.form._aid=s)},null,8,["modelValue"]),o(a,{class:"mt-2",message:(g=e.form.errors)==null?void 0:g._aid},null,8,["message"])]),l("div",null,[o(t,{for:"enterpriseId",value:"enterpriseId"}),o(m,{id:"enterpriseId",type:"text",class:"mt-1 block w-full",modelValue:e.form.enterpriseId,"onUpdate:modelValue":r[5]||(r[5]=s=>e.form.enterpriseId=s)},null,8,["modelValue"]),o(a,{class:"mt-2",message:(k=e.form.errors)==null?void 0:k.enterpriseId},null,8,["message"])]),l("div",null,[o(t,{for:"orgId",value:"orgId"}),o(m,{id:"orgId",type:"text",class:"mt-1 block w-full",modelValue:e.form.orgId,"onUpdate:modelValue":r[6]||(r[6]=s=>e.form.orgId=s)},null,8,["modelValue"]),o(a,{class:"mt-2",message:(V=e.form.errors)==null?void 0:V.orgId},null,8,["message"])]),l("div",j,[o(w,{onClick:I.submitForm,disabled:e.form.processing},{default:v(()=>[T("Save")]),_:1},8,["onClick","disabled"]),o(C,{"enter-active-class":"transition ease-in-out","enter-from-class":"opacity-0","leave-active-class":"transition ease-in-out","leave-to-class":"opacity-0"},{default:v(()=>[e.form.recentlySuccessful?(i(),u("p",q,"Saved.")):d("",!0)]),_:1})]),!typeof e.form.errors==="object"?(i(),u("p",D,S(e.form.errors),1)):d("",!0)])])):d("",!0)}const Q=E(F,[["render",K]]);export{Q as default};
