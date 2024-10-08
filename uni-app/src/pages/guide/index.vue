<template>
	<view class="main">
		<guide v-if="guidePages" :advData="advData"></guide>
	</view>
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
	import guide from '@/components/guide/index.vue'
	import Cache from '@/utils/cache';
	import { setStorage } from '@/libs/uniApi.js';
	import {
		getOpenAdv
	} from '@/api/api.js'
	export default {
		components: {
			guide
		},
		data() {
			return {
				guidePages: false,
				frequency: '',
				advData: []
			}
		},
		onLoad(options) {
			if(options.spid)setStorage('spid', options.spid);
		},
		onShow() {
			// #ifdef H5
			if(this.$wechat.isWeixin()){
				this.$wechat.wechat();
			}
			// #endif
			this.loadExecution()
		},
		methods: {
			loadExecution() {
				getOpenAdv().then(res => {
					this.frequency = res.data.config.open_screen_space
					const tagDate = uni.getStorageSync('guideDate') || '',
					nowDate = new Date();
					if(tagDate >= nowDate && res.data.config.open_screen_space!=0){
						uni.switchTab({
							url: '/pages/index/index'
						});
						return
					}else{
						if (res.data.config.open_screen_switch == "" || res.data.list.length == 0) {
							uni.switchTab({
								url: '/pages/index/index'
							});
						} else if (res.data.config.open_screen_switch && res.data.list.length) {
							this.advData = res.data
							let curTime = new Date(), addHour = curTime.setHours(curTime.getHours()+res.data.config.open_screen_space);
							uni.setStorageSync('guideDate', new Date(addHour))
							this.guidePages = true
						}
					}
				}).catch(err => {
					uni.switchTab({
						url: '/pages/index/index'
					});
				})
			}
		},
		onHide() {
			this.guidePages = false
		}
	}
</script>

<style>
	page,
	.main {
		width: 100%;
		height: 100%;
	}
</style>
