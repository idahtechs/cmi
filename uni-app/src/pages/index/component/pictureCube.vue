<template>
	<view :style="{background:bgColor,paddingTop:slider*2+'rpx'}" v-if="picList.length" v-show="!isSortType">
		<view class="pictureCube skeleton-rect" :class="{pageOn:bgStyle===1}" :style="{margin:'0 '+prConfig*2+'rpx',background:bgColor}">
			<view class="advertItem01" v-for="(item,index) in picList" :key="index" v-if="style==0" @click="goDetail(item)">
				<image :src="item.image" mode="widthFix"></image>
			</view>
			<view class="advertItem02 acea-row" v-if="style==1">
				<view class="item" v-for="(item,index) in picList" :key="index" @click="goDetail(item)">
					<image :src="item.image" :style="'height:'+ imageH +'rpx;'" mode="widthFix"></image>
				</view>
			</view>
			<view class="advertItem02 advertItem03 acea-row" v-if="style==2">
				<view class="item" v-for="(item,index) in picList" :key="index" @click="goDetail(item)">
					<image :src="item.image" :style="'height:'+ imageH +'rpx;'" mode="widthFix"></image>
				</view>
			</view>
			<view class="advertItem04 acea-row" v-if="style==3">
				<view class="item" @click="goDetail(picList[0])">
					<image :src="picList[0].image"></image>
				</view>
				<view class="item">
					<view class="pic" @click="goDetail(picList[1])">
						<image :src="picList[1].image"></image>
					</view>
					<view class="pic" @click="goDetail(picList[2])">
						<image :src="picList[2].image"></image>
					</view>
				</view>
			</view>
			<view class="advertItem02 advertItem05 acea-row" v-if="style==4">
				<view class="item" v-for="(item,index) in picList" :key="index" @click="goDetail(item)">
					<image :src="item.image" mode="aspectFill" :style="'height:'+ imageH +'rpx;'"></image>
				</view>
			</view>
			<view class="advertItem02 advertItem06 acea-row" v-if="style==5">
				<view class="item" v-for="(item,index) in picList" :key="index" @click="goDetail(item)">
					<image :src="item.image"></image>
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
	
	import {merPath} from "@/utils/index"
	
	export default {
		name: 'pictureCube',
		props: {
			dataConfig: {
				type: Object,
				default: () => {}
			},
			isSortType: {
				type: String | Number,
				default: 0
			},
			merId: {
				type: String || Number,
				default: ''
			}
		},
		data() {
			return {
				picList: this.dataConfig.picStyle.picList,
				style: this.dataConfig.tabConfig.tabVal,
				bgStyle: this.dataConfig.bgStyle.type,
				prConfig: this.dataConfig.prConfig.val,
				slider: this.dataConfig.mbConfig.val,
				bgColor: this.dataConfig.bgColor.color[0].item,
				widthC: '',
				imageH: ''
			};
		},
		mounted() {
			if(this.picList.length){
				let that = this;
				this.$nextTick((e) => {
					if(this.style==1){
						this.widthC = 375
					}else if(this.style==2){
						this.widthC = 250
					}else if(this.style==4){
						this.widthC = 188
					}
					uni.getImageInfo({
						src: that.setDomain(that.picList[0].image),
						success: (res) => {
							if (res && res.height > 0) {
								let height = res.height * ((that.widthC-that.prConfig*2) / res.width)
								that.$set(that, 'imageH', height);	
							} else {
								that.$set(that, 'imageH', (that.widthC-that.prConfig*2)*2);
							}
						},
						fail: function(error) {
							that.$set(that, 'imageH', (that.widthC-that.prConfig*2)*2);
						}
					})
				})
			}
		},
		created() {},
		methods: {
			//替换安全域名
			setDomain: function(url) {
				url = url ? url.toString() : '';
				//本地调试打开,生产请注销
				if (url.indexOf("https://") > -1) return url;
				else return url.replace('http://', 'https://');
			},
			goDetail(url) {
				let urls = url.link
				urls = merPath(urls, this.merId)
				this.$util.JumpPath(urls);
			}
		}
	}
</script>

<style lang="scss" scoped>
	 .pageOn{
			border-radius:24rpx!important;
			.advertItem01{
				image{
						border-radius:20rpx;
				}
			}
			.advertItem02{
				.item{
					&:nth-child(1){
						image{
							border-radius:20rpx 0 0 20rpx
						}
					}
					&:nth-child(2){
						image{
							border-radius:0 20rpx 20rpx 0
						}
					}
				}
			}
			.advertItem03{
				.item{
					&:nth-child(1){
						image{
							border-radius:20rpx 0 0 20rpx
						}
					}
					&:nth-child(2){
						image{
							border-radius:0
						}
					}
					&:nth-child(3){
						image{
							border-radius:0 20rpx 20rpx 0
						}
					}
				}
			}
			.advertItem04{
				.item{
					&:nth-child(1){
						image{
							border-radius:20rpx 0 0 20rpx
						}
					}
					&:nth-child(2){
						.pic{
							&:nth-child(1){
								image{
									border-radius:0 20rpx 0 0
								}
							}
							&:nth-child(2){
								image{
									border-radius:0 0 20rpx 0
								}
							}
						}
					}
				}
			}
			.advertItem05{
				.item{
					&:nth-child(1){
						image{
								border-radius:20rpx 0 0 20rpx
						}
					}
					&:nth-child(2){
						image{
								border-radius:0
						}
					}
					&:nth-child(4){
						image{
								border-radius:0 20rpx 20rpx 0
						}
					}
				}
			}
			.advertItem06{
				.item{
					&:nth-child(1){
						image{
							border-radius:20rpx 0 0 0
						}
					}
					&:nth-child(2){
						image{
							border-radius:0 20rpx 0 0
						}
					}
					&:nth-child(3){
						image{
							border-radius:0 0 0 20rpx
						}
					}
					&:nth-child(4){
						image{
							border-radius:0 0 20rpx 0
						}
					}
				}
			}
	  }
	.pictureCube {
		background-color: #fff;
		.advertItem01 {
			width: 100%;
			height: auto;
			image {
				width: 100%;
				height: 100%;
				display: block;
			}
		}
		.advertItem02{
			width: 100%;
			.item{
				width: 50%;
				height: auto;
				image{
					width: 100%;
					height: 100%;
					display: block;
				}
			}
		}
		.advertItem03{
			.item{
				width: 33.3333%;
			}
		}
		.advertItem04{
			width: 100%;
			.item{
				width: 50%;
				height: 376rpx;
				.pic{
					width: 100%;
					height: 188rpx;
				}
				image{
					width: 100%;
					height: 100%;
					display: block;
				}
			}
		}
		.advertItem05{
			.item{
				width: 25%;
			}
		}
		.advertItem06{
			.item{
				width: 50%;
				height: 188rpx;
			}
		}
	}
</style>
