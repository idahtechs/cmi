(self["webpackChunkcmi_uni_app"]=self["webpackChunkcmi_uni_app"]||[]).push([[220],{34635:function(e,t,a){var n=a(81295);n.__esModule&&(n=n.default),"string"===typeof n&&(n=[[e.id,n,""]]),n.locals&&(e.exports=n.locals);var i=a(69333).A;i("b0cab252",n,!0,{sourceMap:!1,shadowMode:!1})},94949:function(e,t,a){var n=a(21937);n.__esModule&&(n=n.default),"string"===typeof n&&(n=[[e.id,n,""]]),n.locals&&(e.exports=n.locals);var i=a(69333).A;i("53c51860",n,!0,{sourceMap:!1,shadowMode:!1})},77937:function(e,t,a){"use strict";var n;a.d(t,{A:function(){return p}});var i,o=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("v-uni-view",[a("v-uni-view",{staticClass:"payment",class:e.showPaymentWidget?"on":""},[a("v-uni-view",{staticClass:"title acea-row row-center-wrapper"},[e._v("选择付款方式"),a("v-uni-text",{staticClass:"iconfont icon-guanbi",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.emitClose.apply(void 0,arguments)}}})],1),e._l(e.payModesToSelect,(function(t){return a("v-uni-view",{key:t.value,staticClass:"item acea-row row-between-wrapper",on:{click:function(a){arguments[0]=a=e.$handleEvent(a),e.goPay(t)}}},[a("v-uni-view",{staticClass:"left acea-row row-between-wrapper"},[a("v-uni-view",{staticClass:"iconfont",class:t.icon}),a("v-uni-view",{staticClass:"text"},[a("v-uni-view",{staticClass:"name"},[e._v(e._s(t.name))]),t.number?a("v-uni-view",{staticClass:"info"},[e._v(e._s(t.title)),a("span",{staticClass:"money"},[e._v("￥"+e._s(t.number))])]):a("v-uni-view",{staticClass:"info"},[e._v(e._s(t.title))])],1)],1),a("v-uni-view",{staticClass:"iconfont icon-xiangyou"})],1)}))],2),e.showPaymentWidget?a("v-uni-view",{ref:"close",staticClass:"mask",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.emitClose.apply(void 0,arguments)}}}):e._e()],1)},s=[],r=(a(28706),a(2008),a(50113),a(74423),a(62062),a(26099),a(78459),a(21699),a(40658)),c={props:{enabledPayModes:{type:Array,default:function(){return[]}},showPaymentWidget:{type:Boolean,default:!1}},data:function(){return{userBalance:0}},computed:{payModesToSelect:function(e){var t=e.enabledPayModes,a=e.userBalance,n=[{name:"微信支付",icon:"icon-weixinzhifu",value:"wechat",title:"微信快捷支付",payStatus:1},{name:"支付宝支付",icon:"icon-zhifubao",value:"alipay",title:"支付宝支付",payStatus:this.$store.getters.globalData.alipay_open},{name:"余额支付",icon:"icon-yuezhifu",value:"balance",title:"可用余额:",number:a,payStatus:this.$store.getters.globalData.yue_pay_status}];return t.map((function(e){return n.find((function(t){return t.value===e}))})).filter((function(e){return!!e}))}},watch:{showPaymentWidget:function(e){e&&this.enabledPayModes.includes("balance")&&this.refreshUserBalance()}},methods:{refreshUserBalance:function(){var e=this;return(0,r.ug)().then((function(t){e.userBalance=t.data.now_money}))},emitClose:function(){this.$emit("close")},goPay:function(e){var t=this.getType(e.value);this.$emit("prepareToPay",{payType:t,payMode:e})},getType:function(e){var t=e;return"wechat"==e?t=this.$wechat.isWeixin()?"weixin":"h5":"alipay"==e&&(t="alipay"),t},pay:function(e){var t=e.payMode,a=e.paymentAmount,n=e.createOrderPromiseFn,i=e.success,o=void 0===i?function(){}:i,s=e.fail,r=void 0===s?function(){}:s,c=this.$util,l=this.$wechat,u=this.emitClose,d=t.number||0,p=t.value,f=this.getType(p),v=function(e){console.log("pay success: ",e),uni.hideLoading(),u(),c.Tips({title:"支付成功",icon:"success"}),setTimeout(o,1500)},m=function(e,t){return console.log("pay fail: ",e),uni.hideLoading(),r(e),u(),c.Tips({title:t?"取消支付":"支付失败"})};if("balance"==f&&parseFloat(d)<parseFloat(a))return c.Tips({title:"余额不足！"});uni.showLoading({title:"支付中"}),n().then((function(e){console.log("create order: ",e);var t=e.data.status,a=e.data.result.order_id,n=e.data.result.config,i=e.data.result.pay_key;switch(t){case"ORDER_EXIST":case"EXTEND_ORDER":case"PAY_ERROR":case"error":return uni.hideLoading(),r(e),u(),c.Tips({title:e.message});case"success":return v();case"alipay":case"alipayQr":return uni.hideLoading(),u(),uni.navigateTo({url:"/pages/order_pay_back/index?keyCode="+i+"&url="+n});case"wechat":case"weixin":case"weixinApp":n.timeStamp=n.timestamp,l.pay(n).then((function(e){v(e)})).catch((function(e){var t="chooseWXPay:cancel"==e.errMsg;m(e,t)}));break;case"balance":return uni.hideLoading(),u(),c.Tips({title:e.message});case"h5":var o=window.location.protocol+"//"+window.location.host,s="".concat(o,"/pages/order_pay_status/index?order_id=").concat(a),d=encodeURIComponent(s),p=n.mweb_url||n.h5_url,f="".concat(p,"&redirect_url=").concat(d);setTimeout((function(){location.href=f}),100);break}})).catch((function(e){return uni.hideLoading(),c.Tips({title:e})}))}}},l=c,u=(a(34635),a(18535)),d=(0,u.A)(l,o,s,!1,null,"40f2aa31",null,!1,n,i),p=d.exports},27676:function(e,t,a){"use strict";a.r(t),a.d(t,{default:function(){return m}});var n,i={cPage:a(49752).A,commonPayment:a(77937).A},o=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("c-page",{attrs:{"login-required":!0,"bg-theme":"none"},on:{ready:function(t){arguments[0]=t=e.$handleEvent(t),e.onPageReady.apply(void 0,arguments)}}},[a("v-uni-view",{staticClass:"bg-white"},[a("v-uni-view",{staticClass:"flex gap-12 px-24 pt-40 pb-32 overflow-auto"},e._l(e.list,(function(t,n){return a("v-uni-view",{key:t.group_data_id,staticClass:"text-center px-16 py-18 br-20 bd-2 flex-none",class:e.activeIndex===n?"active":null,staticStyle:{width:"252rpx"},on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.activeIndex=n}}},[a("v-uni-view",{staticClass:"fs-12 font-bold color-view-theme"},[e._v("积分")]),a("v-uni-view",{staticClass:"fs-32 font-bold"},[e._v(e._s(t.integral))]),a("v-uni-view",{staticClass:"color-muted fs-20"},[e._v("¥ "+e._s(t.price))])],1)})),1),a("v-uni-view",{staticClass:"text-center px-24 pb-20 fs-12"},[a("v-uni-button",{staticClass:"cmi-btn",attrs:{type:"primary"},on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.buyNow.apply(void 0,arguments)}}},[e._v("立即购买")]),a("v-uni-view",{staticClass:"mt-16"},[a("v-uni-view",{staticClass:"inline-block",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.agree=!e.agree}}},[a("v-uni-checkbox",{staticClass:"events-disabled",attrs:{checked:e.agree}}),e._v("已阅读并同意")],1),a("v-uni-navigator",{staticClass:"cmi-link",attrs:{url:"/pages/users/privacy/index?type=sys_user_agree"}},[e._v("《用户服务协议》")])],1)],1)],1),a("common-payment",{ref:"payment",attrs:{enabledPayModes:["wechat"],showPaymentWidget:e.showPaymentWidget},on:{prepareToPay:function(t){arguments[0]=t=e.$handleEvent(t),e.prepareToPay.apply(void 0,arguments)},close:function(t){arguments[0]=t=e.$handleEvent(t),e.showPaymentWidget=!1}}})],1)},s=[],r=a(42727),c=a(92771),l=a(50575),u=a(40658),d={data:function(){return{agree:!1,activeIndex:0,list:[],showPaymentWidget:!1}},methods:{buyNow:function(){var e=this;if(!this.agree)return uni.showModal({title:"请确认会员条款",content:"已阅读并同意《用户服务协议》",success:function(t){t.confirm&&(e.agree=!0,e.showPaymentWidget=!0)}});this.showPaymentWidget=!0},onPageReady:function(){var e=this;return(0,l.A)((0,r.A)().mark((function t(){var a,n,i,o;return(0,r.A)().wrap((function(t){while(1)switch(t.prev=t.next){case 0:return uni.showLoading(),t.next=3,e.$util.ef((0,u.y1)());case 3:if(a=t.sent,n=(0,c.A)(a,2),i=n[0],o=n[1],uni.hideLoading(),!i){t.next=10;break}return t.abrupt("return",uni.showToast({title:i.message,icon:"none"}));case 10:e.list=o.data.list;case 11:case"end":return t.stop()}}),t)})))()},prepareToPay:function(e){var t=e.payType,a=e.payMode,n=this.list[this.activeIndex];this.$refs.payment.pay({payMode:a,paymentAmount:n.price,createOrderPromiseFn:function(){return(0,u.Y3)(n.group_data_id,{pay_type:t,return_url:location.origin+"/pages/user/index"})},success:function(){uni.navigateBack()},fail:function(){}})}}},p=d,f=(a(94949),a(18535)),v=(0,f.A)(p,o,s,!1,null,"7fd9e69e",null,!1,i,n),m=v.exports},81295:function(e,t,a){"use strict";a.r(t);var n=a(45522),i=a.n(n),o=a(67643),s=a.n(o),r=s()(i());r.push([e.id,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.payment[data-v-40f2aa31]{position:fixed;bottom:0;left:0;width:100%;max-height:%?600?%;border-radius:%?16?% %?16?% 0 0;background-color:#fff;padding-bottom:%?60?%;z-index:99;transition:all .3s cubic-bezier(.25,.5,.5,.9);-webkit-transform:translate3d(0,100%,0);transform:translate3d(0,100%,0)}.payment.on[data-v-40f2aa31]{-webkit-transform:translateZ(0);transform:translateZ(0)}.payment .title[data-v-40f2aa31]{text-align:center;height:%?123?%;font-size:%?32?%;color:#282828;font-weight:700;padding-right:%?30?%;margin-left:%?30?%;position:relative;border-bottom:1px solid #eee}.payment .title .iconfont[data-v-40f2aa31]{position:absolute;right:%?30?%;top:50%;-webkit-transform:translateY(-50%);transform:translateY(-50%);font-size:%?43?%;color:#8a8a8a;font-weight:400}.payment .item[data-v-40f2aa31]{border-bottom:1px solid #eee;height:%?130?%;margin-left:%?30?%;padding-right:%?30?%}.payment .item .left[data-v-40f2aa31]{width:%?610?%}.payment .item .left .text[data-v-40f2aa31]{width:%?540?%}.payment .item .left .text .name[data-v-40f2aa31]{font-size:%?32?%;color:#282828}.payment .item .left .text .info[data-v-40f2aa31]{font-size:%?24?%;color:#999}.payment .item .left .text .info .money[data-v-40f2aa31]{color:#f90}.payment .item .left .iconfont[data-v-40f2aa31]{font-size:%?45?%;color:#09bb07}.payment .item .left .iconfont.icon-zhifubao[data-v-40f2aa31]{color:#00aaea}.payment .item .left .iconfont.icon-yuezhifu[data-v-40f2aa31]{color:#f90}.payment .item .left .iconfont.icon-yuezhifu1[data-v-40f2aa31]{color:#eb6623}.payment .item .iconfont[data-v-40f2aa31]{font-size:%?0.3?%;color:#999}',""]),t["default"]=r},21937:function(e,t,a){"use strict";a.r(t);var n=a(45522),i=a.n(n),o=a(67643),s=a.n(o),r=s()(i());r.push([e.id,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.active[data-v-7fd9e69e]{background-color:#e5fdfe;border-color:var(--view-theme)}',""]),t["default"]=r}}]);