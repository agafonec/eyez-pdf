import{T as d,o as r,g as u,w as i,a as t,u as s,Z as c,c as _,t as f,f as p,b as a,d as w,n as g,h as y}from"./app-e8d2e45e.js";import{_ as b}from"./GuestLayout-c2b423f5.js";import{_ as x}from"./InputError-fdfc1824.js";import{_ as h}from"./InputLabel-57feda78.js";import{_ as k}from"./PrimaryButton-a935a37a.js";import{_ as V}from"./TextInput-f3aa56d7.js";import"./pdf-logo-00fc44ec.js";import"./_plugin-vue_export-helper-c27b6911.js";const v=a("div",{class:"mb-4 text-sm text-gray-600"}," Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one. ",-1),N={key:0,class:"mb-4 font-medium text-sm text-green-600"},$=["onSubmit"],B={class:"flex items-center justify-end mt-4"},D={__name:"ForgotPassword",props:{status:{type:String}},setup(o){const e=d({email:""}),m=()=>{e.post(route("password.email"))};return(S,l)=>(r(),u(b,null,{default:i(()=>[t(s(c),{title:"Forgot Password"}),v,o.status?(r(),_("div",N,f(o.status),1)):p("",!0),a("form",{onSubmit:y(m,["prevent"])},[a("div",null,[t(h,{for:"email",value:"Email"}),t(V,{id:"email",type:"email",class:"mt-1 block w-full",modelValue:s(e).email,"onUpdate:modelValue":l[0]||(l[0]=n=>s(e).email=n),required:"",autofocus:"",autocomplete:"username"},null,8,["modelValue"]),t(x,{class:"mt-2",message:s(e).errors.email},null,8,["message"])]),a("div",B,[t(k,{class:g({"opacity-25":s(e).processing}),disabled:s(e).processing},{default:i(()=>[w(" Email Password Reset Link ")]),_:1},8,["class","disabled"])])],40,$)]),_:1}))}};export{D as default};