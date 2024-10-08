<template>
	<view class="blankPage" :style="'height:'+ heightConfig +'rpx;'" v-if="heightConfig>0">
		<view class="bankCon" :style="'background-color:'+ bgColor +';height:'+ heightConfig +'rpx;'"></view>
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
	export default {
		name: 'blankPage',
		props: {
			dataConfig: {
				type: Object,
				default: () => {}
			}
		},
		data() {
			return {
				bgColor:this.dataConfig.bgColor.color[0].item,
				heightConfig:this.dataConfig.heightConfig.val*2
			};
		},
		created() {},
		methods: {
			
		}
	}
</script>
<style lang="scss">
	.blankPage{
		.bankCon{
			width: 100%;
		}
	} 
</style>
