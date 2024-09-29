// +----------------------------------------------------------------------
// | CRMEB [ CRMEB赋能开发者，助力企业发展 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEB并不是自由软件，未经许可不能去掉CRMEB相关版权
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------
// #ifdef H5
let VUE_APP_WS_URL = `ws://${location.hostname}?type=user`
// #endif

let openPlantGrass = '-openPlantGrass-'

// 网络接口修改此字符 小程序域名要求https
// let httpApi = 'http://192.168.31.106:8324' //测试
let httpApi = 'https://mer1.crmeb.net/' //生产

// 聊天接口修改此字符 小程序聊天要求wss 例如：wss://mer.crmeb.net
// let wsApi = 'ws://192.168.3.20:8324'
let wsApi = 'wss://mer1.crmeb.net'

module.exports = {
	// 请求域名 格式： https://您的域名
	// #ifdef MP || APP-PLUS
	// HTTP_REQUEST_URL: httpApi,
	HTTP_REQUEST_URL: httpApi,
	VUE_APP_WS_URL: `${wsApi}?type=user`,
	// #endif

	// #ifdef H5
	//H5接口是浏览器地址
	HTTP_REQUEST_URL: httpApi || window.location.protocol + "//" + window.location.host,
	// 聊天长连接地址
	VUE_APP_WS_URL: wsApi ? `${wsApi}?type=user` : VUE_APP_WS_URL,
	// #endif
	openPlantGrass: openPlantGrass,
	HEADER: {
		'content-type': 'application/json',
		//#ifdef H5
		'Form-type': navigator.userAgent.toLowerCase().indexOf("micromessenger") !== -1 ? 'wechat' : 'h5',
		//#endif
		//#ifdef MP
		'Form-type': 'routine',
		//#endif
		//#ifdef APP-PLUS
		'Form-type': 'app',
		//#endif
	},
	// 回话密钥名称 请勿修改此配置
	TOKENNAME: 'X-Token',
	// 缓存时间 0 永久
	EXPIRE: 0,
};
