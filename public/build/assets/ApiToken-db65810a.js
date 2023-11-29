import{_ as u,a as d}from"./TextInput-58a0fa8b.js";import{_}from"./InputError-b8524fd7.js";import{P as f}from"./PrimaryButton-f6a2b288.js";import{_ as g}from"./_plugin-vue_export-helper-c27b6911.js";import{j as s,o as r,f as i,b as t,a as o,w as k,n as x,t as h,g as T,F as y,d as I}from"./app-9e0f9ab0.js";const b={name:"ApiToken",components:{InputError:_,InputLabel:u,PrimaryButton:f,TextInput:d},props:{apiToken:{type:String,default:""}},data(){return{processing:!1,response:{errors:!1,message:""}}},methods:{generateToken(){this.processing=!0,this.response.message="",axios.post(route("profile.generate-api-token")).then(n=>{this.processing=!1,this.response=n.data})}}},B=t("header",null,[t("h2",{class:"text-lg font-medium text-gray-900"},"Generate API token"),t("p",{class:"mt-1 text-sm text-gray-600"}," You need to generate api token in order to make api requests. Add it to Bearer Authorization header. ")],-1),A={class:"mt-6"};function C(n,V,a,v,e,p){const l=s("InputLabel"),m=s("TextInput"),c=s("PrimaryButton");return r(),i(y,null,[B,t("div",A,[o(l,{for:"apiToken",value:"Api Token"}),o(m,{id:"apiToken",type:"text",class:"mt-1 block w-full",readonly:"",modelValue:a.apiToken,value:a.apiToken},null,8,["modelValue","value"])]),o(c,{class:"mt-4",onClick:p.generateToken,disabled:e.processing},{default:k(()=>[I("Generate")]),_:1},8,["onClick","disabled"]),e.response.message.length>0?(r(),i("p",{key:0,class:x(`${e.response.errors===!0?"text-red-500":"text-green-500"}`)},h(e.response.message),3)):T("",!0)],64)}const E=g(b,[["render",C]]);export{E as default};
