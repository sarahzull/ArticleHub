import{s as S,x as D,h as w,l as L,o,f as c,b as t,r as b,i as C,z,a as n,w as s,n as v,y as M,c as y,u as l,j as m,Q as _,d as a,g as u,t as g}from"./app-CLQe1i2U.js";const N={class:"relative"},j={__name:"Dropdown",props:{align:{type:String,default:"right"},width:{type:String,default:"48"},contentClasses:{type:String,default:"py-1 bg-white"}},setup(i){const r=i,d=f=>{e.value&&f.key==="Escape"&&(e.value=!1)};S(()=>document.addEventListener("keydown",d)),D(()=>document.removeEventListener("keydown",d));const p=w(()=>({48:"w-48"})[r.width.toString()]),x=w(()=>r.align==="left"?"ltr:origin-top-left rtl:origin-top-right start-0":r.align==="right"?"ltr:origin-top-right rtl:origin-top-left end-0":"origin-top"),e=L(!1);return(f,h)=>(o(),c("div",N,[t("div",{onClick:h[0]||(h[0]=P=>e.value=!e.value)},[b(f.$slots,"trigger")]),C(t("div",{class:"fixed inset-0 z-40",onClick:h[1]||(h[1]=P=>e.value=!1)},null,512),[[z,e.value]]),n(M,{"enter-active-class":"transition ease-out duration-200","enter-from-class":"opacity-0 scale-95","enter-to-class":"opacity-100 scale-100","leave-active-class":"transition ease-in duration-75","leave-from-class":"opacity-100 scale-100","leave-to-class":"opacity-0 scale-95"},{default:s(()=>[C(t("div",{class:v(["absolute z-50 mt-2 rounded-md shadow-lg",[p.value,x.value]]),style:{display:"none"},onClick:h[2]||(h[2]=P=>e.value=!1)},[t("div",{class:v(["rounded-md ring-1 ring-black ring-opacity-5",i.contentClasses])},[b(f.$slots,"content")],2)],2),[[z,e.value]])]),_:3})]))}},B={__name:"DropdownLink",props:{href:{type:String,required:!0}},setup(i){return(r,d)=>(o(),y(l(m),{href:i.href,class:"block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"},{default:s(()=>[b(r.$slots,"default")]),_:3},8,["href"]))}},k={__name:"NavLink",props:{href:{type:String,required:!0},active:{type:Boolean}},setup(i){const r=i,d=w(()=>r.active?"inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out":"inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out");return(p,x)=>(o(),y(l(m),{href:i.href,class:v(d.value)},{default:s(()=>[b(p.$slots,"default")]),_:3},8,["href","class"]))}},$={__name:"ResponsiveNavLink",props:{href:{type:String,required:!0},active:{type:Boolean}},setup(i){const r=i,d=w(()=>r.active?"block w-full ps-3 pe-4 py-2 border-l-4 border-indigo-400 text-start text-base font-medium text-indigo-700 bg-indigo-50 focus:outline-none focus:text-indigo-800 focus:bg-indigo-100 focus:border-indigo-700 transition duration-150 ease-in-out":"block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out");return(p,x)=>(o(),y(l(m),{href:i.href,class:v(d.value)},{default:s(()=>[b(p.$slots,"default")]),_:3},8,["href","class"]))}},A={class:"min-h-screen bg-gray-100"},E={class:"bg-white border-b border-gray-100"},F={class:"px-4 mx-auto max-w-7xl sm:px-6 lg:px-8"},V={class:"flex justify-between h-16"},q={class:"flex"},O={class:"flex items-center shrink-0"},T=t("span",{class:"text-2xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-violet-500"}," ArticleHub ",-1),H={class:"hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"},Q={class:"hidden sm:flex sm:items-center sm:ms-6"},R={class:"flex items-center ms-4"},U={key:0,class:"flex items-center ms-4"},G={key:1,class:"flex items-center ms-4"},I={key:2,class:"flex items-center ms-4"},J={key:3,class:"flex items-center ms-4"},K={href:"/plans",class:"px-2 py-1 text-sm font-semibold leading-tight rounded-md bg-slate-300 text-slate-700"},W={class:"relative ms-3"},X={class:"inline-flex rounded-md"},Y={type:"button",class:"inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md hover:text-gray-700 focus:outline-none"},Z=t("svg",{class:"ms-2 -me-0.5 h-4 w-4",xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 20 20",fill:"currentColor"},[t("path",{"fill-rule":"evenodd",d:"M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z","clip-rule":"evenodd"})],-1),ee={class:"flex items-center -me-2 sm:hidden"},te={class:"w-6 h-6",stroke:"currentColor",fill:"none",viewBox:"0 0 24 24"},se={class:"pt-2 pb-3 space-y-1"},re={class:"pt-4 pb-1 border-t border-gray-200"},oe={class:"px-4"},ae={class:"text-base font-medium text-gray-800"},ne={class:"text-sm font-medium text-gray-500"},ie={class:"mt-3 space-y-1"},le={key:0,class:"bg-white shadow"},de={class:"px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8"},ce={__name:"AuthenticatedLayout",setup(i){const r=L(!1);_().props.auth.user;const d=_().props.can.basic,p=_().props.can.premium,x=_().props.can.pro;return(e,f)=>(o(),c("div",null,[t("div",A,[t("nav",E,[t("div",F,[t("div",V,[t("div",q,[t("div",O,[n(l(m),{href:e.route("dashboard")},{default:s(()=>[T]),_:1},8,["href"])]),t("div",H,[n(k,{href:e.route("dashboard"),active:e.route().current("dashboard")},{default:s(()=>[a(" Dashboard ")]),_:1},8,["href","active"]),l(d)?(o(),y(k,{key:0,href:e.route("personalized.index"),active:e.route().current("personalized.index")},{default:s(()=>[a(" For Basic Plan ")]),_:1},8,["href","active"])):u("",!0),l(p)?(o(),y(k,{key:1,href:e.route("personalized.index"),active:e.route().current("personalized.index")},{default:s(()=>[a(" For Premium Plan ")]),_:1},8,["href","active"])):u("",!0),l(x)?(o(),y(k,{key:2,href:e.route("personalized.index"),active:e.route().current("personalized.index")},{default:s(()=>[a(" For Pro Plan ")]),_:1},8,["href","active"])):u("",!0)])]),t("div",Q,[t("div",R,[e.$page.props.auth.currentPlan==="Basic"?(o(),c("div",U,[n(l(m),{href:e.route("profile.edit"),class:"px-2 py-1 text-sm font-semibold leading-tight rounded-md bg-amber-400 text-amber-700"},{default:s(()=>[a(g(e.$page.props.auth.currentPlan),1)]),_:1},8,["href"])])):u("",!0),e.$page.props.auth.currentPlan==="Premium"?(o(),c("div",G,[n(l(m),{href:e.route("profile.edit"),class:"px-2 py-1 text-sm font-semibold leading-tight rounded-md bg-rose-300 text-rose-700"},{default:s(()=>[a(g(e.$page.props.auth.currentPlan),1)]),_:1},8,["href"])])):u("",!0),e.$page.props.auth.currentPlan==="Pro"?(o(),c("div",I,[n(l(m),{href:e.route("profile.edit"),class:"px-2 py-1 text-sm font-semibold leading-tight rounded-md bg-sky-300 text-sky-700"},{default:s(()=>[a(g(e.$page.props.auth.currentPlan),1)]),_:1},8,["href"])])):u("",!0),e.$page.props.auth.currentPlan==="Free"?(o(),c("div",J,[t("a",K,g(e.$page.props.auth.currentPlan),1)])):u("",!0)]),t("div",W,[n(j,{align:"right",width:"48"},{trigger:s(()=>[t("span",X,[t("button",Y,[a(g(e.$page.props.auth.user.name)+" ",1),Z])])]),content:s(()=>[n(B,{href:e.route("profile.edit")},{default:s(()=>[a(" Profile ")]),_:1},8,["href"]),n(B,{href:e.route("logout"),method:"post",as:"button"},{default:s(()=>[a(" Log Out ")]),_:1},8,["href"])]),_:1})])]),t("div",ee,[t("button",{onClick:f[0]||(f[0]=h=>r.value=!r.value),class:"inline-flex items-center justify-center p-2 text-gray-400 transition duration-150 ease-in-out rounded-md hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500"},[(o(),c("svg",te,[t("path",{class:v({hidden:r.value,"inline-flex":!r.value}),"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M4 6h16M4 12h16M4 18h16"},null,2),t("path",{class:v({hidden:!r.value,"inline-flex":r.value}),"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M6 18L18 6M6 6l12 12"},null,2)]))])])])]),t("div",{class:v([{block:r.value,hidden:!r.value},"sm:hidden"])},[t("div",se,[n($,{href:e.route("dashboard"),active:e.route().current("dashboard")},{default:s(()=>[a(" Dashboard ")]),_:1},8,["href","active"])]),t("div",re,[t("div",oe,[t("div",ae,g(e.$page.props.auth.user.name),1),t("div",ne,g(e.$page.props.auth.user.email),1)]),t("div",ie,[n($,{href:e.route("profile.edit")},{default:s(()=>[a(" Profile ")]),_:1},8,["href"]),n($,{href:e.route("logout"),method:"post",as:"button"},{default:s(()=>[a(" Log Out ")]),_:1},8,["href"])])])],2)]),e.$slots.header?(o(),c("header",le,[t("div",de,[b(e.$slots,"header")])])):u("",!0),t("main",null,[b(e.$slots,"default")])])]))}};export{ce as _};
