import{j as u,x as n,E as d,o as p,c as i,n as k}from"./app-024a3f7a.js";const m=["value"],v={__name:"Checkbox",props:{checked:{type:[Array,Boolean],required:!0},value:{default:null}},emits:["update:checked"],setup(t,{emit:o}){const s=o,l=t,e=u({get(){return l.checked},set(a){s("update:checked",a)}});return(a,c)=>n((p(),i("input",{type:"checkbox",value:t.value,"onUpdate:modelValue":c[0]||(c[0]=r=>e.value=r),class:k(["rounded border-gray-300 text-black shadow-sm focus:ring-black",{"bg-black":e.value}])},null,10,m)),[[d,e.value]])}};export{v as _};
