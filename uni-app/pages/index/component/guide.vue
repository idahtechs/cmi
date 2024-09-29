<template>
	<view class="lines" :style="'padding:14rpx '+ lrEdge +'rpx;padding-top:'+ mbConfig +'rpx;'">
		<view class="item" :style="'border-bottom-width:'+ heightConfig +'rpx;border-bottom-color:'+ lineColor +';border-bottom-style:'+ lineStyle +';'"></view>
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
		name: 'guide',
		props: {
			dataConfig: {
				type: Object,
				default: () => {}
			}
		},
		data() {
			return {
				heightConfig:this.dataConfig.heightConfig.val*2 || 2,
				lineColor:this.dataConfig.lineColor.color[0].item,
				lineStyle:this.dataConfig.lineStyle.type == 0 ? 'dashed' : this.dataConfig.lineStyle.type == 1 ? 'solid' : 'dotted',
				lrEdge:this.dataConfig.lrEdge.val*2,
				mbConfig:this.dataConfig.mbConfig.val*2
			};
		},
		created() {},
		methods: {
			
		}
	}
</script>

<style lang="scss" scoped>
	.lines{
		padding: 0 20rpx;
		margin-top: 20rpx;
		.item{
			width: 100%;
			box-sizing: border-box;
			border-bottom-color: red;
			border-bottom-style: dotted;
		}
	}
</style>
