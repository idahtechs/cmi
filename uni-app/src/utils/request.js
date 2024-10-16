// +----------------------------------------------------------------------
// | CRMEB [ CRMEB赋能开发者，助力企业发展 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEB并不是自由软件，未经许可不能去掉CRMEB相关版权
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------
import {
	HTTP_REQUEST_URL,
	HEADER,
	TOKENNAME
} from '@/config/app';
import { checkLogin } from '../libs/login';
import store from '../store';
import pako from '../plugin/pako/pako.es5.min.js'
function toLogin(){
	store.commit("LOGOUT");
	uni.showToast({
		title: '请登录',
		icon: 'none',
		duration: 1000
	});
}
function decompress(str) {
	return pako.inflateRaw(base64ToUint8Array(str), {
		to: 'string'
	});
}
function base64ToUint8Array(base64String) {
  let padding = '='.repeat((4 - base64String.length % 4) % 4);
  let base64 = (base64String + padding)
    .replace(/\-/g, '+')
    .replace(/_/g, '/');
  let rawData = atob(base64);
  let outputArray = new Uint8Array(rawData.length);
  for (var i = 0; i < rawData.length; ++i) {
    outputArray[i] = rawData.charCodeAt(i);
  }
  return outputArray;
}
function atob(input) {
  const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
  let str = input.replace(/=+$/, '');
  let output = '';
  if (str.length % 4 === 1) {
    throw new Error('InvalidLengthError');
  }
  for (let i = 0, len = str.length; i < len; i += 4) {
    const a = chars.indexOf(str.charAt(i));
    const b = chars.indexOf(str.charAt(i + 1));
    const c = chars.indexOf(str.charAt(i + 2));
    const d = chars.indexOf(str.charAt(i + 3));
    const sum = (a << 18) | (b << 12) | (c << 6) | d;
    output += String.fromCharCode((sum >> 16) & 0xFF, (sum >> 8) & 0xFF, sum & 0xFF);
  }
 
  return output;
}
/**
 * 发送请求
 */
function baseRequest(url, method, data, {
	noAuth = false,
	noVerify = false
}) {
	let Url = HTTP_REQUEST_URL,
		header = HEADER;
	if (!noAuth) {
		//登录过期自动登录
		if (!store.state.app.token && !checkLogin()) {
			toLogin();
			return Promise.reject({
				msg: '未登录'
			});
		}
	}
	if (store.state.app.token) header[TOKENNAME] = 'Bearer ' + store.state.app.token;
	return new Promise((reslove, reject) => {
		uni.request({
			url: Url + '/api/' + url,
			method: method || 'GET',
			header: header,
			timeout: 5 * 60 * 1000, // 5分钟超时
			data: data || {},
			success: (res) => {
				if (res.data && res.data.encode) {
					try
					{
						res.data.data = JSON.parse(decompress(res.data.data));
					}catch(e){
						res.data.data = decompress(decodeURI(res.data.data));
					}
				}
				if (noVerify)
					reslove(res.data, res);
				else if (res.data.status == 200)
					reslove(res.data, res);
				else if ([410000, 410001, 410002, 40000].indexOf(res.data.status) !== -1) {
					toLogin();
					reject(res.data);
				} else if (res.data.status == 501) {
					uni.reLaunch({
						url: '/pages/error/index'
					})
					reject(res.data);
				} else
					reject(res.data.message || '系统错误');
			},
			fail: (message) => {
				reject('请求失败');
			}
		})
	});
}
const request = {};
['options', 'get', 'post', 'put', 'head', 'delete', 'trace', 'connect'].forEach((method) => {
	request[method] = (api, data, opt) => baseRequest(api, method, data, opt || {})
});
export default request;


/**
 * 带cache的请求
 * @param {*} requestFn 请求声明
 * @param {*} options
 * @param {number} options.cacheMs 缓存时间，毫秒
 * @returns 
 */
export const createCacheRequest = (requestFn, { cacheMs = 5 * 60 * 1000 } = {}) => {
  const cacheRequest = {}
	const timeouts = {}
	const getKey = args => JSON.stringify(args)

  const innerCall = (...args) => {
    const key = getKey(args)
    if (!cacheRequest[key]) {
      cacheRequest[key] = requestFn(...args)
      timeouts[key] = setTimeout(() => { delete cacheRequest[key] }, cacheMs)
			cacheRequest[key].catch(() => {
				delete cacheRequest[key]
				clearTimeout(timeouts[key])
			})
    }

    return cacheRequest[key]
  }

	innerCall.reload = (...args) => {
		const key = getKey(args)
		delete cacheRequest[key]
		clearTimeout(timeouts[key])
		return innerCall(...args)
	}

	return innerCall
}
