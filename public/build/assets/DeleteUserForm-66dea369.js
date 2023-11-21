import{o as m,f as h,r as x,x as $,y as C,H as B,h as D,c as S,a,w as l,N as p,Q as y,b as e,J as w,n as b,g as V,R as E,v,T as U,z as N,d as _,u as d,M as T}from"./app-57d6f950.js";import{_ as A}from"./_plugin-vue_export-helper-c27b6911.js";import{_ as M,a as z,b as K}from"./TextInput-4f3cbb53.js";const O={},P={class:"inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"};function W(t,n){return m(),h("button",P,[x(t.$slots,"default")])}const g=A(O,[["render",W]]),F={class:"fixed inset-0 overflow-y-auto px-4 py-6 md:px-0 z-50","scroll-region":""},I=e("div",{class:"absolute inset-0 bg-gray-500 opacity-75"},null,-1),L=[I],j={__name:"Modal",props:{show:{type:Boolean,default:!1},maxWidth:{type:String,default:"2xl"},closeable:{type:Boolean,default:!0}},emits:["close"],setup(t,{emit:n}){const s=t,o=n;$(()=>s.show,()=>{s.show?document.body.style.overflow="hidden":document.body.style.overflow=null});const i=()=>{s.closeable&&o("close")},r=u=>{u.key==="Escape"&&s.show&&i()};C(()=>document.addEventListener("keydown",r)),B(()=>{document.removeEventListener("keydown",r),document.body.style.overflow=null});const c=D(()=>({md:"md:max-w-sm",md:"md:max-w-md",lg:"md:max-w-lg",xl:"md:max-w-xl","2xl":"md:max-w-2xl"})[s.maxWidth]);return(u,f)=>(m(),S(E,{to:"body"},[a(w,{"leave-active-class":"duration-200"},{default:l(()=>[p(e("div",F,[a(w,{"enter-active-class":"ease-out duration-300","enter-from-class":"opacity-0","enter-to-class":"opacity-100","leave-active-class":"ease-in duration-200","leave-from-class":"opacity-100","leave-to-class":"opacity-0"},{default:l(()=>[p(e("div",{class:"fixed inset-0 transform transition-all",onClick:i},L,512),[[y,t.show]])]),_:1}),a(w,{"enter-active-class":"ease-out duration-300","enter-from-class":"opacity-0 translate-y-4 md:translate-y-0 md:scale-95","enter-to-class":"opacity-100 translate-y-0 md:scale-100","leave-active-class":"ease-in duration-200","leave-from-class":"opacity-100 translate-y-0 md:scale-100","leave-to-class":"opacity-0 translate-y-4 md:translate-y-0 md:scale-95"},{default:l(()=>[p(e("div",{class:b(["mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all md:w-full md:mx-auto",c.value])},[t.show?x(u.$slots,"default",{key:0}):V("",!0)],2),[[y,t.show]])]),_:3})],512),[[y,t.show]])]),_:3})]))}},H=["type"],J={__name:"SecondaryButton",props:{type:{type:String,default:"button"}},setup(t){return(n,s)=>(m(),h("button",{type:t.type,class:"inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"},[x(n.$slots,"default")],8,H))}},Q={class:"space-y-6"},R=e("header",null,[e("h2",{class:"text-lg font-medium text-gray-900"},"Delete Account"),e("p",{class:"mt-1 text-sm text-gray-600"}," Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain. ")],-1),q={class:"p-6"},G=e("h2",{class:"text-lg font-medium text-gray-900"}," Are you sure you want to delete your account? ",-1),X=e("p",{class:"mt-1 text-sm text-gray-600"}," Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account. ",-1),Y={class:"mt-6"},Z={class:"mt-6 flex justify-end"},oe={__name:"DeleteUserForm",setup(t){const n=v(!1),s=v(null),o=U({password:""}),i=()=>{n.value=!0,N(()=>s.value.focus())},r=()=>{o.delete(route("profile.destroy"),{preserveScroll:!0,onSuccess:()=>c(),onError:()=>s.value.focus(),onFinish:()=>o.reset()})},c=()=>{n.value=!1,o.reset()};return(u,f)=>(m(),h("section",Q,[R,a(g,{onClick:i},{default:l(()=>[_("Delete Account")]),_:1}),a(j,{show:n.value,onClose:c},{default:l(()=>[e("div",q,[G,X,e("div",Y,[a(M,{for:"password",value:"Password",class:"sr-only"}),a(z,{id:"password",ref_key:"passwordInput",ref:s,modelValue:d(o).password,"onUpdate:modelValue":f[0]||(f[0]=k=>d(o).password=k),type:"password",class:"mt-1 block w-3/4",placeholder:"Password",onKeyup:T(r,["enter"])},null,8,["modelValue","onKeyup"]),a(K,{message:d(o).errors.password,class:"mt-2"},null,8,["message"])]),e("div",Z,[a(J,{onClick:c},{default:l(()=>[_(" Cancel ")]),_:1}),a(g,{class:b(["ms-3",{"opacity-25":d(o).processing}]),disabled:d(o).processing,onClick:r},{default:l(()=>[_(" Delete Account ")]),_:1},8,["class","disabled"])])])]),_:1},8,["show"])]))}};export{oe as default};
