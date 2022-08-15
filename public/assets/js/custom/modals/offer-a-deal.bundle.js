"use strict";var KTModalOfferADealComplete=function(){var e;return{init:function(){KTModalOfferADeal.getForm(),e=KTModalOfferADeal.getStepperObj(),document.querySelector('[data-kt-element="complete-start"]').addEventListener("click",(function(){e.goTo(1)}))}}}();"undefined"!=typeof module&&void 0!==module.exports&&(module.exports=KTModalOfferADealComplete);var KTModalOfferADealDetails=function(){var e,t,o,a,i;return{init:function(){a=KTModalOfferADeal.getForm(),i=KTModalOfferADeal.getStepperObj(),e=document.querySelector('[data-kt-element="details-next"]'),t=document.querySelector('[data-kt-element="details-previous"]'),$(a.querySelector('[name="details_activation_date"]')).flatpickr({enableTime:!0,dateFormat:"d, M Y, H:i"}),$(a.querySelector('[name="details_customer"]')).on("change",(function(){o.revalidateField("details_customer")})),o=FormValidation.formValidation(a,{fields:{details_customer:{validators:{notEmpty:{message:"Customer is required"}}},details_title:{validators:{notEmpty:{message:"Deal title is required"}}},details_activation_date:{validators:{notEmpty:{message:"Activation date is required"}}},"details_notifications[]":{validators:{notEmpty:{message:"Notifications are required"}}}},plugins:{trigger:new FormValidation.plugins.Trigger,bootstrap:new FormValidation.plugins.Bootstrap5({rowSelector:".fv-row",eleInvalidClass:"",eleValidClass:""})}}),e.addEventListener("click",(function(t){t.preventDefault(),e.disabled=!0,o&&o.validate().then((function(t){console.log("validated!"),"Valid"==t?(e.setAttribute("data-kt-indicator","on"),setTimeout((function(){e.removeAttribute("data-kt-indicator"),e.disabled=!1,i.goNext()}),1500)):(e.disabled=!1,Swal.fire({text:"Sorry, looks like there are some errors detected, please try again.",icon:"error",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}}))}))})),t.addEventListener("click",(function(){i.goPrevious()}))}}}();"undefined"!=typeof module&&void 0!==module.exports&&(module.exports=KTModalOfferADealDetails);var KTModalOfferADealFinance=function(){var e,t,o,a,i;return{init:function(){a=KTModalOfferADeal.getForm(),i=KTModalOfferADeal.getStepperObj(),e=document.querySelector('[data-kt-element="finance-next"]'),t=document.querySelector('[data-kt-element="finance-previous"]'),o=FormValidation.formValidation(a,{fields:{finance_setup:{validators:{notEmpty:{message:"Amount is required"},callback:{message:"The amount must be greater than $100",callback:function(e){var t=e.value;if(t=t.replace(/[$,]+/g,""),parseFloat(t)<100)return!1}}}},finance_usage:{validators:{notEmpty:{message:"Usage type is required"}}},finance_allow:{validators:{notEmpty:{message:"Allowing budget is required"}}}},plugins:{trigger:new FormValidation.plugins.Trigger,bootstrap:new FormValidation.plugins.Bootstrap5({rowSelector:".fv-row",eleInvalidClass:"",eleValidClass:""})}}),KTDialer.getInstance(a.querySelector("#kt_modal_finance_setup")).on("kt.dialer.changed",(function(){o.revalidateField("finance_setup")})),e.addEventListener("click",(function(t){t.preventDefault(),e.disabled=!0,o&&o.validate().then((function(t){console.log("validated!"),"Valid"==t?(e.setAttribute("data-kt-indicator","on"),setTimeout((function(){e.removeAttribute("data-kt-indicator"),e.disabled=!1,i.goNext()}),1500)):(e.disabled=!1,Swal.fire({text:"Sorry, looks like there are some errors detected, please try again.",icon:"error",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}}))}))})),t.addEventListener("click",(function(){i.goPrevious()}))}}}();"undefined"!=typeof module&&void 0!==module.exports&&(module.exports=KTModalOfferADealFinance);var KTModalOfferADeal=function(){var e,t,o;return{init:function(){e=document.querySelector("#kt_modal_offer_a_deal_stepper"),o=document.querySelector("#kt_modal_offer_a_deal_form"),t=new KTStepper(e)},getStepperObj:function(){return t},getForm:function(){return o}}}();KTUtil.onDOMContentLoaded((function(){document.querySelector("#kt_modal_offer_a_deal")&&(KTModalOfferADeal.init(),KTModalOfferADealType.init(),KTModalOfferADealDetails.init(),KTModalOfferADealFinance.init(),KTModalOfferADealComplete.init())})),"undefined"!=typeof module&&void 0!==module.exports&&(module.exports=KTModalOfferADeal);var KTModalOfferADealType=function(){var e,t,o,a;return{init:function(){o=KTModalOfferADeal.getForm(),a=KTModalOfferADeal.getStepperObj(),e=document.querySelector('[data-kt-element="type-next"]'),t=FormValidation.formValidation(o,{fields:{offer_type:{validators:{notEmpty:{message:"Offer type is required"}}}},plugins:{trigger:new FormValidation.plugins.Trigger,bootstrap:new FormValidation.plugins.Bootstrap5({rowSelector:".fv-row",eleInvalidClass:"",eleValidClass:""})}}),e.addEventListener("click",(function(o){o.preventDefault(),e.disabled=!0,t&&t.validate().then((function(t){console.log("validated!"),"Valid"==t?(e.setAttribute("data-kt-indicator","on"),setTimeout((function(){e.removeAttribute("data-kt-indicator"),e.disabled=!1,a.goNext()}),1e3)):(e.disabled=!1,Swal.fire({text:"Sorry, looks like there are some errors detected, please try again.",icon:"error",buttonsStyling:!1,confirmButtonText:"Ok, got it!",customClass:{confirmButton:"btn btn-primary"}}))}))}))}}}();"undefined"!=typeof module&&void 0!==module.exports&&(module.exports=KTModalOfferADealType);