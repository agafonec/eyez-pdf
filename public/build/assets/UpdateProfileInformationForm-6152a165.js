import{Q as n,T as g,o as m,c as u,b as s,a,u as e,d,w as c,M as h,U as x,f as p,J as V,h as b,i as k}from"./app-98a89079.js";import{_ as v}from"./InputError-37ae8931.js";import{a as y,_}from"./TextInput-5e159b10.js";import{P as w}from"./PrimaryButton-db4d4b28.js";import"./_plugin-vue_export-helper-c27b6911.js";const U=s("header",null,[s("h2",{class:"text-lg font-medium text-gray-900"},"Profile Information"),s("p",{class:"mt-1 text-sm text-gray-600"}," Update your account's profile information and email address. ")],-1),S={key:0},B={class:"text-sm mt-2 text-gray-800"},N={class:"mt-2 font-medium text-sm text-green-600"},E={class:"flex items-center gap-4"},P={key:0,class:"text-sm text-gray-600"},M={__name:"UpdateProfileInformationForm",props:{currentUser:{type:Object,default:null},mustVerifyEmail:{type:Boolean},status:{type:String}},setup(f){const r=n().props.currentUser||n().props.auth.user,t=g({name:r.name,email:r.email,user:r});return(l,o)=>(m(),u("section",null,[U,s("form",{onSubmit:o[2]||(o[2]=b(i=>e(n)().props.currentUser===void 0?e(t).patch(l.route("profile.update")):e(t).patch(l.route("profile.update.other")),["prevent"])),class:"mt-6 space-y-6"},[s("div",null,[a(_,{for:"name",value:"Name"}),a(y,{id:"name",type:"text",class:"mt-1 block w-full",modelValue:e(t).name,"onUpdate:modelValue":o[0]||(o[0]=i=>e(t).name=i),required:"",autofocus:"",autocomplete:"name"},null,8,["modelValue"]),a(v,{class:"mt-2",message:e(t).errors.name},null,8,["message"])]),s("div",null,[a(_,{for:"email",value:"Email"}),a(y,{id:"email",type:"email",class:"mt-1 block w-full",modelValue:e(t).email,"onUpdate:modelValue":o[1]||(o[1]=i=>e(t).email=i),required:"",autocomplete:"username"},null,8,["modelValue"]),a(v,{class:"mt-2",message:e(t).errors.email},null,8,["message"])]),f.mustVerifyEmail&&e(r).email_verified_at===null?(m(),u("div",S,[s("p",B,[d(" Your email address is unverified. "),a(e(k),{href:l.route("verification.send"),method:"post",as:"button",class:"underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"},{default:c(()=>[d(" Click here to re-send the verification email. ")]),_:1},8,["href"])]),h(s("div",N," A new verification link has been sent to your email address. ",512),[[x,f.status==="verification-link-sent"]])])):p("",!0),s("div",E,[a(w,{disabled:e(t).processing},{default:c(()=>[d("Save")]),_:1},8,["disabled"]),a(V,{"enter-active-class":"transition ease-in-out","enter-from-class":"opacity-0","leave-active-class":"transition ease-in-out","leave-to-class":"opacity-0"},{default:c(()=>[e(t).recentlySuccessful?(m(),u("p",P,"Saved.")):p("",!0)]),_:1})])],32)]))}};export{M as default};
