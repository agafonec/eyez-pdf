import{_ as x}from"./AuthenticatedLayout-0c88090f.js";import U from"./DeleteUserForm-cb005f89.js";import v from"./UpdatePasswordForm-1e6dacdf.js";import F from"./UpdateProfileInformationForm-81c08bcf.js";import{Z as A,r as t,o,c as n,a as r,w as m,F as P,b as s,f as l,g as c,d as u}from"./app-024a3f7a.js";import b from"./OpretailnformationForm-e3ae8773.js";import B from"./Settings-c53fb796.js";import V from"./ApiToken-fc33bd9a.js";import{_ as E}from"./PrimaryButton-7ece6cb8.js";import{_ as O}from"./_plugin-vue_export-helper-c27b6911.js";import"./pdf-logo-4d0ebdf1.js";import"./DangerButton-e1ed5c6f.js";import"./InputError-cc44ac6f.js";import"./TextInput-3ee09e56.js";import"./SecondaryButton-634c8d56.js";import"./Checkbox-f2e37aea.js";import"./index-41d2690b.js";import"./moment-fbc5633a.js";const T={name:"Edit",components:{AuthenticatedLayout:x,DeleteUserForm:U,UpdatePasswordForm:v,UpdateProfileInformationForm:F,Head:A,OpretailnformationForm:b,Settings:B,ApiToken:V,PrimaryButton:E},props:{mustVerifyEmail:{type:Boolean},currentUser:{type:Object},status:{type:String},opretail:{type:Object},stores:{type:[Object,Array]},roles:{type:Array},eyezApiToken:{type:String},showSuperAdmin:{type:Boolean,default:!1}}},C=s("h2",{class:"font-semibold text-xl text-gray-800 leading-tight"},"Profile",-1),S={class:"py-12"},N={class:"max-w-7xl mx-auto md:px-6 lg:px-8 space-y-6"},j={class:"p-4 md:p-8 bg-white shadow md:rounded-lg grid grid-cols-2 gap-5"},H={key:0},I={key:1,class:"p-4 md:p-8 bg-white shadow md:rounded-lg"},L={key:2},z={key:3,class:"p-4 md:p-8 bg-white shadow md:rounded-lg"},G=s("header",null,[s("h2",{class:"text-lg font-medium text-gray-900 mb-4"},"Start sync process with cameras.")],-1);function D(i,a,e,Z,q,J){const _=t("Head"),f=t("UpdateProfileInformationForm"),p=t("UpdatePasswordForm"),y=t("OpretailnformationForm"),g=t("settings"),h=t("ApiToken"),d=t("PrimaryButton"),k=t("AuthenticatedLayout");return o(),n(P,null,[r(_,{title:"Profile"}),r(k,null,{header:m(()=>[C]),default:m(()=>[s("div",S,[s("div",N,[s("div",j,[r(f,{"must-verify-email":e.mustVerifyEmail,status:e.status,class:"max-w-xl"},null,8,["must-verify-email","status"]),r(p,{class:"max-w-xl",user:e.mustVerifyEmail},null,8,["user"])]),e.roles.includes("admin")&&e.currentUser!==void 0?(o(),n("div",H,[r(y,{opretail:e.opretail,user:e.currentUser},null,8,["opretail","user"])])):l("",!0),e.roles.includes("admin")&&e.currentUser!==void 0&&!e.opretail.errors?(o(),n("div",I,[r(g,{settings:e.currentUser.settings,user:e.currentUser,stores:e.stores},null,8,["settings","user","stores"])])):l("",!0),e.roles.includes("admin")&&!e.opretail.errors?(o(),n("div",L,[r(h,{"api-token":e.eyezApiToken,user:e.currentUser},null,8,["api-token","user"])])):l("",!0),e.roles.includes("admin")||e.roles.includes("main_user")?(o(),n("div",z,[G,e.roles.includes("admin")?(o(),c(d,{key:0,onClick:a[0]||(a[0]=w=>i.$inertia.visit(i.route("admin.sync-store",{user:e.currentUser.id})))},{default:m(()=>[u("Go to sync page")]),_:1})):(o(),c(d,{key:1,onClick:a[1]||(a[1]=w=>i.$inertia.visit(i.route("profile.sync-opretail")))},{default:m(()=>[u("Go to sync page")]),_:1}))])):l("",!0)])])]),_:1})],64)}const de=O(T,[["render",D]]);export{de as default};