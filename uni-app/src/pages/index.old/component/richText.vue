<template>
	<view class='richText' :style="'background-color:'+bgColor+';margin:'+ udConfig +'rpx '+ lrConfig +'rpx;'" v-if="description">
		<!-- <view>大法官好地方规划的法规</view> -->
		<!-- #ifndef APP-PLUS -->
		<jyf-parser :domain='domain' :html="description.replace(/<br\/>/ig, '')" ref="article" :tag-style="tagStyle"></jyf-parser>
		<!-- #endif -->
		<!-- #ifdef APP-PLUS -->
		<view class="description" v-html="description"></view>
		<!-- #endif -->
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
	
	import parser from "@/components/jyf-parser/jyf-parser";
	import { HTTP_REQUEST_URL } from '@/config/app';
	export default {
		name: 'richText',
		props: {
			dataConfig: {
				type: Object,
				default: () => {}
			}
		},
		components: {
			"jyf-parser": parser
		},
		data() {
			return {
				tagStyle: {
					img: 'width:100%;'
				},
				bgColor:this.dataConfig.bgColor.color[0].item,
				lrConfig:this.dataConfig.lrConfig.val * 2,
				description:this.dataConfig.richText.val,
				udConfig:this.dataConfig.udConfig.val * 2,
				domain: HTTP_REQUEST_URL,
			};
		},
		created() {},
		methods: {}
	}
</script>

<style lang="scss" scoped>
	.richText{
		padding: 20rpx;
		background-color: #fff;
		margin: 0 20rpx;
	}

</style>
