(self["webpackChunkcmi_uni_app"]=self["webpackChunkcmi_uni_app"]||[]).push([[992],{42060:function(t,n,e){var s=e(6376);s.__esModule&&(s=s.default),"string"===typeof s&&(s=[[t.id,s,""]]),s.locals&&(t.exports=s.locals);var i=e(69333).A;i("17c5bac8",s,!0,{sourceMap:!1,shadowMode:!1})},67770:function(t,n,e){"use strict";e.r(n),e.d(n,{default:function(){return h}});var s,i={cPage:e(83841).A},a=function(){var t=this,n=t.$createElement,e=t._self._c||n;return e("c-page",[e("v-uni-view",{staticClass:"px-20 pt-20 pb-24"},[e("v-uni-image",{staticClass:"absolute left-0 top-0 w-full",attrs:{src:"/static/images/bg-user-index.png",mode:"widthFix"}}),e("v-uni-view",{staticClass:"relative z-1 flex gap-8",on:{click:function(n){arguments[0]=n=t.$handleEvent(n),t.onUserCardClick.apply(void 0,arguments)}}},[e("v-uni-image",{staticClass:"user-avatar",attrs:{src:t.userInfo&&t.userInfo.avatar||"/static/images/f.png"}}),e("v-uni-view",{staticClass:"fs-15 font-bold mt-8"},[t._v(t._s(t.isLogin?t.userInfo.nickname:"请点击登录"))])],1)],1),e("v-uni-view",{staticClass:"flex flex-column p-20 gap-12"},t._l(t.userMenus,(function(n){return e("v-uni-view",{key:n.text,staticClass:"flex bg-white br-8 px-20 py-12 fs-15",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.onMenuClick(n)}}},[e("v-uni-text",[t._v(t._s(n.text))]),e("v-uni-view",{staticClass:"ml-auto flex-none"},[t._v(t._s(n.postfix)),e("v-uni-text",{staticClass:"iconfont icon-jiantou"})],1)],1)})),1)],1)},r=[],u=e(42727),o=e(92771),c=e(50575),l=e(49943),p=e(45013),f=e(40658),d={data:function(){return{userIntegralInfo:{}}},computed:(0,l.A)((0,l.A)({},(0,p.mapGetters)(["isLogin","userInfo"])),{},{userMenus:function(t){var n=t.isLogin,e=t.userIntegralInfo;return[{text:"我的积分",url:"",postfix:n?"".concat(e.integral||0,"分"):""},{text:"我的创作记录",url:"/pages/ai/records/index",postfix:""},{text:"联系客服",url:"/pages/customer_service_qrcode/index",postfix:""},{text:"设置",url:"/pages/users/user_info/index",postfix:""}]}}),onShow:function(){this.loadData()},methods:{onUserCardClick:function(){this.$util.ensureLogin()},onMenuClick:function(t){return this.isLogin?t.url?void this.$util.gotoPage({url:t.url}):uni.showModal({title:"提示",content:"该功能暂未开放，敬请期待",showCancel:!1,confirmText:"我知道了"}):this.$util.ensureLogin()},loadData:function(){var t=this;return(0,c.A)((0,u.A)().mark((function n(){var e,s,i,a;return(0,u.A)().wrap((function(n){while(1)switch(n.prev=n.next){case 0:if(t.$store.getters.isLogin){n.next=2;break}return n.abrupt("return");case 2:return t.$store.dispatch("USERINFO",!0),n.next=5,t.$util.ef((0,f.hb)());case 5:e=n.sent,s=(0,o.A)(e,2),i=s[0],a=s[1],i||(t.userIntegralInfo=a.data);case 10:case"end":return n.stop()}}),n)})))()}}},v=d,x=(e(42060),e(18535)),g=(0,x.A)(v,a,r,!1,null,"2cbaba78",null,!1,i,s),h=g.exports},6376:function(t,n,e){"use strict";e.r(n);var s=e(45522),i=e.n(s),a=e(67643),r=e.n(a),u=r()(i());u.push([t.id,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.user-avatar[data-v-2cbaba78]{width:%?146?%;height:%?146?%;border-radius:100%}',""]),n["default"]=u}}]);