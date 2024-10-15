<template>
	<view v-if="activityData.length > 0" :style="'padding-top:'+mbConfig+'rpx;'" class="skeleton-rect">
		<view class="activity_count" :style="'background:'+bgColor+';margin: 0 '+prConfig+'rpx;border-radius:'+bgStyle+'rpx'">
			<view class="spike-bd">
				<view class="title">
					<image class="title-img" :src="`${domain}/static/images/topic_title.png`"></image>
				</view>
				<navigator :url="merId ? '/pages/store/home/index?id='+merId+'&type=4' : '/pages/activity/topic/index'" class="more-btn" hover-class="none">
					进入专场
					<text class="iconfont icon-jiantou" hover-class="none"></text>
				</navigator>
			</view>
			<view v-if="activityData.length">
				<!-- 单图模式 -->
				<block v-if="activityData.length == 1">
					<view class="topic_swiper" 
						v-for="(item,index) in activityData" 
						:key="index" 
						@click="goTopicDetail(item.info[1].value)">
						<image :src="item.img"></image>
					</view>
				</block>
				<view v-else class="topic_swiper">
					<swiper class="swiper" indicator-dots="true" :autoplay="autoplay" :circular="true" :interval="2500" :duration="duration"
					 indicator-color="rgba(255,255,255,0.6)" :indicator-active-color="pointerColor" :class="[pointerStyle==1 ? 'circular' : pointerStyle==0 ? 'square' : 'noPoint',conStyle?'':'fillet','dot'+txtStyle]">
						<block v-for="(item,index) in activityData" :key="index">
							<swiper-item>
								<view @click="goTopicDetail(item.info[1].value)" class='slide-navigator acea-row row-between-wrapper'>
									<image :src="item.img" class="slide-image"></image>
								</view>
							</swiper-item>
						</block>
					</swiper>
				</view>
			</view>
			
		</view>
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
	import { HTTP_REQUEST_URL } from '@/config/app';
	import {merPath} from "@/utils/index"
	export default {
		name: 'topic',
		props: {
			dataConfig: {
				type: Object,
				default: () => {}
			},
			merId: {
				type: String || Number,
				default: ''
			}
		},
		data() {
			return {
				indicatorDots: false,
				autoplay: true,
				duration: 500,
				activityData: this.dataConfig.menuConfig.list,
				bgColor: this.dataConfig.colorShow.val ? this.dataConfig.bgColor.color[0].item : 'transparent',
				pointerColor: this.dataConfig.pointerColor&&this.dataConfig.pointerColor.color&&this.dataConfig.pointerColor.color[0].item || '#ffffff',
				mbConfig: this.dataConfig.mbConfig.val*2, //页面间距
				prConfig: this.dataConfig.prConfig.val*2, //背景间距
				txtStyle: this.dataConfig.txtStyle.type,
				bgStyle: this.dataConfig.bgStyle.type ? 20 : 0,
				colorShow: this.dataConfig.colorShow.val,
				pointerStyle: this.dataConfig.pointerStyle.type, //指示点样式
				conStyle: this.dataConfig.conStyle.type, //内容样式
				domain: HTTP_REQUEST_URL,
			};
		},
		created() {},
		mounted() {},
		methods: {
			goTopicDetail(url) {
				let urls = url
				urls = merPath(urls, this.merId)
				this.$util.JumpPath(urls);
			}
		}
	}
</script>

<style lang="scss" scoped>
	@import '../style/main.scss';
	.activity_count{
		margin-bottom: 20rpx;
		background: #ffffff;
		border-radius: 16rpx;
		position: relative;
		padding: 24rpx 20rpx 26rpx;
		::v-deep swiper-item,uni-swiper-item,swiper{
			height: 278rpx;
		}
		::v-deep uni-image,::v-deep image,::v-deep .easy-loadimage{
			width: 650rpx;
			height: 278rpx;
			border-radius: 16rpx;
		}
		::v-deep .uni-swiper-wrapper{
			overflow: hidden;
		}
	}
	.topic_swiper{
		width: 650rpx;
		margin: 0 auto;
		line-height: 0;
		&.fillet {
			border-radius: 0;
			::v-deep uni-image,::v-deep image,::v-deep .easy-loadimage{
				border-radius: 0;
			}
		}
		// 圆形指示点
		&.circular {
			::v-deep .uni-swiper-dot {
				width: 10rpx;
				height: 10rpx;
				background: rgba(0, 0, 0, .4);
			}
			::v-deep .uni-swiper-dot-active {
				background: rgba(233, 51, 35, 1);
			}
		}	
		// 方形指示点
		&.square {
			::v-deep .uni-swiper-dot {
				width: 20rpx;
				height: 5rpx;
				border-radius: 3rpx;
				background: rgba(0, 0, 0, .4);
			}	
			::v-deep .uni-swiper-dot-active {
				background: rgba(233, 51, 35, 1);
			}
		}		
		
	}
	.noPoint{
		::v-deep .uni-swiper-dots,::v-deep .uni-swiper-dot {
			display: none!important;
		}
	}
	::v-deep .dot0 .uni-swiper-dots-horizontal{
		left: 10%;
	}
	::v-deep .dot1 .uni-swiper-dots-horizontal{
		left: 50%;
	}
	::v-deep .dot2 .uni-swiper-dots-horizontal{
		left: 90%;
	}
</style>
