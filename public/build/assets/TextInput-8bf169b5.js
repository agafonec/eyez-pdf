import{o as s,f as o,t as l,r as i,s as d,x as c}from"./app-4142d4a4.js";const p={class:"block font-medium text-sm text-gray-700"},m={key:0},f={key:1},v={__name:"InputLabel",props:{value:{type:String}},setup(t){return(a,e)=>(s(),o("label",p,[t.value?(s(),o("span",m,l(t.value),1)):(s(),o("span",f,[i(a.$slots,"default")]))]))}},_=["value"],y={__name:"TextInput",props:{modelValue:{type:String,required:!0}},emits:["update:modelValue"],setup(t,{expose:a}){const e=d(null);return c(()=>{e.value.hasAttribute("autofocus")&&e.value.focus()}),a({focus:()=>e.value.focus()}),(n,u)=>(s(),o("input",{class:"border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm",value:t.modelValue,onInput:u[0]||(u[0]=r=>n.$emit("update:modelValue",r.target.value)),ref_key:"input",ref:e},null,40,_))}};export{v as _,y as a};
