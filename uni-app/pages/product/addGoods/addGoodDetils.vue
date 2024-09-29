<template>
	<view class="container">
		<input-goods-detils @getProductContent="getProductContent" title="填写商品描述" :prodectContent="goodsDis" :maxLength="200"></input-goods-detils>
		<view class="handle"><view class="handle_button" @click="save">保存</view></view>
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
	import inputGoodsDetils from '../components/inputGoodsDetils.vue';
	import { navigateTo, navigateBack, setStorage, getStorage } from '../../../libs/uniApi.js';
	export default {
		components: {
			inputGoodsDetils
		},
		data() {
			return {
				goodsDis: {imageList: []},
			}
		},
		created() {
			this.initData();
		},
		methods: {
			initData() {
				if(getStorage('goodsDis')) {
					this.goodsDis = getStorage('goodsDis');
				}
			},
			getProductContent(obj) {
				this.goodsDis = obj;
			},
			save() {
				setStorage('goodsDis', this.goodsDis);
				navigateBack(1);
			}
		}
	}
	
</script>

<style lang="scss" scoped>
	.container {
		padding-top: 20rpx;
	}
	.handle {
		width: 100%;
		height: 126rpx;
		background: #ffffff;
		display: flex;
		align-items: center;
		justify-content: center;
		position: fixed;
		left: 0;
		bottom: 0;
		&_button {
			width: 690rpx;
			height: 86rpx;
			background: #e93323;
			border-radius: 43px;
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 32rpx;
			color: #ffffff;
		}
	}
</style>
