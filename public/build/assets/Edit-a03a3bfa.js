import{_ as f}from"./AuthenticatedLayout-0997d913.js";import y from"./DeleteUserForm-81cf7636.js";import g from"./UpdatePasswordForm-1444534f.js";import h from"./UpdateProfileInformationForm-10d09fd9.js";import{Z as w,r as t,o as r,c as s,a as o,w as a,F as x,b as n,f as i}from"./app-8d227d84.js";import U from"./OpretailnformationForm-4486168d.js";import k from"./Settings-1d790f76.js";import F from"./ApiToken-f23d2041.js";import{_ as A}from"./_plugin-vue_export-helper-c27b6911.js";import"./pdf-logo-2196faa3.js";import"./InputError-5128279a.js";import"./TextInput-347c1fc9.js";import"./SecondaryButton-43de0f6a.js";import"./PrimaryButton-314db279.js";import"./Checkbox-fd59a90d.js";const b={name:"Edit",components:{AuthenticatedLayout:f,DeleteUserForm:y,UpdatePasswordForm:g,UpdateProfileInformationForm:h,Head:w,OpretailnformationForm:U,Settings:k,ApiToken:F},props:{mustVerifyEmail:{type:Boolean},currentUser:{type:Object},status:{type:String},opretail:{type:Object},stores:{type:[Object,Array]},roles:{type:Array},eyezApiToken:{type:String},showSuperAdmin:{type:Boolean,default:!1}}},v=n("h2",{class:"font-semibold text-xl text-gray-800 leading-tight"},"Profile",-1),P={class:"py-12"},E={class:"max-w-7xl mx-auto md:px-6 lg:px-8 space-y-6"},O={class:"p-4 md:p-8 bg-white shadow md:rounded-lg grid grid-cols-2 gap-5"},V={key:0,class:"p-4 md:p-8 bg-white shadow md:rounded-lg"},B={key:1,class:"p-4 md:p-8 bg-white shadow md:rounded-lg"},T={key:2,class:"p-4 md:p-8 bg-white shadow md:rounded-lg"};function S(j,C,e,H,I,L){const m=t("Head"),d=t("UpdateProfileInformationForm"),l=t("UpdatePasswordForm"),c=t("OpretailnformationForm"),u=t("settings"),_=t("ApiToken"),p=t("AuthenticatedLayout");return r(),s(x,null,[o(m,{title:"Profile"}),o(p,null,{header:a(()=>[v]),default:a(()=>[n("div",P,[n("div",E,[n("div",O,[o(d,{"must-verify-email":e.mustVerifyEmail,status:e.status,class:"max-w-xl"},null,8,["must-verify-email","status"]),o(l,{class:"max-w-xl",user:e.mustVerifyEmail},null,8,["user"])]),e.roles.includes("admin")&&e.currentUser!==void 0?(r(),s("div",V,[o(c,{opretail:e.opretail,user:e.currentUser},null,8,["opretail","user"])])):i("",!0),e.roles.includes("admin")&&e.currentUser!==void 0&&!e.opretail.errors?(r(),s("div",B,[o(u,{settings:e.opretail.settings,user:e.currentUser,stores:e.stores},null,8,["settings","user","stores"])])):i("",!0),e.roles.includes("admin")&&!e.opretail.errors?(r(),s("div",T,[o(_,{"api-token":e.eyezApiToken,user:e.currentUser},null,8,["api-token","user"])])):i("",!0)])])]),_:1})],64)}const ee=A(b,[["render",S]]);export{ee as default};
