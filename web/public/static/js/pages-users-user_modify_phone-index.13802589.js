(self["webpackChunkcmi_uni_app"]=self["webpackChunkcmi_uni_app"]||[]).push([[825],{10686:function(t,e,i){var n=i(97362);n.__esModule&&(n=n.default),"string"===typeof n&&(n=[[t.id,n,""]]),n.locals&&(t.exports=n.locals);var a=i(69333).A;a("0f7fc8e6",n,!0,{sourceMap:!1,shadowMode:!1})},47876:function(t,e,i){"use strict";var n;i.r(e),i.d(e,{default:function(){return b}});var a,s=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{style:t.viewColor},[i("v-uni-form",{attrs:{"report-submit":"true"}},[i("v-uni-view",[i("v-uni-view",{staticClass:"ChangePassword"},[i("v-uni-view",{staticClass:"list"},[i("v-uni-view",{staticClass:"item"},[i("v-uni-text",{staticClass:"phone"},[t._v(t._s(t.userInfo.phone))])],1),i("v-uni-view",{staticClass:"item"},[i("v-uni-input",{attrs:{type:"number",placeholder:"新手机号","placeholder-class":"placeholder"},model:{value:t.phone,callback:function(e){t.phone=e},expression:"phone"}})],1),i("v-uni-view",{staticClass:"item acea-row row-between-wrapper"},[i("v-uni-input",{staticClass:"codeIput",attrs:{type:"number",placeholder:"验证码","placeholder-class":"placeholder"},model:{value:t.captcha,callback:function(e){t.captcha=e},expression:"captcha"}}),i("v-uni-button",{staticClass:"code",class:!0===t.disabled?"on":"",attrs:{disabled:t.disabled},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.handleVerify.apply(void 0,arguments)}}},[t._v(t._s(t.text))])],1),t.isShowCode?i("v-uni-view",{staticClass:"item acea-row row-between-wrapper"},[i("v-uni-input",{staticClass:"codeIput",attrs:{type:"text","placeholder-class":"placeholder",placeholder:"填写验证码"},model:{value:t.codeVal,callback:function(e){t.codeVal=e},expression:"codeVal"}}),i("v-uni-view",{staticClass:"code",on:{click:function(e){arguments[0]=e=t.$handleEvent(e)}}},[i("v-uni-image",{staticClass:"code-img",attrs:{src:t.codeUrl}})],1)],1):t._e()],1)],1),i("v-uni-button",{staticClass:"confirmBnt",attrs:{"form-type":"submit"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.confirmSubmit.apply(void 0,arguments)}}},[t._v("确认")])],1)],1),i("Verify",{ref:"verify",attrs:{captchaType:"blockPuzzle",imgSize:{width:"330px",height:"155px"}},on:{success:function(e){arguments[0]=e=t.$handleEvent(e),t.success.apply(void 0,arguments)}}})],1)},o=[],c=i(42727),r=i(50575),l=(i(27495),i(90906),i(76935)),d=i(21629),u=i(40658),h=i(45013),p=i(53806),f=i(7379),v={mixins:[l.A],components:{Verify:f.A},data:function(){return{userInfo:{},phone:"",captcha:"",key:"",codeVal:"",codeUrl:"",disabled:!1,isShowCode:!1}},computed:(0,h.mapGetters)(["isLogin","viewColor"]),onLoad:function(){this.isLogin?this.getUserInfo():(0,p.N)()},methods:{getUserInfo:function(){var t=this;(0,u.ug)().then((function(e){t.userInfo=e.data}))},confirmSubmit:function(){var t=this;return t.phone?/^1(3|4|5|7|8|9|6)\d{9}$/i.test(t.phone)?t.captcha?void(0,d.z3)({phone:t.phone,sms_code:t.captcha}).then((function(e){return t.$util.Tips({title:"修改成功！",icon:"success"},{tab:5,url:"/pages/users/user_info/index"})})).catch((function(e){return t.$util.Tips({title:e})})):t.$util.Tips({title:"请填写验证码"}):t.$util.Tips({title:"请输入正确的手机号码！"}):t.$util.Tips({title:"请填写手机号码！"})},code:function(t){var e=this;return(0,r.A)((0,c.A)().mark((function i(){var n;return(0,c.A)().wrap((function(i){while(1)switch(i.prev=i.next){case 0:if(n=e,n.phone){i.next=3;break}return i.abrupt("return",n.$util.Tips({title:"请填写手机号码！"}));case 3:if(/^1(3|4|5|7|8|9|6)\d{9}$/i.test(n.phone)){i.next=5;break}return i.abrupt("return",n.$util.Tips({title:"请输入正确的手机号码！"}));case 5:return n.disabled=!0,i.next=8,(0,u.t2)({phone:n.phone,code:n.captcha,type:"change_phone",captchaType:"blockPuzzle",captchaVerification:t.captchaVerification}).then((function(t){n.disabled=!1,n.$util.Tips({title:t.msg}),n.sendCode()})).catch((function(t){return n.disabled=!1,n.$util.Tips({title:t})}));case 8:case"end":return i.stop()}}),i)})))()},getcaptcha:function(){var t=this;(0,u.z8)().then((function(e){t.codeUrl=e.data.captcha})),t.isShowCode=!0},success:function(t){this.$refs.verify.hide(),this.code(t)},handleVerify:function(){this.$refs.verify.show()}}},m=v,w=(i(10686),i(18535)),g=(0,w.A)(m,s,o,!1,null,"21227607",null,!1,n,a),b=g.exports},97362:function(t,e,i){"use strict";i.r(e);var n=i(45522),a=i.n(n),s=i(67643),o=i.n(s),c=o()(a());c.push([t.id,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.ChangePassword[data-v-21227607]{background:#fff;padding-top:%?53?%}.ChangePassword .phone[data-v-21227607]{font-size:%?32?%}.ChangePassword .list[data-v-21227607]{width:%?580?%;margin:0 auto}.ChangePassword .list .item[data-v-21227607]{width:100%;height:%?110?%;display:flex;align-items:center;border-bottom:%?2?% solid #f0f0f0}.ChangePassword .list .item uni-input[data-v-21227607]{width:100%;height:100%;font-size:%?32?%}.ChangePassword .list .item .placeholder[data-v-21227607]{color:#b9b9bc}.ChangePassword .list .item uni-input.codeIput[data-v-21227607]{width:%?340?%}.ChangePassword .list .item .code[data-v-21227607]{font-size:%?32?%;position:relative;padding-left:%?26?%;color:var(--view-theme)}.ChangePassword .list .item .code[data-v-21227607]::before{content:"";width:%?1?%;height:%?30?%;position:absolute;top:%?10?%;left:0;background:#ddd;display:inline-block}.ChangePassword .list .item .code[disabled][data-v-21227607]{background:transparent}.ChangePassword .list .item .code.on[data-v-21227607]{color:#b9b9bc!important}.ChangePassword .list .item .code-img[data-v-21227607]{width:%?100?%;height:%?50?%}.confirmBnt[data-v-21227607]{font-size:%?32?%;width:%?580?%;height:%?90?%;border-radius:%?45?%;color:#fff;margin:%?70?% auto 0 auto;text-align:center;line-height:%?90?%;background-color:var(--view-theme)}.getPhoneBtn[data-v-21227607]{font-size:%?32?%;width:%?580?%;height:%?90?%;border-radius:%?45?%;border:1px solid var(--view-theme);color:var(--view-theme);margin:%?40?% auto 0 auto;text-align:center;line-height:%?90?%}.getPhoneBtn .iconfont[data-v-21227607]{font-size:%?32?%;margin-right:%?12?%}',""]),e["default"]=c}}]);