<template>
	<web-view class="web-view" :webview-styles="webviewStyles" :src="url"></web-view>
</template>

<script>
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
		ERP_REGISTER_URI
	} from '@/config/app';
	export default {
		data() {
			return {
				webviewStyles: {
					progress: {
						color: 'transparent'
					}
				},
				url: ''
			}
		},
		onLoad(options) {
			if (options.url) {
				let url = decodeURIComponent(options.url)
				this.setData({
					url: url
				})
			} else if (!options.do || !options.m) {
				uni.navigateBack({
					delta: 1
				})
			} else {
				this.setData({
					url: `${ERP_REGISTER_URI}?i=4&c=entry&do=${options.do}&m=${options.m}&adtag=${options?.adtag}&pid=${options?.pid}`
				})
			}

		},
		methods: {
			setData(obj) {
				let that = this;
				let keys = [];
				let val, data;
				Object.keys(obj).forEach(function(key) {
					keys = key.split('.');
					val = obj[key];
					data = that.$data;
					keys.forEach(function(key2, index) {
						if (index + 1 == keys.length) {
							that.$set(data, key2, val);
						} else {
							if (!data[key2]) {
								that.$set(data, key2, {});
							}
						}
						data = data[key2];
					})
				});
			}
		}
	}
</script>
