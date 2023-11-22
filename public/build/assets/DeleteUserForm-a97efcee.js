import{o as _,f as x,r as g,v as $,x as C,G as B,h as D,c as V,a as t,w as l,M as f,P as y,b as e,I as p,n as b,g as E,R as U,s as h,T as S,y as T,d as w,u as d,L as A}from"./app-95558c48.js";import{_ as M}from"./_plugin-vue_export-helper-c27b6911.js";import{_ as N,a as P,b as I}from"./TextInput-45b01894.js";import{_ as K}from"./SecondaryButton-8addc342.js";const L={},O={class:"inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"};function W(a,n){return _(),x("button",O,[g(a.$slots,"default")])}const v=M(L,[["render",W]]),z={class:"fixed inset-0 overflow-y-auto px-4 py-6 md:px-0 z-50","scroll-region":""},F=e("div",{class:"absolute inset-0 bg-gray-500 opacity-75"},null,-1),j=[F],G={__name:"Modal",props:{show:{type:Boolean,default:!1},maxWidth:{type:String,default:"2xl"},closeable:{type:Boolean,default:!0}},emits:["close"],setup(a,{emit:n}){const o=a,s=n;$(()=>o.show,()=>{o.show?document.body.style.overflow="hidden":document.body.style.overflow=null});const i=()=>{o.closeable&&s("close")},r=u=>{u.key==="Escape"&&o.show&&i()};C(()=>document.addEventListener("keydown",r)),B(()=>{document.removeEventListener("keydown",r),document.body.style.overflow=null});const c=D(()=>({md:"md:max-w-sm",md:"md:max-w-md",lg:"md:max-w-lg",xl:"md:max-w-xl","2xl":"md:max-w-2xl"})[o.maxWidth]);return(u,m)=>(_(),V(U,{to:"body"},[t(p,{"leave-active-class":"duration-200"},{default:l(()=>[f(e("div",z,[t(p,{"enter-active-class":"ease-out duration-300","enter-from-class":"opacity-0","enter-to-class":"opacity-100","leave-active-class":"ease-in duration-200","leave-from-class":"opacity-100","leave-to-class":"opacity-0"},{default:l(()=>[f(e("div",{class:"fixed inset-0 transform transition-all",onClick:i},j,512),[[y,a.show]])]),_:1}),t(p,{"enter-active-class":"ease-out duration-300","enter-from-class":"opacity-0 translate-y-4 md:translate-y-0 md:scale-95","enter-to-class":"opacity-100 translate-y-0 md:scale-100","leave-active-class":"ease-in duration-200","leave-from-class":"opacity-100 translate-y-0 md:scale-100","leave-to-class":"opacity-0 translate-y-4 md:translate-y-0 md:scale-95"},{default:l(()=>[f(e("div",{class:b(["mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all md:w-full md:mx-auto",c.value])},[a.show?g(u.$slots,"default",{key:0}):E("",!0)],2),[[y,a.show]])]),_:3})],512),[[y,a.show]])]),_:3})]))}},R={class:"space-y-6"},q=e("header",null,[e("h2",{class:"text-lg font-medium text-gray-900"},"Delete Account"),e("p",{class:"mt-1 text-sm text-gray-600"}," Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain. ")],-1),H={class:"p-6"},J=e("h2",{class:"text-lg font-medium text-gray-900"}," Are you sure you want to delete your account? ",-1),Q=e("p",{class:"mt-1 text-sm text-gray-600"}," Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account. ",-1),X={class:"mt-6"},Y={class:"mt-6 flex justify-end"},oe={__name:"DeleteUserForm",setup(a){const n=h(!1),o=h(null),s=S({password:""}),i=()=>{n.value=!0,T(()=>o.value.focus())},r=()=>{s.delete(route("profile.destroy"),{preserveScroll:!0,onSuccess:()=>c(),onError:()=>o.value.focus(),onFinish:()=>s.reset()})},c=()=>{n.value=!1,s.reset()};return(u,m)=>(_(),x("section",R,[q,t(v,{onClick:i},{default:l(()=>[w("Delete Account")]),_:1}),t(G,{show:n.value,onClose:c},{default:l(()=>[e("div",H,[J,Q,e("div",X,[t(N,{for:"password",value:"Password",class:"sr-only"}),t(P,{id:"password",ref_key:"passwordInput",ref:o,modelValue:d(s).password,"onUpdate:modelValue":m[0]||(m[0]=k=>d(s).password=k),type:"password",class:"mt-1 block w-3/4",placeholder:"Password",onKeyup:A(r,["enter"])},null,8,["modelValue","onKeyup"]),t(I,{message:d(s).errors.password,class:"mt-2"},null,8,["message"])]),e("div",Y,[t(K,{onClick:c},{default:l(()=>[w(" Cancel ")]),_:1}),t(v,{class:b(["ms-3",{"opacity-25":d(s).processing}]),disabled:d(s).processing,onClick:r},{default:l(()=>[w(" Delete Account ")]),_:1},8,["class","disabled"])])])]),_:1},8,["show"])]))}};export{oe as default};
