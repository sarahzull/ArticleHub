import{Q as m,T as g,o as n,c as o,b as e,t as a,d as i,g as f,u as p}from"./app-CkuosRbL.js";import{S as r}from"./sweetalert2.all-CNZ3tukp.js";const v=e("header",null,[e("h2",{class:"text-lg font-bold text-zinc-800"},"Your Subscription Plan")],-1),_={class:"mt-6 space-y-6"},P={class:"flex flex-col gap-3"},S={class:"flex flex-row justify-between"},k=e("div",{class:"font-medium text-stone-400"},"Subscription",-1),B={key:0,class:"flex items-center ms-4"},C={class:"px-2 py-1 text-sm font-semibold leading-tight rounded-md bg-amber-400 text-amber-700"},E={key:1,class:"flex items-center ms-4"},j={class:"px-2 py-1 text-sm font-semibold leading-tight rounded-md bg-rose-300 text-rose-700"},R={key:2,class:"flex items-center ms-4"},A={class:"px-2 py-1 text-sm font-semibold leading-tight rounded-md bg-sky-300 text-sky-700"},F={class:"flex flex-row justify-between"},D=e("div",{class:"font-medium text-stone-400"},"Price",-1),N={class:"flex flex-row justify-between"},T=e("div",{class:"font-medium text-stone-400"},"Next billing date",-1),V={class:"flex items-center justify-between gap-4"},Y=["value"],M={key:0,type:"submit",class:"inline-flex items-center py-2 text-xs font-semibold uppercase text-slate-500"},$=["value"],z={key:0,type:"submit",class:"inline-flex items-center py-2 text-xs font-semibold uppercase text-slate-500"},G={__name:"UserPlan",props:{mustVerifyEmail:{type:Boolean},status:{type:String},userPlan:{type:Object},showSubscriptionForm:{type:Boolean,default:!1}},setup(t){const x=t,u=m().props.auth.user,d=g({user_id:u.id,plan_id:x.userPlan.plan.plan_id}),l=m().props.flash;l.success?r.fire({title:"Success!",text:l.success,icon:"success"}):l.error&&r.fire({title:"Error",text:l.error,icon:"error"});const h=async()=>{await r.fire({title:"Are you sure?",text:"Do you want to cancel the plan?",icon:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Yes, cancel plan"}).then(s=>{s.isConfirmed&&b()})},b=async()=>{d.post(route("profile.cancelPlan"),{onSuccess:s=>{r.fire({title:"Success!",text:s.success||"Subscription has been canceled.",icon:"success"})},onError:s=>{r.fire({title:"Error",text:s.response.data.error||"An error occurred. Please try again.",icon:"error"})}})},y=async()=>{(await r.fire({title:"Are you sure?",text:"Do you want to stop renew the plan?",icon:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"Yes, stop renew the plan"})).isConfirmed&&await w()},w=async()=>{try{const c=(await d.post(route("profile.nonRenewPlan"))).data;r.fire({title:"Success!",text:c.success||"Subscription will not be renewed.",icon:"success"})}catch(s){r.fire({title:"Error",text:s.response.data.error||"An error occurred. Please try again.",icon:"error"})}};return(s,c)=>(n(),o("section",null,[v,e("div",_,[e("div",P,[e("div",S,[k,e("div",null,[t.userPlan.plan.name==="Basic"?(n(),o("div",B,[e("span",C,a(t.userPlan.plan.name),1)])):i("",!0),t.userPlan.plan.name==="Premium"?(n(),o("div",E,[e("span",j,a(t.userPlan.plan.name),1)])):i("",!0),t.userPlan.plan.name==="Pro"?(n(),o("div",R,[e("span",A,a(t.userPlan.plan.name),1)])):i("",!0)])]),e("div",F,[D,e("div",null,"RM "+a(t.userPlan.plan.price),1)]),e("div",N,[T,e("div",null,a(t.userPlan.end_date_formatted),1)])]),e("div",V,[t.showSubscriptionForm?i("",!0):(n(),o("button",{key:0,class:"inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-pink-600 uppercase transition duration-150 ease-in-out border border-pink-600 rounded-md hover:outline-none hover:bg-pink-600 hover:text-white",onClick:c[0]||(c[0]=Q=>s.$emit("toggle-subscription-form"))}," Change Subscription Plan ")),t.userPlan.status=="non_renewing"?(n(),o("form",{key:1,onSubmit:f(h,["prevent"])},[e("input",{type:"hidden",name:"user_id",value:p(u).id},null,8,Y),t.showSubscriptionForm?i("",!0):(n(),o("button",M," Cancel Subscription "))],32)):i("",!0),t.userPlan.status=="active"?(n(),o("form",{key:2,onSubmit:f(y,["prevent"])},[e("input",{type:"hidden",name:"user_id",value:p(u).id},null,8,$),t.showSubscriptionForm?i("",!0):(n(),o("button",z," Stop Renewing "))],32)):i("",!0)])])]))}};export{G as default};
