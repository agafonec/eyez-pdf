import{M as i,P as c,o as t,f as s,F as d,B as m,t as o,r as p,s as _,x as f}from"./app-8af786f6.js";const g={class:"text-sm text-red-600"},y={key:1},$={__name:"InputError",props:{message:{type:[String,Array]}},setup(e){return(r,a)=>i((t(),s("div",null,[Array.isArray(e.message)?(t(!0),s(d,{key:0},m(e.message,n=>(t(),s("p",g,o(n),1))),256)):(t(),s("p",y,o(e.message),1))],512)),[[c,e.message]])}},v={class:"block font-medium text-sm text-gray-700"},h={key:0},x={key:1},S={__name:"InputLabel",props:{value:{type:String}},setup(e){return(r,a)=>(t(),s("label",v,[e.value?(t(),s("span",h,o(e.value),1)):(t(),s("span",x,[p(r.$slots,"default")]))]))}},k=["value"],A={__name:"TextInput",props:{modelValue:{type:String,required:!0}},emits:["update:modelValue"],setup(e,{expose:r}){const a=_(null);return f(()=>{a.value.hasAttribute("autofocus")&&a.value.focus()}),r({focus:()=>a.value.focus()}),(n,u)=>(t(),s("input",{class:"border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm",value:e.modelValue,onInput:u[0]||(u[0]=l=>n.$emit("update:modelValue",l.target.value)),ref_key:"input",ref:a},null,40,k))}};export{S as _,A as a,$ as b};