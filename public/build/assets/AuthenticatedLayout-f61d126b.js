import{A as x}from"./ApplicationLogo-3ca58ede.js";import{a as v,_ as y}from"./DropdownLink-f72dad18.js";import{h as _,o as d,c as b,w as o,r as u,n as l,u as p,j as m,p as w,f as c,b as e,a as r,d as a,t as h,g as k}from"./app-6737ccd8.js";const $={__name:"NavLink",props:{href:{type:String,required:!0},active:{type:Boolean}},setup(n){const s=n,t=_(()=>s.active?"inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out":"inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out");return(i,g)=>(d(),b(p(m),{href:n.href,class:l(t.value)},{default:o(()=>[u(i.$slots,"default")]),_:3},8,["href","class"]))}},f={__name:"ResponsiveNavLink",props:{href:{type:String,required:!0},active:{type:Boolean}},setup(n){const s=n,t=_(()=>s.active?"block w-full ps-3 pe-4 py-2 border-l-4 border-indigo-400 text-start text-base font-medium text-indigo-700 bg-indigo-50 focus:outline-none focus:text-indigo-800 focus:bg-indigo-100 focus:border-indigo-700 transition duration-150 ease-in-out":"block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out");return(i,g)=>(d(),b(p(m),{href:n.href,class:l(t.value)},{default:o(()=>[u(i.$slots,"default")]),_:3},8,["href","class"]))}},B={class:"min-h-screen bg-gray-100"},L={class:"bg-white border-b border-gray-100"},N={class:"max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"},C={class:"flex justify-between h-16"},M={class:"flex"},j={class:"shrink-0 flex items-center"},D={class:"hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"},S={class:"hidden sm:flex sm:items-center sm:ms-6"},V={class:"ms-3 relative"},A={class:"inline-flex rounded-md"},q={type:"button",class:"inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"},z=e("svg",{class:"ms-2 -me-0.5 h-4 w-4",xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 20 20",fill:"currentColor"},[e("path",{"fill-rule":"evenodd",d:"M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z","clip-rule":"evenodd"})],-1),O={class:"-me-2 flex items-center sm:hidden"},P={class:"h-6 w-6",stroke:"currentColor",fill:"none",viewBox:"0 0 24 24"},E={class:"pt-2 pb-3 space-y-1"},R={class:"pt-4 pb-1 border-t border-gray-200"},T={class:"px-4"},F={class:"font-medium text-base text-gray-800"},G={class:"font-medium text-sm text-gray-500"},H={class:"mt-3 space-y-1"},I={key:0,class:"bg-white shadow"},J={class:"max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8"},W={__name:"AuthenticatedLayout",setup(n){const s=w(!1);return(t,i)=>(d(),c("div",null,[e("div",B,[e("nav",L,[e("div",N,[e("div",C,[e("div",M,[e("div",j,[r(p(m),{href:t.route("dashboard")},{default:o(()=>[r(x,{class:"block h-9 w-auto fill-current text-gray-800"})]),_:1},8,["href"])]),e("div",D,[r($,{href:t.route("dashboard"),active:t.route().current("dashboard")},{default:o(()=>[a(" Dashboard ")]),_:1},8,["href","active"])])]),e("div",S,[e("div",V,[r(y,{align:"right",width:"48"},{trigger:o(()=>[e("span",A,[e("button",q,[a(h(t.$page.props.auth.user.name)+" ",1),z])])]),content:o(()=>[r(v,{href:t.route("profile.edit")},{default:o(()=>[a(" Profile ")]),_:1},8,["href"]),r(v,{href:t.route("logout"),method:"post",as:"button"},{default:o(()=>[a(" Log Out ")]),_:1},8,["href"])]),_:1})])]),e("div",O,[e("button",{onClick:i[0]||(i[0]=g=>s.value=!s.value),class:"inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"},[(d(),c("svg",P,[e("path",{class:l({hidden:s.value,"inline-flex":!s.value}),"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M4 6h16M4 12h16M4 18h16"},null,2),e("path",{class:l({hidden:!s.value,"inline-flex":s.value}),"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M6 18L18 6M6 6l12 12"},null,2)]))])])])]),e("div",{class:l([{block:s.value,hidden:!s.value},"sm:hidden"])},[e("div",E,[r(f,{href:t.route("dashboard"),active:t.route().current("dashboard")},{default:o(()=>[a(" Dashboard ")]),_:1},8,["href","active"])]),e("div",R,[e("div",T,[e("div",F,h(t.$page.props.auth.user.name),1),e("div",G,h(t.$page.props.auth.user.email),1)]),e("div",H,[r(f,{href:t.route("profile.edit")},{default:o(()=>[a(" Profile ")]),_:1},8,["href"]),r(f,{href:t.route("logout"),method:"post",as:"button"},{default:o(()=>[a(" Log Out ")]),_:1},8,["href"])])])],2)]),t.$slots.header?(d(),c("header",I,[e("div",J,[u(t.$slots,"header")])])):k("",!0),e("main",null,[u(t.$slots,"default")])])]))}};export{W as _};
