(self["webpackChunkcmi_uni_app"]=self["webpackChunkcmi_uni_app"]||[]).push([[360],{31476:function(t,e,i){var a=i(38723);a.__esModule&&(a=a.default),"string"===typeof a&&(a=[[t.id,a,""]]),a.locals&&(t.exports=a.locals);var n=i(69333).A;n("5213b653",a,!0,{sourceMap:!1,shadowMode:!1})},45141:function(t,e,i){var a=i(13898);a.__esModule&&(a=a.default),"string"===typeof a&&(a=[[t.id,a,""]]),a.locals&&(t.exports=a.locals);var n=i(69333).A;n("3e50b0d6",a,!0,{sourceMap:!1,shadowMode:!1})},78693:function(t,e,i){var a=i(81921);a.__esModule&&(a=a.default),"string"===typeof a&&(a=[[t.id,a,""]]),a.locals&&(t.exports=a.locals);var n=i(69333).A;n("e6022592",a,!0,{sourceMap:!1,shadowMode:!1})},79358:function(t,e,i){var a=i(10930);a.__esModule&&(a=a.default),"string"===typeof a&&(a=[[t.id,a,""]]),a.locals&&(t.exports=a.locals);var n=i(69333).A;n("4dd00268",a,!0,{sourceMap:!1,shadowMode:!1})},4101:function(t,e,i){var a=i(88425);a.__esModule&&(a=a.default),"string"===typeof a&&(a=[[t.id,a,""]]),a.locals&&(t.exports=a.locals);var n=i(69333).A;n("36e2daf4",a,!0,{sourceMap:!1,shadowMode:!1})},69015:function(t,e,i){"use strict";var a;i.r(e),i.d(e,{default:function(){return F}});var n,o,s,c,r,d=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{staticClass:"wrapper",style:t.viewColor},[i("v-uni-view",{staticClass:"bag"},[i("v-uni-image",{staticClass:"bg",attrs:{src:"/static/images/bg-login.png",mode:"widthFix"}})],1),i("v-uni-view",{staticClass:"system-height",style:{height:t.statusBarHeight}}),i("v-uni-view",{staticClass:"merchant-msg"},[i("img",{attrs:{src:t.login_logo}}),i("v-uni-view",{staticClass:"name"},[t._v(t._s(t.site_name))]),i("v-uni-view",{staticClass:"color-gray fs-20"},[t._v("你的创意助手，文案新玩法")])],1),i("v-uni-view",{staticClass:"wechat_login"},[i("v-uni-view",{staticClass:"btn-wrapper"},[i("v-uni-button",{staticClass:"bg-theme btn1",attrs:{"hover-class":"none"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.wechatLogin.apply(void 0,arguments)}}},[t._v("微信登陆/注册")])],1)],1),t.canGetPrivacySetting?t._e():i("v-uni-view",{staticClass:"protocol"},[i("v-uni-checkbox-group",{on:{click:function(e){e.stopPropagation(),arguments[0]=e=t.$handleEvent(e),t.ChangeIsDefault.apply(void 0,arguments)}}},[i("v-uni-checkbox",{class:t.inAnimation?"trembling":"",attrs:{checked:!!t.protocol},on:{animationend:function(e){arguments[0]=e=t.$handleEvent(e),t.inAnimation=!1}}}),i("v-uni-text",{on:{click:function(e){e.stopPropagation(),arguments[0]=e=t.$handleEvent(e),t.ChangeIsDefault.apply(void 0,arguments)}}},[t._v("已阅读并同意")]),i("v-uni-text",{staticClass:"main-color",on:{click:function(e){e.stopPropagation(),arguments[0]=e=t.$handleEvent(e),t.privacy(4)}}},[t._v("《用户协议》")]),t._v("与"),i("v-uni-text",{staticClass:"main-color",on:{click:function(e){e.stopPropagation(),arguments[0]=e=t.$handleEvent(e),t.privacy(3)}}},[t._v("《隐私协议》")])],1)],1),[i("editUserModal",{attrs:{isShow:t.showUserUpdateModal},on:{closeEdit:function(e){arguments[0]=e=t.$handleEvent(e),t.closeEdit.apply(void 0,arguments)},editSuccess:function(e){arguments[0]=e=t.$handleEvent(e),t.editSuccess.apply(void 0,arguments)}}})]],2)},u=[],l=i(49943),p=(i(25276),function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{style:t.viewColor},[i("v-uni-view",{staticClass:"product-window",class:{on:t.isShow}},[i("v-uni-view",{staticClass:"iconfont icon-guanbi",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.closeAttr.apply(void 0,arguments)}}}),i("v-uni-view",{staticClass:"mp-data"},[i("v-uni-image",{attrs:{src:t.routine_logo,mode:""}}),i("v-uni-text",{staticClass:"mp-name"},[t._v(t._s(t.site_name)+" 申请")])],1),i("v-uni-view",{staticClass:"trip-msg"},[i("v-uni-view",{staticClass:"title"},[t._v("获取您的昵称、头像")]),i("v-uni-view",{staticClass:"trip"},[t._v("提供具有辨识度的用户中心界面")])],1),i("v-uni-form",{on:{submit:function(e){arguments[0]=e=t.$handleEvent(e),t.formSubmit.apply(void 0,arguments)}}},[i("v-uni-view",{staticClass:"edit"},[i("v-uni-view",{staticClass:"avatar edit-box"},[i("v-uni-view",{staticClass:"left"},[i("v-uni-view",{staticClass:"head"},[t._v("头像")]),t.mp_is_new?i("v-uni-button",{staticClass:"avatar-box",attrs:{"open-type":"chooseAvatar"},on:{chooseavatar:function(e){arguments[0]=e=t.$handleEvent(e),t.onChooseAvatar.apply(void 0,arguments)}}},[i("v-uni-image",{attrs:{src:t.userInfo.avatar||t.defHead}})],1):i("v-uni-view",{staticClass:"avatar-box",on:{click:function(e){e.stopPropagation(),arguments[0]=e=t.$handleEvent(e),t.uploadpic.apply(void 0,arguments)}}},[i("v-uni-image",{attrs:{src:t.userInfo.avatar||t.defHead}})],1)],1)],1),i("v-uni-view",{staticClass:"nickname edit-box"},[i("v-uni-view",{staticClass:"left"},[i("v-uni-view",{staticClass:"head"},[t._v("昵称")]),i("v-uni-view",{staticClass:"input"},[i("v-uni-input",{attrs:{type:"nickname","placeholder-class":"pl-sty",placeholder:"请输入昵称",name:"nickname",maxlength:16,value:t.userInfo.nickname}})],1)],1)],1)],1),i("v-uni-view",{staticClass:"bottom"},[i("v-uni-button",{staticClass:"save",class:{open:t.userInfo.avatar},attrs:{formType:"submit"}},[t._v("保存")])],1)],1)],1),t.canvasStatus?i("v-uni-canvas",{style:{width:t.canvasWidth+"px",height:t.canvasHeight+"px",position:"absolute",left:"-100000px",top:"-100000px"},attrs:{"canvas-id":"canvas"}}):t._e(),t.isShow?i("v-uni-view",{staticClass:"mask",on:{touchmove:function(e){e.preventDefault(),arguments[0]=e=t.$handleEvent(e)},click:function(e){arguments[0]=e=t.$handleEvent(e),t.closeAttr.apply(void 0,arguments)}}}):t._e()],1)}),v=[],f=i(45013),h=i(24384),g=i(38710),m=i(40658),w={props:{isShow:{type:Boolean,value:!1}},data:function(){return{defHead:i(56325),mp_is_new:this.$Cache.get("MP_VERSION_ISNEW")||!1,userInfo:{avatar:"",nickname:""},canvasStatus:!1}},computed:(0,h.f4)({routine_logo:"",site_name:""},(0,f.mapGetters)(["viewColor","keyColor"])),mounted:function(){},methods:{uploadpic:function(){var t=this,e=this;this.canvasStatus=!0,e.$util.uploadImageChange("upload/image",(function(i){var a=e.userInfo;void 0!==a&&(e.userInfo.avatar=i.data.url),t.canvasStatus=!1}),(function(e){t.canvasStatus=!1}),(function(e){t.canvasWidth=e.w,t.canvasHeight=e.h}))},onChooseAvatar:function(t){var e=this,i=t.detail.avatarUrl;this.$util.uploadImgs("upload/image",i,(function(t){e.userInfo.avatar=t.data.path}),(function(t){console.log(t)}))},closeAttr:function(){this.$emit("closeEdit")},formSubmit:function(t){var e=this,i=this;return this.userInfo.avatar?t.detail.value.nickname?(this.userInfo.nickname=t.detail.value.nickname,void(0,m.I1)(this.userInfo).then((function(t){return e.$emit("editSuccess"),i.$util.Tips({title:t.message,icon:"success"},{tab:3})})).catch((function(t){return i.$util.Tips({title:t||"保存失败"},{tab:3,url:1})}))):i.$util.Tips({title:"请输入昵称"}):i.$util.Tips({title:"请上传头像"})}}},b=w,x=(i(31476),i(78693),i(18535)),A=(0,x.A)(b,p,v,!1,null,"6bb6c87a",null,!1,n,o),_=A.exports,y=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{style:t.viewColor},[i("v-uni-view",{staticClass:"mask maskPoup",attrs:{hidden:0==t.isShow},on:{touchmove:function(e){e.preventDefault(),arguments[0]=e=t.$handleEvent(e)},click:function(e){arguments[0]=e=t.$handleEvent(e),t.rejectAgreement.apply(void 0,arguments)}}}),i("v-uni-view",{staticClass:"product-window",class:{on:t.isShow}},[i("v-uni-view",{staticClass:"mp-data"},[i("v-uni-text",{staticClass:"mp-name"},[t._v(t._s(t.site_name)+"服务与隐私协议")])],1),i("v-uni-view",{staticClass:"trip-msg"},[i("v-uni-view",{staticClass:"trip"},[t._v("欢迎您使用"+t._s(t.site_name)+"！请仔细阅读以下内容，并作出适当的选择：")])],1),i("v-uni-view",{staticClass:"trip-title"},[t._v("隐私政策概要")]),i("v-uni-view",{staticClass:"trip-msg"},[i("v-uni-view",{staticClass:"trip"},[t._v("当您点击同意并开始时用产品服务时，即表示您已理解并同息该条款内容，该条款将对您产生法律约束力。如您拒绝，将无法继续下一步操作。")])],1),i("v-uni-view",{staticClass:"main-color",on:{click:function(e){e.stopPropagation(),arguments[0]=e=t.$handleEvent(e),t.privacy(3)}}},[t._v("点击阅读"+t._s(t.agreementName))]),i("v-uni-view",{staticClass:"bottom"},[i("v-uni-button",{staticClass:"save open",attrs:{type:"default",id:"agree-btn","open-type":"agreePrivacyAuthorization"},on:{agreeprivacyauthorization:function(e){arguments[0]=e=t.$handleEvent(e),t.handleAgree.apply(void 0,arguments)}}},[t._v("同意并继续")]),i("v-uni-button",{staticClass:"reject",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.rejectAgreement.apply(void 0,arguments)}}},[t._v("取消")])],1)],1)],1)},C=[],k=i(27959),S=(getApp(),{data:function(){return{isShow:!1,agreementName:"",mpData:uni.getStorageSync("copyRight")}},computed:(0,l.A)({},(0,h.f4)({site_name:""},(0,f.mapGetters)(["viewColor"]))),mounted:function(){var t=this;wx.getPrivacySetting({success:function(e){console.log(e.needAuthorization),e.needAuthorization?(t.isShow=!0,t.agreementName=e.privacyContractName):t.$emit("onAgree")},fail:function(){},complete:function(){}})},methods:{handleAgree:function(){this.isShow=!1,this.$emit("onclose")},rejectAgreement:function(){this.isShow=!1,this.$emit("onReject")},privacy:function(t){uni.navigateTo({url:"/pages/users/privacy/index?type="+t})}}}),U=S,E=(i(45141),i(79358),(0,x.A)(U,y,C,!1,null,"7f62c06d",null,!1,s,c)),I=E.exports,P=i(57660),$=i(66427),z=i(72801),M=i(11974),T=(i(53806),getApp()),D=uni.getSystemInfoSync().statusBarHeight+"px",N={data:function(){return{domain:M.HTTP_REQUEST_URL,isUp:!1,canClose:!0,phone:"",statusBarHeight:D,isHome:!1,isPhoneBox:!1,protocol:!1,showUserUpdateModal:!1,logoUrl:"",code:"",codeVal:"",authKey:"",options:"",userInfo:{},codeNum:0,canUseGetUserProfile:!1,canGetPrivacySetting:!1,inAnimation:!1,colorStatus:uni.getStorageSync("color_status"),mp_is_new:this.$Cache.get("MP_VERSION_ISNEW")||!1,configData:g.A.get("BASIC_CONFIG"),bindPhone:!1,wechat_phone_switch:0}},computed:(0,l.A)({},(0,h.f4)({login_logo:"",site_name:"",first_avatar_switch:""},(0,f.mapGetters)(["isLogin","viewColor"]))),components:{editUserModal:_,privacyAgreementPopup:I},watch:{},onLoad:function(t){uni.getUserProfile&&(this.canUseGetUserProfile=!0);var e=getCurrentPages(),i=e[e.length-2];i&&"pages/order_addcart/order_addcart"==i.route?this.isHome=!0:this.isHome=!1},onReady:function(){this.getCode()},created:function(){},methods:{getCode:function(){var t=this;t.code=1},getAuthLogin:function(){var t=this,e=this;if(!e.protocol&&!e.canUseGetUserProfile)return e.$util.Tips({title:"请勾选用户协议与隐私政策"});uni.showLoading({title:"正在登录中"}),e.canUseGetUserProfile=!1,k.A.getUserProfile().then((function(i){var a=i.userInfo;a.code=e.code,a.spread=T.globalData.spid,a.spread_code=T.globalData.code,(0,P.gq)({auth:{type:"routine",auth:a}}).then((function(i){if(200!=i.data.status)return uni.setStorageSync("auth_token",i.data.result.key),uni.navigateTo({url:"/pages/users/login/index"});var a=i.data.result.expires_time-g.A.time();e.$store.commit("UPDATE_USERINFO",i.data.result.user),e.$store.commit("LOGIN",{token:i.data.result.token,time:a}),e.$store.commit("SETUID",i.data.result.user.uid),g.A.set($.EXPIRES_TIME,i.data.result.expires_time,a),g.A.set($.USER_INFO,i.data.result.user,a),i.data.result.user.isNew&&e.mp_is_new&&1==e.first_avatar_switch?(uni.hideLoading(),e.showUserUpdateModal=!0):t.$util.Tips({title:"授权成功",icon:"success"},{tab:3})})).catch((function(t){uni.hideLoading(),uni.showToast({title:t,icon:"none",duration:2e3})}))})).catch((function(t){uni.hideLoading()}))},onAgree:function(){this.protocol=!0},ChangeIsDefault:function(t){this.$set(this,"protocol",!this.protocol)},editSuccess:function(){this.showUserUpdateModal=!1},closeEdit:function(){this.showUserUpdateModal=!1,this.$util.Tips({title:"登录成功",icon:"success"},{tab:3})},onReject:function(){uni.switchTab({url:"/pages/index/index"})},back:function(){uni.navigateBack({delta:1})},home:function(){uni.switchTab({url:"/pages/index/index"})},maskClose:function(t){this.isUp=!1},bindPhoneClose:function(t){this.isPhoneBox=!1,t.isStatus&&this.$util.Tips({title:"登录成功",icon:"success"},{tab:3})},getPath:function(t){return-1!=t.indexOf("?")&&(t=t.split("?")[0],console.log(t)),t},getUserInfo:function(t,e){var i=this,a=this;(0,m.ug)().then((function(n){uni.hideLoading(),a.userInfo=n.data,a.$store.commit("SETUID",n.data.uid),a.$store.commit("UPDATE_USERINFO",n.data),t?i.showUserUpdateModal=!0:a.$util.Tips({title:"登录成功",icon:"success"},{tab:4,url:e||"/pages/user/index"})})).catch((function(t){uni.hideLoading(),uni.showToast({title:t.msg,icon:"none",duration:2e3})}))},privacy:function(t){uni.navigateTo({url:"/pages/users/privacy/index?type="+t})},wechatLogin:function(){if(!this.protocol)return this.$util.Tips({title:"请勾选用户协议与隐私政策"});z.A.oAuth()}}},R=N,B=(i(4101),(0,x.A)(R,d,u,!1,null,"263c1e4a",null,!1,a,r)),F=B.exports},38723:function(t,e,i){"use strict";i.r(e);var a=i(45522),n=i.n(a),o=i(67643),s=i.n(o),c=s()(n());c.push([t.id,".pl-sty[data-v-6bb6c87a]{color:#999;font-size:%?30?%}",""]),e["default"]=c},13898:function(t,e,i){"use strict";i.r(e);var a=i(45522),n=i.n(a),o=i(67643),s=i.n(o),c=s()(n());c.push([t.id,".pl-sty[data-v-7f62c06d]{color:#999;font-size:%?30?%}",""]),e["default"]=c},81921:function(t,e,i){"use strict";i.r(e);var a=i(45522),n=i.n(a),o=i(67643),s=i.n(o),c=s()(n());c.push([t.id,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.product-window.on[data-v-6bb6c87a]{-webkit-transform:translateZ(0);transform:translateZ(0);display:block}.mask[data-v-6bb6c87a]{z-index:99}.product-window[data-v-6bb6c87a]{position:fixed;bottom:0;width:100%;left:0;background-color:#fff;z-index:1000;border-radius:%?20?% %?20?% 0 0;-webkit-transform:translate3d(0,100%,0);transform:translate3d(0,100%,0);transition:all .3s cubic-bezier(.25,.5,.5,.9);padding:%?38?% %?40?%;display:none}.product-window .icon-guanbi[data-v-6bb6c87a]{position:absolute;top:%?40?%;right:%?40?%;font-size:%?24?%;font-weight:700;color:#999}.product-window .mp-data[data-v-6bb6c87a]{display:flex;align-items:center;margin-bottom:%?30?%}.product-window .mp-data .mp-name[data-v-6bb6c87a]{font-size:%?28?%;font-weight:700;color:#000}.product-window .mp-data uni-image[data-v-6bb6c87a]{width:%?48?%;height:%?48?%;border-radius:50%;margin-right:%?16?%}.product-window .trip-msg[data-v-6bb6c87a]{padding-bottom:%?32?%;border-bottom:1px solid #f5f5f5}.product-window .trip-msg .title[data-v-6bb6c87a]{font-size:%?30?%;font-weight:700;color:#000;margin-bottom:%?6?%}.product-window .trip-msg .trip[data-v-6bb6c87a]{font-size:%?26?%;color:#777}.product-window .edit[data-v-6bb6c87a]{border-bottom:1px solid #f5f5f5}.product-window .edit .avatar[data-v-6bb6c87a]{border-bottom:1px solid #f5f5f5}.product-window .edit .nickname .input[data-v-6bb6c87a]{width:100%}.product-window .edit .nickname uni-input[data-v-6bb6c87a]{height:%?80?%}.product-window .edit .edit-box[data-v-6bb6c87a]{display:flex;justify-content:space-between;align-items:center;font-size:%?30?%;padding:%?22?% 0}.product-window .edit .edit-box .left[data-v-6bb6c87a]{display:flex;align-items:center;flex:1}.product-window .edit .edit-box .left .head[data-v-6bb6c87a]{color:rgba(0,0,0,.9);white-space:nowrap;margin-right:%?60?%}.product-window .edit .edit-box .left uni-button[data-v-6bb6c87a]{flex:1;display:flex;align-items:center}.product-window .edit .edit-box uni-image[data-v-6bb6c87a]{width:%?80?%;height:%?80?%;border-radius:%?6?%}.product-window .edit .icon-xiangyou[data-v-6bb6c87a]{color:#cfcfcf}.product-window .bottom[data-v-6bb6c87a]{display:flex;align-items:center;justify-content:center}.product-window .bottom .save[data-v-6bb6c87a]{border:1px solid #f5f5f5;display:flex;align-items:center;justify-content:center;width:%?368?%;height:%?80?%;border-radius:%?12?%;margin-top:%?52?%;background-color:#f5f5f5;color:#ccc;font-size:%?30?%;font-weight:700}.product-window .bottom .save.open[data-v-6bb6c87a]{border:1px solid #fff;background-color:#07c160;color:#fff}',""]),e["default"]=c},10930:function(t,e,i){"use strict";i.r(e);var a=i(45522),n=i.n(a),o=i(67643),s=i.n(o),c=s()(n());c.push([t.id,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */.product-window.on[data-v-7f62c06d]{-webkit-transform:translateZ(0);transform:translateZ(0)}.maskPoup[data-v-7f62c06d]{z-index:999}.product-window[data-v-7f62c06d]{position:fixed;bottom:0;width:100%;left:0;background-color:#fff;z-index:1000;border-radius:%?40?% %?40?% 0 0;-webkit-transform:translate3d(0,100%,0);transform:translate3d(0,100%,0);transition:all .3s cubic-bezier(.25,.5,.5,.9);padding:%?64?% %?40?%;padding-bottom:%?38?%;padding-bottom:calc(%?38?% + constant(safe-area-inset-bottom));padding-bottom:calc(%?38?% + env(safe-area-inset-bottom));box-shadow:0 %?2?% %?10?% rgba(0,0,0,.06)}.product-window .icon-guanbi[data-v-7f62c06d]{position:absolute;top:%?40?%;right:%?40?%;font-size:%?24?%;font-weight:700;color:#999}.product-window .mp-data[data-v-7f62c06d]{display:flex;align-items:center;justify-content:center;margin-bottom:%?40?%}.product-window .mp-data .mp-name[data-v-7f62c06d]{font-size:%?34?%;font-weight:500;color:#333;line-height:%?48?%}.product-window .trip-msg[data-v-7f62c06d]{padding-bottom:%?32?%}.product-window .trip-msg .title[data-v-7f62c06d]{font-size:%?30?%;font-weight:700;color:#000;margin-bottom:%?6?%}.product-window .trip-msg .trip[data-v-7f62c06d]{color:#333;font-size:%?28?%;font-family:PingFang SC-Regular,PingFang SC;font-weight:400}.product-window .trip-title[data-v-7f62c06d]{font-size:%?28?%;font-weight:500;color:#333;margin-bottom:%?8?%}.product-window .main-color[data-v-7f62c06d]{font-size:%?28?%;font-weight:400;color:var(--view-theme);margin-bottom:%?40?%}.product-window .bottom[data-v-7f62c06d]{display:flex;align-items:center;justify-content:center;flex-direction:column}.product-window .bottom .save[data-v-7f62c06d],\n.product-window .bottom .reject[data-v-7f62c06d]{display:flex;align-items:center;justify-content:center;width:%?670?%;height:%?80?%;border-radius:%?80?%;background-color:#f5f5f5;color:#333;font-size:%?30?%;font-weight:500}.product-window .bottom .save[data-v-7f62c06d]{background-color:var(--view-theme);color:#fff;margin-bottom:%?24?%}',""]),e["default"]=c},88425:function(t,e,i){"use strict";i.r(e);var a=i(45522),n=i.n(a),o=i(67643),s=i.n(o),c=s()(n());c.push([t.id,'@charset "UTF-8";\n/**\n * 这里是uni-app内置的常用样式变量\n *\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\n *\n */\n/**\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\n *\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\n */\n/* 颜色变量 */\n/* 行为相关颜色 */\n/* 背景颜色 */\n/* 边框颜色 */\n/* 尺寸变量 */\n/* 文字尺寸 */\n/* 图片尺寸 */\n/* Border Radius */\n/* 水平间距 */\n/* 垂直间距 */\n/* 透明度 */\n/* 文章场景相关 */uni-page-body[data-v-263c1e4a]{background:#fff}body.?%PAGE?%[data-v-263c1e4a]{background:#fff}[data-v-263c1e4a] uni-checkbox .uni-checkbox-input,[data-v-263c1e4a] uni-checkbox .wx-checkbox-input{border-radius:100%;width:%?28?%;height:%?28?%}[data-v-263c1e4a] uni-checkbox .uni-checkbox-input.uni-checkbox-input-checked,[data-v-263c1e4a] uni-checkbox .wx-checkbox-input.wx-checkbox-input-checked{border:1px solid var(--view-theme)!important;background-color:var(--view-theme)!important}.wrapper[data-v-263c1e4a]{position:relative;height:100vh}.wrapper .bag[data-v-263c1e4a]{position:absolute;top:0;left:0;width:100%;opacity:.8;z-index:-1;z-index:0}.wrapper .bag .bg[data-v-263c1e4a]{width:100%}.wrapper .merchant-msg[data-v-263c1e4a]{padding-top:%?252?%;display:flex;justify-content:center;align-items:center;flex-direction:column;z-index:2;position:relative}.wrapper .merchant-msg img[data-v-263c1e4a]{width:%?230?%;height:%?230?%}.wrapper .merchant-msg .name[data-v-263c1e4a]{font-size:%?52?%;font-weight:500;color:#333;line-height:%?56?%;margin:%?72?% 0 %?24?%}.wechat_login[data-v-263c1e4a]{margin-top:%?200?%}.wechat_login .img uni-image[data-v-263c1e4a]{width:100%}.wechat_login .btn-wrapper[data-v-263c1e4a]{padding:0 %?66?%}.wechat_login .btn-wrapper uni-button[data-v-263c1e4a]{width:100%;height:%?86?%;line-height:%?86?%;margin-bottom:%?40?%;border-radius:%?120?%;font-size:%?30?%}.wechat_login .btn-wrapper uni-button.btn1[data-v-263c1e4a]{color:#fff;background:var(--view-theme)}.wechat_login .btn-wrapper uni-button.btn2[data-v-263c1e4a]{color:#666;border:1px solid #ddd}.title-bar[data-v-263c1e4a]{position:relative;display:flex;align-items:center;justify-content:center;font-size:%?34?%;font-weight:500;color:#333;line-height:%?48?%}.icon[data-v-263c1e4a]{position:absolute;left:%?30?%;top:0;display:flex;align-items:center;justify-content:center;width:%?80?%;height:%?80?%}.icon uni-image[data-v-263c1e4a]{width:%?50?%;height:%?50?%}.protocol[data-v-263c1e4a]{position:fixed;bottom:%?52?%;left:0;width:100%;margin:0 auto;color:#999;font-size:%?24?%;line-height:%?22?%;text-align:center;bottom:calc(%?52?% + constant(safe-area-inset-bottom));bottom:calc(%?52?% + env(safe-area-inset-bottom))}.protocol .main-color[data-v-263c1e4a]{color:var(--view-theme)}.protocol .trembling[data-v-263c1e4a]{-webkit-animation:shake .6s;animation:shake .6s}',""]),e["default"]=c},56325:function(t){"use strict";t.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAABQCAMAAAC5zwKfAAAAYFBMVEUAAAD19fX19fX09PT09PT19fXj4+P09PTz8/P////09PSysrKzs7O9vb3x8fHHx8fr6+ve3t7U1NTh4eHY2Ni6urrm5ubc3NzBwcG3t7e1tbXl5eXJycnt7e3Q0NDMzMzKPmceAAAACnRSTlMAWuH7ruUJkZEJ7qI+3wAAAWZJREFUWMPt2QlOwzAQhWFnbevxln3pdv9bghAkJY6o23kSReQ/wCdns5Sx+CiP0jiRjJI4jXIxFWUSUBx9eXsJaj95UDGSwCIh8gwJZvm0QNgSUyyYihgLxiLBgomQ4DZwA/8vWJ0aIn1tQaDr6TN7RoDHkaaGlgPO3tzouKBp6FvaMMErLSp5oFNLcDQs8EJeNQtsfLDngI78lGGAFa3UMsB6DSwYYLkGdq+0wgJ9D43yvYH1HvY+eGWBhQ86Fuh/KifJA7uFpxwTlNrbvZhgq249LTmgv4OpIwA0g/dEeKA8zWABAe0MVgiwUDNoDRc0pabbhvLMAd1lpGXKVs+CR6totaY8PwF2mn7IFuYxsG7oTkP5CGgpIBsO1hRUHQzqMFAHgxQYHPy9S+7CwC4YlFWv7mmqr17ox2cDN/DPgehhGnzcBx9I4kemB+xQ9wAfO7+3w3k7IaDiDnu4kEVi6oA7/ngDU8P9py9tSZwAAAAASUVORK5CYII="}}]);