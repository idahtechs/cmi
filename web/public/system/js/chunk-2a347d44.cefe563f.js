(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2a347d44"],{"0152":function(t,e,a){"use strict";a.r(e);var i=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"divBox"},[i("el-card",{staticClass:"box-card"},[i("div",{staticClass:"clearfix",attrs:{slot:"header"},slot:"header"},[i("el-form",{attrs:{inline:"",size:"small"}},[i("el-form-item",{attrs:{label:"状态："}},[i("el-select",{attrs:{placeholder:"状态",clearable:""},on:{change:t.getList},model:{value:t.tableFrom.status,callback:function(e){t.$set(t.tableFrom,"status",e)},expression:"tableFrom.status"}},t._l(t.optionsData,(function(t){return i("el-option",{key:t.value,attrs:{label:t.label,value:t.value}})})),1)],1),t._v(" "),i("el-form-item",{attrs:{label:"关键字："}},[i("el-input",{staticClass:"selWidth",attrs:{placeholder:"请输入关键字"},nativeOn:{keyup:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.getList(e)}},model:{value:t.tableFrom.keyword,callback:function(e){t.$set(t.tableFrom,"keyword",e)},expression:"tableFrom.keyword"}},[i("el-button",{staticClass:"el-button-solt",attrs:{slot:"append",icon:"el-icon-search"},on:{click:t.getList},slot:"append"})],1)],1)],1),t._v(" "),i("el-button",{attrs:{size:"small",type:"primary"},on:{click:t.onAdd}},[t._v("添加客服")])],1),t._v(" "),i("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.loading,expression:"loading"}],staticStyle:{width:"100%"},attrs:{data:t.tableData.data,size:"small"}},[i("el-table-column",{attrs:{prop:"service_id",label:"ID","min-width":"60"}}),t._v(" "),i("el-table-column",{attrs:{prop:"user.nickname",label:"微信用户名称","min-width":"130"}}),t._v(" "),i("el-table-column",{attrs:{label:"客服头像","min-width":"80"},scopedSlots:t._u([{key:"default",fn:function(t){return[i("div",{staticClass:"demo-image__preview"},[i("el-image",{staticClass:"tabImage",attrs:{src:t.row.avatar,"preview-src-list":[t.row.avatar]}})],1)]}}])}),t._v(" "),i("el-table-column",{attrs:{prop:"nickname",label:"客服名称","min-width":"130"}}),t._v(" "),i("el-table-column",{attrs:{label:"帐号状态","min-width":"100"},scopedSlots:t._u([{key:"default",fn:function(e){return[i("el-switch",{attrs:{"active-value":1,"inactive-value":0,disabled:!e.row.user||e.row.user.cancel_time,"active-text":"开","inactive-text":"关"},nativeOn:{click:function(a){return t.onchangeIsShow(e.row)}},model:{value:e.row.is_open,callback:function(a){t.$set(e.row,"is_open",a)},expression:"scope.row.is_open"}})]}}])}),t._v(" "),i("el-table-column",{attrs:{prop:"create_time",label:"添加时间","min-width":"150"}}),t._v(" "),i("el-table-column",{attrs:{label:"操作","min-width":"150",fixed:"right"},scopedSlots:t._u([{key:"default",fn:function(e){return[e.row.is_open&&e.row.status?i("el-button",{attrs:{type:"text",size:"small"},on:{click:function(a){return t.login(e.row.service_id,e.$index)}}},[t._v("进入工作台")]):t._e(),t._v(" "),i("el-button",{attrs:{type:"text",size:"small"},on:{click:function(a){return t.goList(e.row.service_id,e.$index)}}},[t._v("聊天记录")]),t._v(" "),e.row.user&&!e.row.user.cancel_time?i("el-button",{attrs:{type:"text",size:"small"},on:{click:function(a){return t.onEdit(e.row.service_id)}}},[t._v("编辑")]):t._e(),t._v(" "),i("el-button",{attrs:{type:"text",size:"small"},on:{click:function(a){return t.onDel(e.row.service_id,e.$index)}}},[t._v("删除")])]}}])})],1),t._v(" "),i("div",{staticClass:"block"},[i("el-pagination",{attrs:{"page-sizes":[20,40,60,80],"page-size":t.tableFrom.limit,"current-page":t.tableFrom.page,layout:"total, sizes, prev, pager, next, jumper",total:t.tableData.total},on:{"size-change":t.handleSizeChange,"current-change":t.pageChange}})],1)],1),t._v(" "),i("el-dialog",{attrs:{title:"聊天记录",width:t.isChat?"600px":"800px",visible:t.dialogTableVisible},on:{"update:visible":function(e){t.dialogTableVisible=e}}},[t.isChat?i("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.loadingChat,expression:"loadingChat"}],key:"isIndex",attrs:{data:t.tableChatData.data}},[i("el-table-column",{attrs:{property:"user.nickname",label:"用户名称","min-width":"100"}}),t._v(" "),i("el-table-column",{attrs:{label:"用户头像","min-width":"80"},scopedSlots:t._u([{key:"default",fn:function(t){return[t.row.user.avatar?i("img",{staticClass:"tabImage",attrs:{src:t.row.user.avatar}}):i("img",{staticClass:"tabImage",attrs:{src:a("cdfe")}})]}}],null,!1,3196233401)}),t._v(" "),i("el-table-column",{attrs:{label:"操作",fixed:"right","min-width":"80"},scopedSlots:t._u([{key:"default",fn:function(e){return[i("el-button",{attrs:{type:"text",size:"small"},on:{click:function(a){return t.check(e.row.uid,e.$index)}}},[t._v("查看对话")])]}}],null,!1,252509333)})],1):t._e(),t._v(" "),t.isChat?t._e():i("el-button",{attrs:{type:"primary",size:"small"},on:{click:t.goBack}},[t._v("返回聊天记录")]),t._v(" "),t.isChat?t._e():i("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.loadingChat,expression:"loadingChat"}],key:"isIndexs",attrs:{data:t.tableServiceData.data}},[i("el-table-column",{attrs:{label:"发送人","min-width":"100"},scopedSlots:t._u([{key:"default",fn:function(e){return[i("span",[t._v(t._s(0===e.row.send_type?e.row.user.nickname:e.row.service.nickname))])]}}],null,!1,1916843481)}),t._v(" "),i("el-table-column",{attrs:{label:"发送人头像","min-width":"80"},scopedSlots:t._u([{key:"default",fn:function(t){return[t.row.user.avatar?i("img",{staticClass:"tabImage",attrs:{src:t.row.user.avatar}}):i("img",{staticClass:"tabImage",attrs:{src:a("cdfe")}})]}}],null,!1,3196233401)}),t._v(" "),i("el-table-column",{attrs:{label:"发送消息","min-width":"100"},scopedSlots:t._u([{key:"default",fn:function(e){return[2===e.row.msn_type?i("span",{staticClass:"tabImage"},[t._v("["+t._s(e.row.msn)+"]")]):3===e.row.msn_type?i("img",{staticClass:"tabImage",attrs:{src:e.row.msn}}):i("span",[t._v(t._s(e.row.msn))])]}}],null,!1,3176559001)}),t._v(" "),i("el-table-column",{attrs:{prop:"create_time",label:"发送时间","min-width":"100"}})],1),t._v(" "),i("div",{staticClass:"block"},[i("el-pagination",{attrs:{"page-sizes":[20,40,60,80],"page-size":t.tableFrom.limit,"current-page":t.tableFrom.page,layout:"total, sizes, prev, pager, next, jumper",total:t.isChat?t.tableChatData.total:t.tableServiceData.total},on:{"size-change":t.handleSizeChangeChat,"current-change":t.pageChangeChat}})],1)],1)],1)},s=[],l=a("8593"),n=a("a78e"),o=a.n(n),r=a("bbcc"),c=[{value:"1",label:"显示"},{value:"0",label:"隐藏"}],u={name:"Service",data:function(){return{isChat:!1,loadingChat:!1,dialogTableVisible:!1,optionsData:c,loading:!1,tableData:{data:[],total:0},tableFrom:{page:1,limit:20,keyword:"",status:""},tableChatFrom:{page:1,limit:8},tableChatData:{data:[],total:0},tableServiceData:{data:[],total:0},serviceId:0,uid:""}},mounted:function(){this.getList()},methods:{login:function(t){var e=this;Object(l["V"])(t).then((function(t){o.a.set("SerToken",t.data.token),window.open(r["a"].httpUrl+t.data.url)})).catch((function(t){e.$message.error(t.message)}))},getList:function(){var t=this;this.loading=!0,Object(l["U"])(this.tableFrom).then((function(e){t.tableData.data=e.data.list,t.tableData.total=e.data.count,t.loading=!1})).catch((function(e){t.$message.error(e.message),t.loading=!1}))},pageChange:function(t){this.tableFrom.page=t,this.getList()},handleSizeChange:function(t){this.tableFrom.limit=t,this.getList()},goList:function(t){this.serviceId=t,this.dialogTableVisible=!0,this.tableChatFrom.page=1,this.getListChat(),this.isChat=!0},goBack:function(){this.tableChatFrom.page=1,this.getListChat(),this.isChat=!0},check:function(t){this.uid=t,this.serviceChatUidList(t),this.isChat=!1},getListChat:function(){var t=this;this.loadingChat=!0,Object(l["Q"])(this.serviceId,this.tableChatFrom).then((function(e){t.tableChatData.data=e.data.list,t.tableChatData.total=e.data.count,t.loadingChat=!1})).catch((function(e){t.$message.error(e.message),t.loadingChat=!1}))},serviceChatUidList:function(t){var e=this;this.loadingChat=!0,Object(l["R"])(this.serviceId,t,this.tableChatFrom).then((function(t){e.tableChatData.data=[],e.tableServiceData.data=t.data.list,e.tableServiceData.total=t.data.count,e.loadingChat=!1})).catch((function(t){e.$message.error(t.message),e.loadingChat=!1}))},pageChangeChat:function(t){this.tableChatFrom.page=t,this.isChat?this.getListChat():this.serviceChatUidList(this.uid)},handleSizeChangeChat:function(t){this.tableChatFrom.limit=t,this.getListChat(),this.isChat?this.getListChat():this.serviceChatUidList(this.uid)},onAdd:function(){var t=this;this.$modalForm(Object(l["S"])()).then((function(){return t.getList()}))},onEdit:function(t){var e=this;this.$modalForm(Object(l["X"])(t)).then((function(){return e.getList()}))},onDel:function(t,e){var a=this;this.$modalSure("删除该客服吗").then((function(){Object(l["T"])(t).then((function(t){var i=t.message;a.$message.success(i),a.tableData.data.splice(e,1)})).catch((function(t){var e=t.message;a.$message.error(e)}))}))},onchangeIsShow:function(t){var e=this;Object(l["W"])(t.service_id,t.is_open).then((function(t){var a=t.message;e.$message.success(a),e.getList()})).catch((function(t){var a=t.message;e.$message.error(a)}))}}},d=u,h=(a("c69e"),a("2877")),m=Object(h["a"])(d,i,s,!1,null,"7a2761c6",null);e["default"]=m.exports},9071:function(t,e,a){},c69e:function(t,e,a){"use strict";a("9071")},cdfe:function(t,e,a){t.exports=a.p+"system/img/f.5aa43cd3.png"}}]);