<template>
	<view :style="'padding-top:' +mbConfig+'rpx;'" v-if="menus.length">
		<view class='nav acea-row acea-row' :style="'background:'+bgColor[0].item+';margin: 0'+prConfig+'rpx 0;border-radius:'+bgStyle+'rpx;'">
			<block v-if="rowStyle == 0">
				<scroll-view scroll-x="true" style="white-space: nowrap; display: flex" show-scrollbar="false">
					<view v-for="(item,index) in menus" :key="index" class='item' :style="'color:'+titleColor" @click="menusTap(item.info[1].value)">
						<view class='pictrue skeleton-rect'>
							<image :src='item.img' :style="'border-radius:'+menuStyle"></image>
						</view>
						<view class="menu-txt" :style="'color:'+titleColor">{{item.info[0].value}}</view>
					</view>
				</scroll-view>
			</block>		
			<block v-else v-for="(item,index) in menus" :key="index">
				<view class='item' :style="'color:'+titleColor+';width:'+number" @click="menusTap(item.info[1].value)">
					<view class='pictrue skeleton-rect'>
						<image :src='item.img' :style="'border-radius:'+menuStyle"></image>
					</view>
					<view class="menu-txt" :style="'color:'+titleColor">{{item.info[0].value}}</view>
				</view>
			</block>
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
		name: 'menus',
		props: {
			dataConfig: {
				type: Object,
				default: () => {}
			},
			merId:{}
		},
		data() {
			return {
				menus: this.dataConfig.menuConfig.list,
				bgColor: this.dataConfig.bgColor.color,
				menuStyle: this.dataConfig.menuStyle.type ? '50%' : 0,
				rowStyle: this.dataConfig.tabConfig.tabVal,  //0单行1多行
				bgStyle: this.dataConfig.bgStyle.type ? '16' : '0',
				titleColor: this.dataConfig.titleColor.color[0].item,
				mbConfig: this.dataConfig.mbConfig.val*2,
				prConfig: this.dataConfig.prConfig.val*2,
				rowNum: this.dataConfig.rowsNum.type,//0两行，1三行，2四行
				number: this.dataConfig.number.type == 0 ? '33.33%' : this.dataConfig.number.type == 1 ? '25%' : '20%',  //三个四个五个 
			};
		},
		created() {},
		mounted() {},
		methods: {
			menusTap(url) {		
				let data = this.$util.stringIntercept(url, 1, '\?');
				data = this.$util.stringIntercept(data, 1, '\=');
				uni.setStorageSync('storeIndex', data);
				url = merPath(url, this.merId)	
				this.$util.JumpPath(url);
			}
		}
	}
</script>

<style lang="scss" scoped>
	.nav {
		padding: 30rpx 0 0;
		.item {
			width: 20%;
			text-align: center;
			font-size: 24rpx;
			display: inline-block;
			margin-bottom: 20rpx;
			.pictrue,::v-deep .pictrue image,::v-deep .pictrue .easy-loadimage,::v-deep .pictrue uni-image {
				width: 82rpx;
				height: 82rpx;
				margin: 0 auto;
			}
			.menu-txt {
				font-size: 24rpx;
				color: #454545;
				margin-top: 15rpx;
			}
			&.four {
				width: 25%;
				.pictrue {
					width: 90rpx;
					height: 90rpx;
				}
			}
		}
	}
</style>
