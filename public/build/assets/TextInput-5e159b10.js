import{o,c as s,t as r,k as i,x as d,z as c}from"./app-98a89079.js";const p={class:"block font-medium text-sm text-gray-700"},m={key:0},_={key:1},v={__name:"InputLabel",props:{value:{type:String}},setup(t){return(a,e)=>(o(),s("label",p,[t.value?(o(),s("span",m,r(t.value),1)):(o(),s("span",_,[i(a.$slots,"default")]))]))}},f=["value"],y={__name:"TextInput",props:{modelValue:{type:String,required:!0}},emits:["update:modelValue"],setup(t,{expose:a}){const e=d(null);return c(()=>{e.value.hasAttribute("autofocus")&&e.value.focus()}),a({focus:()=>e.value.focus()}),(n,u)=>(o(),s("input",{class:"border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm",value:t.modelValue,onInput:u[0]||(u[0]=l=>n.$emit("update:modelValue",l.target.value)),ref_key:"input",ref:e},null,40,f))}};export{v as _,y as a};
