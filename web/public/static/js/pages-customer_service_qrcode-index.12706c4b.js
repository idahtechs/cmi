"use strict";(self["webpackChunkcmi_uni_app"]=self["webpackChunkcmi_uni_app"]||[]).push([[432],{61892:function(e,t,n){var i;n.d(t,{A:function(){return p}});var l,a=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("v-uni-view",{on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.handleDownload.apply(void 0,arguments)}}},[e._t("default",[e._v("下载")])],2)},r=[],o=n(42727),s=n(50575),c={props:{fileUrl:String,fileName:String,type:{type:String,default:"image"}},methods:{handleDownload:function(){this.fileUrl&&("image"===this.type?this.downloadImage(this.fileUrl):this.downloadFile(this.fileUrl))},downloadImage:function(e){var t=this;return(0,s.A)((0,o.A)().mark((function n(){return(0,o.A)().wrap((function(n){while(1)switch(n.prev=n.next){case 0:uni.showLoading(),t.$util.h5DownloadImage(e,{filename:t.fileName||"download.png"}),uni.hideLoading();case 3:case"end":return n.stop()}}),n)})))()},downloadFile:function(e){return(0,s.A)((0,o.A)().mark((function e(){return(0,o.A)().wrap((function(e){while(1)switch(e.prev=e.next){case 0:console.warn("尚未实现文件下载");case 1:case"end":return e.stop()}}),e)})))()}}},u=c,f=n(18535),d=(0,f.A)(u,a,r,!1,null,null,null,!1,i,l),p=d.exports},18871:function(e,t,n){n.r(t),n.d(t,{default:function(){return p}});var i,l={cPage:n(83841).A,downloadButton:n(61892).A},a=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("c-page",{attrs:{flex:!0}},[n("v-uni-view",{staticClass:"absolute left-0 top-0 w-full h-full flex-1 flex flex-column"},[n("v-uni-view",{staticClass:"relative z-1 flex-1 flex flex-column justify-content-center align-items-center p-20"},[n("v-uni-image",{staticClass:"absolute z--1 left-0 top-0 w-full h-full",attrs:{src:"/static/images/bg-customer-service-qrcode.png",mode:"aspectFill"}}),n("v-uni-view",{staticStyle:{"font-size":"92rpx","font-weight":"bold",color:"white","text-shadow":"0 8rpx 8rpx rgba(5, 96, 201, 0.7)","margin-bottom":"74rpx"}},[e._v("扫码联系客服")]),n("v-uni-image",{staticClass:"w-full br-20",attrs:{src:e.service_qrcode,mode:"widthFix","show-menu-by-longpress":!0}})],1),n("v-uni-view",{staticClass:"p-20 flex-none"},[n("download-button",{staticClass:"cmi-btn cmi-btn-primary",attrs:{"file-url":e.service_qrcode,"file-name":"客服二维码.png"}},[e._v("保存二维码")])],1)],1)],1)},r=[],o=n(49943),s=n(24384),c={computed:(0,o.A)({},(0,s.f4)(["service_qrcode"]))},u=c,f=n(18535),d=(0,f.A)(u,a,r,!1,null,null,null,!1,l,i),p=d.exports}}]);