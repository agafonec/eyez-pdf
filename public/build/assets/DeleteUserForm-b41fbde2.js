import{s as b,v as k,x as C,j as D,o as v,g as B,a as t,w as a,y as f,z as y,b as e,A as p,n as x,k as $,f as E,B as V,C as _,T as U,c as A,D as S,d as w,u as d,E as T}from"./app-e8d2e45e.js";import{D as h}from"./DangerButton-cb891795.js";import{_ as N}from"./InputError-fdfc1824.js";import{_ as z}from"./InputLabel-57feda78.js";import{_ as K}from"./SecondaryButton-d80db504.js";import{_ as M}from"./TextInput-f3aa56d7.js";import"./_plugin-vue_export-helper-c27b6911.js";const O={class:"fixed inset-0 overflow-y-auto px-4 py-6 md:px-0 z-50","scroll-region":""},P=e("div",{class:"absolute inset-0 bg-gray-500 opacity-75"},null,-1),W=[P],j={__name:"Modal",props:{show:{type:Boolean,default:!1},maxWidth:{type:String,default:"2xl"},closeable:{type:Boolean,default:!0}},emits:["close"],setup(l,{emit:n}){const o=l,s=n;b(()=>o.show,()=>{o.show?document.body.style.overflow="hidden":document.body.style.overflow=null});const i=()=>{o.closeable&&s("close")},r=m=>{m.key==="Escape"&&o.show&&i()};k(()=>document.addEventListener("keydown",r)),C(()=>{document.removeEventListener("keydown",r),document.body.style.overflow=null});const c=D(()=>({md:"md:max-w-sm",md:"md:max-w-md",lg:"md:max-w-lg",xl:"md:max-w-xl","2xl":"md:max-w-2xl"})[o.maxWidth]);return(m,u)=>(v(),B(V,{to:"body"},[t(p,{"leave-active-class":"duration-200"},{default:a(()=>[f(e("div",O,[t(p,{"enter-active-class":"ease-out duration-300","enter-from-class":"opacity-0","enter-to-class":"opacity-100","leave-active-class":"ease-in duration-200","leave-from-class":"opacity-100","leave-to-class":"opacity-0"},{default:a(()=>[f(e("div",{class:"fixed inset-0 transform transition-all",onClick:i},W,512),[[y,l.show]])]),_:1}),t(p,{"enter-active-class":"ease-out duration-300","enter-from-class":"opacity-0 translate-y-4 md:translate-y-0 md:scale-95","enter-to-class":"opacity-100 translate-y-0 md:scale-100","leave-active-class":"ease-in duration-200","leave-from-class":"opacity-100 translate-y-0 md:scale-100","leave-to-class":"opacity-0 translate-y-4 md:translate-y-0 md:scale-95"},{default:a(()=>[f(e("div",{class:x(["mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all md:w-full md:mx-auto",c.value])},[l.show?$(m.$slots,"default",{key:0}):E("",!0)],2),[[y,l.show]])]),_:3})],512),[[y,l.show]])]),_:3})]))}},F={class:"space-y-6"},I=e("header",null,[e("h2",{class:"text-lg font-medium text-gray-900"},"Delete Account"),e("p",{class:"mt-1 text-sm text-gray-600"}," Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain. ")],-1),L={class:"p-6"},q=e("h2",{class:"text-lg font-medium text-gray-900"}," Are you sure you want to delete your account? ",-1),G=e("p",{class:"mt-1 text-sm text-gray-600"}," Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account. ",-1),H={class:"mt-6"},J={class:"mt-6 flex justify-end"},te={__name:"DeleteUserForm",setup(l){const n=_(!1),o=_(null),s=U({password:""}),i=()=>{n.value=!0,S(()=>o.value.focus())},r=()=>{s.delete(route("profile.destroy"),{preserveScroll:!0,onSuccess:()=>c(),onError:()=>o.value.focus(),onFinish:()=>s.reset()})},c=()=>{n.value=!1,s.reset()};return(m,u)=>(v(),A("section",F,[I,t(h,{onClick:i},{default:a(()=>[w("Delete Account")]),_:1}),t(j,{show:n.value,onClose:c},{default:a(()=>[e("div",L,[q,G,e("div",H,[t(z,{for:"password",value:"Password",class:"sr-only"}),t(M,{id:"password",ref_key:"passwordInput",ref:o,modelValue:d(s).password,"onUpdate:modelValue":u[0]||(u[0]=g=>d(s).password=g),type:"password",class:"mt-1 block w-3/4",placeholder:"Password",onKeyup:T(r,["enter"])},null,8,["modelValue","onKeyup"]),t(N,{message:d(s).errors.password,class:"mt-2"},null,8,["message"])]),e("div",J,[t(K,{onClick:c},{default:a(()=>[w(" Cancel ")]),_:1}),t(h,{class:x(["ms-3",{"opacity-25":d(s).processing}]),disabled:d(s).processing,onClick:r},{default:a(()=>[w(" Delete Account ")]),_:1},8,["class","disabled"])])])]),_:1},8,["show"])]))}};export{te as default};