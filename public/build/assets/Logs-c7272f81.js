import{_ as u}from"./AuthenticatedLayout-6e0aa9e2.js";import{h as p}from"./moment-fbc5633a.js";import{_ as x}from"./_plugin-vue_export-helper-c27b6911.js";import{r as c,o as a,c as o,a as i,w as y,F as n,b as t,d as l,t as r,e as g,n as b}from"./app-f7a59c7f.js";import"./pdf-logo-b6e28c85.js";const f={name:"Logs",components:{AuthenticatedLayout:u},props:{logs:{type:Object,required:!0},user:{type:Object,required:!0}},methods:{dateFormat(d){return p(d).format("YYYY-MM-DD HH:mm:ss")}}},w={class:"py-12"},v={class:"max-w-7xl mx-auto md:px-6 lg:px-8 space-y-6"},k={class:"p-4 md:p-8 bg-white shadow md:rounded-lg gap-5"},L={class:"w-full mb-4"},H={class:"text-lg font-medium text-gray-900 mb-4"},T={class:"font-semibold"},j={key:0,class:"relative overflow-x-auto sm:rounded-lg"},C={class:"w-full text-sm text-right rtl:text-right text-gray-500 dark:text-gray-400 shadow-md"},D=t("thead",{class:"text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"},[t("tr",null,[t("th",{scope:"col",class:"px-6 py-3"}," Type "),t("th",{scope:"col",class:"px-6 py-3"}," Content "),t("th",{scope:"col",class:"px-6 py-3"}," Date ")])],-1),F={class:"bg-white border-b dark:bg-gray-800 dark:border-gray-700"},M={scope:"row",class:"px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"},Y={class:"px-6 py-4"},A={class:"px-6 py-4"},B={class:"flex items-center flex-column flex-wrap md:flex-row justify-between pt-4 ml-1","aria-label":"Table navigation"},N={class:"text-sm font-normal text-gray-500 dark:text-gray-400 mb-4 md:mb-0 block w-full md:inline md:w-auto"},S={class:"font-semibold text-gray-900"},V={class:"font-semibold text-gray-900"},q={class:"inline-flex -space-x-px rtl:space-x-reverse text-sm h-8"},O={class:"group/pagination"},z=["href","innerHTML"],E={key:1};function U(d,G,s,I,J,h){const _=c("Head"),m=c("AuthenticatedLayout");return a(),o(n,null,[i(_,{title:"נתוני זמן אמת"}),i(m,null,{default:y(()=>[t("div",w,[t("div",v,[t("div",k,[t("header",L,[t("h2",H,[l("Session logs for "),t("span",T,r(s.user.name),1)]),s.logs.data.length>0?(a(),o("div",j,[t("table",C,[D,t("tbody",null,[(a(!0),o(n,null,g(s.logs.data,e=>(a(),o("tr",F,[t("th",M,r(e.type),1),t("td",Y,r(e.content),1),t("td",A,r(h.dateFormat(e.created_at)),1)]))),256))])]),t("nav",B,[t("span",N,[l(" Showing "),t("span",S,r(s.logs.from)+"-"+r(s.logs.to),1),l(" of "),t("span",V,r(s.logs.total),1)]),t("ul",q,[(a(!0),o(n,null,g(s.logs.links,e=>(a(),o("li",O,[t("a",{href:e.url,class:b(["flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500","hover:text-gray-700","group-first/pagination:rounded-s-lg group-last/pagination:rounded-e-lg",{"opacity-75 cursor-not-allowed":e.url===null},`${e.active?"dark:bg-gray-700 bg-blue-50 hover:bg-blue-100 hover:text-blue-700":" bg-white border border-gray-300 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700"}`,"dark:border-gray-700 dark:text-gray-400 dark:hover:text-white"]),innerHTML:e.label},null,10,z)]))),256))])])])):(a(),o("div",E," User don't have any logs yet "))])])])])]),_:1})],64)}const X=x(f,[["render",U]]);export{X as default};