import{x as L,G as B,h as v,s as S,o as u,f as m,b as e,r as c,M as k,P as $,a as r,w as n,n as l,I as M,c as _,u as h,i as y,d,t as b,g as N}from"./app-8af786f6.js";import{P as D}from"./pdf-logo-57d6eeaf.js";const E={class:"relative"},P={__name:"Dropdown",props:{align:{type:String,default:"right"},width:{type:String,default:"48"},contentClasses:{type:String,default:"py-1 bg-white"}},setup(o){const s=o,t=p=>{i.value&&p.key==="Escape"&&(i.value=!1)};L(()=>document.addEventListener("keydown",t)),B(()=>document.removeEventListener("keydown",t));const a=v(()=>({48:"w-48"})[s.width.toString()]),g=v(()=>s.align==="left"?"ltr:origin-top-left rtl:origin-top-right start-0":s.align==="right"?"ltr:origin-top-right rtl:origin-top-left end-0":s.align==="center"?"end-1/2 -translate-x-1/2 text-center":"origin-top"),i=S(!1);return(p,f)=>(u(),m("div",E,[e("div",{onClick:f[0]||(f[0]=w=>i.value=!i.value)},[c(p.$slots,"trigger")]),k(e("div",{class:"fixed inset-0 z-40",onClick:f[1]||(f[1]=w=>i.value=!1)},null,512),[[$,i.value]]),r(M,{"enter-active-class":"transition ease-out duration-200","enter-from-class":"opacity-0 scale-95","enter-to-class":"opacity-100 scale-100","leave-active-class":"transition ease-in duration-75","leave-from-class":"opacity-100 scale-100","leave-to-class":"opacity-0 scale-95"},{default:n(()=>[k(e("div",{class:l(["absolute z-50 mt-2 rounded-md shadow-lg",[a.value,g.value]]),style:{display:"none"},onClick:f[2]||(f[2]=w=>i.value=!1)},[e("div",{class:l(["rounded-md ring-1 ring-black ring-opacity-5",o.contentClasses])},[c(p.$slots,"content")],2)],2),[[$,i.value]])]),_:3})]))}},C={__name:"DropdownLink",props:{href:{type:String,required:!0},align:{type:String,default:"start"}},setup(o){return(s,t)=>(u(),_(h(y),{href:o.href,class:l(["block w-full px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out",`${o.align==="start"?"text-start":o.align==="center"?"text-center":"text-end"}`])},{default:n(()=>[c(s.$slots,"default")]),_:3},8,["href","class"]))}},j={__name:"NavLink",props:{href:{type:String,required:!0},active:{type:Boolean}},setup(o){const s=o,t=v(()=>s.active?"inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out":"inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out");return(a,g)=>(u(),_(h(y),{href:o.href,class:l(t.value)},{default:n(()=>[c(a.$slots,"default")]),_:3},8,["href","class"]))}},x={__name:"ResponsiveNavLink",props:{href:{type:String,required:!0},active:{type:Boolean}},setup(o){const s=o,t=v(()=>s.active?"block w-full ps-3 pe-4 py-2 border-l-4 border-indigo-400 text-start text-base font-medium text-indigo-700 bg-indigo-50 focus:outline-none focus:text-indigo-800 focus:bg-indigo-100 focus:border-indigo-700 transition duration-150 ease-in-out":"block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out");return(a,g)=>(u(),_(h(y),{href:o.href,class:l(t.value)},{default:n(()=>[c(a.$slots,"default")]),_:3},8,["href","class"]))}},z={class:"min-h-screen bg-gray-100"},V={class:"bg-white border-b border-gray-100"},q={class:"max-w-7xl mx-auto px-4 md:px-6 lg:px-8"},O={class:"flex justify-between h-16"},T={class:"flex"},A={class:"shrink-0 flex items-center"},G={class:"hidden space-x-8 md:-my-px md:ms-10 md:flex"},I={class:"hidden md:flex md:items-center md:ms-6"},R={class:"ms-3 relative"},U={class:"inline-flex rounded-md"},F={type:"button",class:"inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"},H=e("svg",{class:"ms-2 -me-0.5 h-4 w-4",xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 20 20",fill:"currentColor"},[e("path",{"fill-rule":"evenodd",d:"M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z","clip-rule":"evenodd"})],-1),J={class:"-me-2 flex items-center md:hidden"},K={class:"h-6 w-6",stroke:"currentColor",fill:"none",viewBox:"0 0 24 24"},Q={class:"pt-2 pb-3 space-y-1"},W={class:"pt-4 pb-1 border-t border-gray-200"},X={class:"px-4"},Y={class:"font-medium text-base text-gray-800"},Z={class:"font-medium text-sm text-gray-500"},ee={class:"mt-3 space-y-1"},te={key:0,class:"bg-white shadow"},se={class:"max-w-7xl mx-auto py-6 px-4 md:px-6 lg:px-8"},re={__name:"AuthenticatedLayout",setup(o){const s=S(!1);return(t,a)=>(u(),m("div",null,[e("div",z,[e("nav",V,[e("div",q,[e("div",O,[e("div",T,[e("div",A,[r(h(y),{href:t.route("dashboard")},{default:n(()=>[r(h(D),{width:"100",height:"50"})]),_:1},8,["href"])]),e("div",G,[r(j,{href:t.route("home"),active:t.route().current("home")},{default:n(()=>[d(" Statistic ")]),_:1},8,["href","active"])])]),e("div",I,[e("div",R,[r(P,{align:"right",width:"48"},{trigger:n(()=>[e("span",U,[e("button",F,[d(b(t.$page.props.auth.user.name)+" ",1),H])])]),content:n(()=>[r(C,{href:t.route("profile.edit")},{default:n(()=>[d(" Profile ")]),_:1},8,["href"]),r(C,{href:t.route("logout"),method:"post",as:"button"},{default:n(()=>[d(" Log Out ")]),_:1},8,["href"])]),_:1})])]),e("div",J,[e("button",{onClick:a[0]||(a[0]=g=>s.value=!s.value),class:"inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"},[(u(),m("svg",K,[e("path",{class:l({hidden:s.value,"inline-flex":!s.value}),"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M4 6h16M4 12h16M4 18h16"},null,2),e("path",{class:l({hidden:!s.value,"inline-flex":s.value}),"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M6 18L18 6M6 6l12 12"},null,2)]))])])])]),e("div",{class:l([{block:s.value,hidden:!s.value},"md:hidden"])},[e("div",Q,[r(x,{href:t.route("dashboard"),active:t.route().current("dashboard")},{default:n(()=>[d(" Dashboard ")]),_:1},8,["href","active"])]),e("div",W,[e("div",X,[e("div",Y,b(t.$page.props.auth.user.name),1),e("div",Z,b(t.$page.props.auth.user.email),1)]),e("div",ee,[r(x,{href:t.route("profile.edit")},{default:n(()=>[d(" Profile ")]),_:1},8,["href"]),r(x,{href:t.route("logout"),method:"post",as:"button"},{default:n(()=>[d(" Log Out ")]),_:1},8,["href"])])])],2)]),t.$slots.header?(u(),m("header",te,[e("div",se,[c(t.$slots,"header")])])):N("",!0),e("main",null,[c(t.$slots,"default")])])]))}};export{re as _,P as a,C as b};
