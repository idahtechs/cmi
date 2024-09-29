<template>
	<view :style="{ 'background-image': `linear-gradient(0deg, rgba(0, 0, 0, 0.9) 0%, rgba(0, 0, 0, 0.5) 100%),url(${store.mer_banner})` }"
	 class="store-home">
		<!-- 搜索 -->
		<!-- #ifdef MP || APP-PLUS -->
		<view :style="{ height: statusBarHeight }"></view>
		<view class="header">
			<view class="head-menu">
				<view class="iconfont icon-xiangzuo" style="color: #fff;"></view>
				<view class="iconfont icon-shouye4" style="color: #fff;"></view>
			</view>
			<view class="search"><text class="iconfont icon-xiazai5"></text>搜索商品</view>
		</view>
		<!-- #endif -->
		<!-- #ifdef H5 -->
		<view class="header">
			<view class="head-menu">
				<view class="iconfont icon-xiangzuo"></view>
				<view class="iconfont icon-shouye4"></view>
			</view>
			<view class="search"><text class="iconfont icon-xiazai5"></text>搜索商品</view>
		</view>
		<!-- #endif -->
		<view v-show="navShow && tabActive === 3" class="nav" :style="viewColor">
			<view class="nav-cont">
				<view :class="{ active: navActive === 0 }" class="item">
					<view class="cont">
						{{ select.selected ? '评分' : '默认' }}
						<text :class="['arrow-icon', 'iconfont', select.show ? 'icon-xiangshang' : 'icon-xiangxia']"></text>
					</view>
				</view>
				<view :class="{ active: navActive === 1 }" class="item">
					<view class="cont">销量</view>
				</view>
				<view :class="{ active: navActive === 2 }" class="item">
					<view class="cont">
						价格
						<image :src="sortPrice ? domain+'/static/diy/up'+keyColor+'.png' : domain+'/static/diy/down'+keyColor+'.png'"></image>
					</view>
				</view>
				<view class="item">
					<view class="cont">
						<text :class="['layout-icon', 'iconfont', isColumn ? 'icon-pailie' : 'icon-tupianpailie']"></text>
					</view>
				</view>
			</view>
			<view v-show="select.show && navShow" class="select">
				<view v-for="item in select.options" :key="item.id" :class="{ active: item.id === select.selected }" class="item">
				 {{ item.name }}
				 <text v-if="item.id === select.selected" class="iconfont icon-gou"></text>
				 </view>
			</view>
		</view>
		<!--diy页面的导航-->
		<view v-show="navShow && tabActive == 1" class="nav" :style="viewColor">
			<view class="nav-cont">
				<view class="item active">
					<view class="cont">精选</view>
				</view>
				<view class="item">
					<view class="cont">新品</view>
				</view>
				<view class="item">
					<view class="cont">活动</view>
				</view>
				<view class="item">
					<view class="cont">领券</view>
				</view>
			</view>
		</view>
		<scroll-view class="main" scroll-y="true" @scroll="scrollHome" catchtouchmove :style="viewColor">
			<!-- 店铺信息 -->
			<view id="store" class="store">
				<image :src="store.mer_avatar"></image>
				<view class="text">
					<view class="text-view">
						<text v-if="store.type_name" class="font-bg-red">{{store.type_name}}</text>
						<text v-else-if="store.is_trader" class="font-bg-red">自营</text>
						<text class="name">{{ store.mer_name }}</text>
						<text class="iconfont icon-xiangyou"></text>
					</view>
					<view class="score">
						<view class="star">
							<view
							:style="{width: `${score.star.toFixed(2)}%`, backgroundImage: `url(${domain}/static/diy/score1${keyColor}.png)`}"></view>
						</view>
						<view>{{ score.number.toFixed(1) }}</view>
					</view>
				</view>
				<view v-if="store.services_type == 0" class="kefu">
					<text class="iconfont icon-kefu3"></text>
				</view>
				<view v-else-if="store.services_type == 1" class="kefu">
					<text class="iconfont icon-kefu3"></text>
				</view>
				<view v-else class="kefu">
					<text class="iconfont icon-kefu3"></text>
				</view>
				<button hover-class="none" :class="store.care ? 'care' : ''" v-if="!isLogin">
					<text v-show="!store.care" class="iconfont icon-guanzhu"></text>
					{{ store.care ? '已关注' : '关注' }}
				</button>
				<button v-else hover-class="none" :class="store.care ? 'care' : ''">
					<text v-show="!store.care" class="iconfont icon-guanzhu"></text>
					{{ store.care ? '已关注' : '关注' }}
				</button>
			</view>
			<view v-show="!navShow && tabActive === 3" class="nav">
				<view class="nav-cont">
					<view class="item active">
						<view class="cont">
							{{ select.selected ? '评分' : '默认' }}
							<text :class="['arrow-icon', 'iconfont', select.show ? 'icon-xiangshang' : 'icon-xiangxia']"></text>
						</view>
					</view>
					<view class="item">
						<view class="cont">
							销量
						</view>
					</view>
					<view class="item">
						<view class="cont">
							价格
							<image v-if="navActive === 2 && where.order == 'price_asc'" :src="domain+'/static/diy/up'+keyColor+'.png'"></image>
							<image v-if="navActive === 2 && where.order == 'price_desc'" :src="domain+'/static/diy/down'+keyColor+'.png'"></image>
						</view>
					</view>
					<view class="item">
						<view class="cont">
							<text :class="['layout-icon', 'iconfont', isColumn ? 'icon-pailie' : 'icon-tupianpailie']"></text>
						</view>
					</view>
				</view>
				<view v-show="select.show && !navShow" class="select">
					<view v-for="item in select.options" :key="item.id" :class="{ active: item.id === select.selected }" class="item"
					 >{{ item.name }}
					 <text v-if="item.id === select.selected" class="iconfont icon-gou"></text>
					 </view>
				</view>
			</view>
			<!--diy导航-->
			<view v-show="!navShow && tabActive === 1" class="nav">
				<view class="nav-cont">
					<view class="item active">
						<view class="cont">精选</view>
					</view>
					<view class="item">
						<view class="cont">新品</view>
					</view>
					<view class="item">
						<view class="cont">活动</view>
					</view>
					<view class="item">
						<view class="cont">领券</view>
					</view>
				</view>
				<view v-show="select.show && !navShow" class="select">
					<view v-for="item in select.options" :key="item.id" :class="{ active: item.id === select.selected }" class="item">
						{{ item.name }}
					 <text v-if="item.id === select.selected" class="iconfont icon-gou"></text>
					</view>
				</view>
			</view>
			<view class="tab-cont" :style="viewColor">
				<!-- diy组件 -->
				<view>
					<!-- #ifdef H5 -->
					<view v-for="(item, index) in styleConfig" :key="index">
						<block v-if="item.name != 'headerSerch' && item.name != 'tabNav' && item.name != 'pageFoot'">
							<component
								:is="item.name"
								:index="index"
								:dataConfig="item"
								:merId="id"
								@detail="goGoodsDetail"
							></component>
						</block>
						</block>
					</view>
					<!-- #endif -->
					<!-- #ifdef MP  || APP-PLUS-->
					<block v-for="(item, index) in styleConfig" :key="index" >
						<view>
							<activeParty v-if="item.name == 'activeParty'" :merId="id" :dataConfig="item"></activeParty>
							<articleList v-if="item.name == 'articleList'" :merId="id" :dataConfig="item"></articleList>
							<bargain v-if="item.name == 'bargain'" :merId="id" :dataConfig="item"></bargain>
							<blankPage v-if="item.name == 'blankPage'" :merId="id" :dataConfig="item"></blankPage>
							<combination v-if="item.name == 'combination'" :merId="id" :dataConfig="item"></combination>
							<topic v-if="item.name == 'topic'" :merId="id" :dataConfig="item"></topic>
							<coupon v-if="item.name == 'coupon'" :merId="id" :dataConfig="item"></coupon>	
							<goodList v-if="item.name == 'goodList'" :merId="id" :dataConfig="item" @detail="goGoodsDetail"></goodList>
							<guide v-if="item.name == 'guide'" :merId="id" :dataConfig="item"></guide>
							<liveBroadcast v-if="item.name == 'liveBroadcast'" :merId="id" :dataConfig="item"></liveBroadcast>
							<menus v-if="item.name == 'menus'" :merId="id" :dataConfig="item"></menus>
							<presellList v-if="item.name == 'presellList'" :merId="id" :dataConfig="item"></presellList>
							<richText v-if="item.name == 'richText'" :merId="id" :dataConfig="item"></richText>
							<seckill v-if="item.name == 'seckill'" :merId="id" :dataConfig="item"></seckill>
							<swiperBg v-if="item.name == 'swiperBg'" :merId="id" :dataConfig="item"></swiperBg>
							<pictureCube v-if="item.name == 'pictureCube'" :merId="id" :dataConfig="item"></pictureCube>
							<swipers v-if="item.name == 'swipers'" :merId="id" :dataConfig="item"></swipers>
							<titles v-if="item.name == 'titles'" :merId="id" :dataConfig="item"></titles>
						</view>
					</block>
					<!-- #endif -->
				</view>
			</view>
		</scroll-view>
		<view class="footer" :style="viewColor">
			<view v-for="(item, index) in tabs" :key="index" :class="{ active: tabActive === item.value }" class="item">
				<view :class="['iconfont', item.icon]"></view>
				<view>{{ item.name }}</view>
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
	import request from "@/utils/request.js";
	import WaterfallsFlow from '@/components/WaterfallsFlow/WaterfallsFlow.vue'
	import easyLoadimage from '@/components/easy-loadimage/easy-loadimage.vue'
	import {getStoreDetail,	getStoreGoods, getStoreCategory, storeServiceList, hasServiceApi} from '@/api/store.js';
	import { getDiy } from '@/api/api.js';
	import { getUserInfo } from '@/api/user.js';
	import { configMap } from "@/utils";
	import { mapGetters } from "vuex";
	import { goShopDetail } from '@/libs/order.js';
	import emptyPage from '@/components/emptyPage.vue'
	import { HTTP_REQUEST_URL } from '@/config/app';
	import mConfig from '../../store/component/index.js';
	// #ifdef MP || APP-PLUS
	import activeParty from '../../index/component/activeParty';
	import coupon from '../../index/component/coupon';
	import bargain from '../../index/component/bargain';
	import blankPage from '../../index/component/blankPage';
	import combination from '../../index/component/combination';
	import goodList from '../../index/component/goodList';
	import guide from '../../index/component/guide';
	import liveBroadcast from '../../index/component/liveBroadcast';
	import menus from '../../index/component/menus';
	import topic from '../../index/component/topic';
	import presellList from '../../index/component/presellList';
	import richText from '../../index/component/richText';
	import seckill from '../../index/component/seckill';
	import swiperBg from '../../index/component/swiperBg';
	import pictureCube from '../../index/component/pictureCube'
	import titles from '../../index/component/titles';
	// #endif
	const app = getApp();	
	export default {
		components: {
			...mConfig,
			emptyPage,
			WaterfallsFlow,
			easyLoadimage,
			// #ifdef MP || APP-PLUS
			activeParty,
			bargain,
			coupon,
			blankPage,
			combination,
			goodList,
			liveBroadcast,
			menus,
			topic,
			presellList,
			richText,
			seckill,
			swiperBg,
			pictureCube,
			titles
			// #endif
		},
		data() {
			return {
				domain: HTTP_REQUEST_URL,
				// #ifdef MP
				menuButtonInfo: uni.getMenuButtonBoundingClientRect(),
				// #endif
				id: 0, // 商铺id
				did: 0, //diy模板id
				store: {}, // 商铺详情
				goods: [], // 商铺商品
				category: [], // 商铺分类
				coupon: [], // 优惠券
				styleConfig: [],
				isColumn: true, // 商品列表排列方式
				navShow: false,
				navActive: 0,
				diyActive: 0,
				tabActive: 1, // 底部切换
				keyword: '',
				order: '',
				sortPrice: true, // 价格排序
				goodsLoading: false,
				categoryLoading: false,
				loadTitle: '加载更多',
				loadend: false,
				where: {
					order: '',
					keyword: '',
					page: 1,
					limit: 30
				},
				// 下拉菜单
				select: {
					show: false,
					selected: 0,
					options: [{
							id: 0,
							name: '默认'
						},
						{
							id: 1,
							name: '评分'
						}
					]
				},
				// 底部菜单
				tabs1: [{
						icon: 'icon-yizhan_o',
						name: '首页',
						value: 1,
					},
					{
						icon: 'icon-gouwu_o',
						name: '商品',
						value: 3,
					},
					{
						icon: 'icon-baoguo_huanbaohe_o',
						name: '生活服务',
						value: 5,
					},
					{
						icon: 'icon-yingyongAPP_o',
						name: '分类',
						value: 2,
					},
					{
						icon: 'icon-liwu_o',
						name: '专场',
						value: 4,
					}
				],
				// 底部菜单
				tabs2: [{
						icon: 'icon-yizhan_o',
						name: '首页',
						value: 1,
					},
					{
						icon: 'icon-gouwu_o',
						name: '商品',
						value: 3,
					},
					{
						icon: 'icon-yingyongAPP_o',
						name: '分类',
						value: 2,
					},
					{
						icon: 'icon-zhuanti',
						name: '专场',
						value: 4,
					}
				],
				tabs: [],
				storeTop: 0,
				navHeight: 0,
				service_open: false,
				type: 0,
				statusBarHeight: 0,
			}
		},
		computed: {
			score: function() {
				let store = this.store,
					score = {
						star: 0,
						number: 0
					};
				if ('postage_score' in store) {
					score.number = (parseFloat(store.postage_score) + parseFloat(store.product_score) + parseFloat(store.service_score)) /
						3;
					score.star = score.number / 5 * 100;
				}
				return score;
			},
			...configMap({
				margin_ico_switch: 0,
				margin_ico: '',
			}, mapGetters(['isLogin','uid','viewColor','keyColor'])),
		},
		watch: {
	
		},
		onLoad: function(options) {
			this.type = parseInt(options.type) || 1
			this.id = options.mer_id || options.id || 0
			this.did = options.diy_id || 0
			this.order = options.order
			this.getStore();
			this.diyData();
			this.statusBarHeight = uni.getSystemInfoSync().statusBarHeight + 'px';
		},
		onShow() {
			
		},
		onReady: function() {

		},
		mounted: function() {
			const query = uni.createSelectorQuery().in(this);
			query.select('#store').boundingClientRect(data => {
				this.storeHeight = data.height;
				this.storeTop = data.top;
			}).exec();
		},
		methods: {
			// 获取diy模板数据
			diyData() {
				let that = this;
				getDiy({id: that.id, did: that.did, version: '221'}).then(res => {
					let data = res.data.data;
					that.styleConfig = that.objToArr(data.value);
					uni.setNavigationBarTitle({
						title: data.title
					});
				}).catch(err => {
					uni.showToast({
						title: err,
						icon: 'none'
					});		
				});
			},
			// 对象转数组
			objToArr(data) {
				let obj = Object.keys(data).sort();
				let m = obj.map(key => data[key]);
				return m;
			},
			// 获取商品详情
			getStore: function() {
				getStoreDetail(this.id).then(res => {
					this.store = res.data;
					if(this.id == 0){
						this.tabs = this.tabs1
					}else{
						if((res.data.delivery_way.length == 1 && res.data.delivery_way[0] == '1') || res.data.delivery_way.length == 2){
							this.tabs = this.tabs1
							this.service_open = true
						}else{
							this.tabs = this.tabs2
							this.service_open = false
						}	
					}	
				}).catch(err => {
					this.loading = false;
					uni.showToast({
						title: err,
						icon: 'none'
					})
					
				})
			},
			// 获取商铺商品
			getGoods: function() {
				let that = this;
				if (that.loadend) return;
				if (that.loading) return;
				if (that.goodsLoading) return;
				that.goodsLoading = true;
				that.loadTitle = '';
				getStoreGoods(that.id, that.where).then(res => {
					that.goodsLoading = false;
					let list = res.data.list;
					let goodsList = that.$util.SplitArray(list, that.goods);
					let loadend = list.length < that.where.limit;
					that.loadend = loadend;
					that.loading = false;
					that.loadTitle = loadend ? '已全部加载' : '加载更多';
					that.$set(that, 'goods', goodsList);
					that.$set(that.where, 'page', that.where.page + 1);
				}).catch(err => {
					that.loading = false;
					that.goodsLoading = false;
					uni.showToast({
						title: err,
						icon: 'none'
					})
					setTimeout(function() {
						uni.navigateBack()
					}, 1000);
				});
			},
			// 去商品详情页
			goGoodsDetail(item) {
				
			},
			// 商铺首页滚动 navbar 吸顶
			scrollHome: function(e) {
				uni.$emit('scroll');
				this.navShow = e.detail.scrollTop >= 50;
			},
			
		},
	}
</script>

<style lang="scss" scoped>
	.tab-cont{
		background: #f5f5f5;
		min-height: 500rpx;
		pointer-events: none;
	}
	/deep/.care {
		background-image: none !important;
		border: 1px solid #fff;
		background-color: transparent;
	}
	.store-home {
		position: fixed;
		top: 0;
		right: 0;
		bottom: 0;
		left: 0;
		display: flex;
		flex-direction: column;
		padding-bottom: 100rpx;
		padding-bottom: calc(100rpx+ constant(safe-area-inset-bottom)); ///兼容 IOS<11.2/
		padding-bottom: calc(100rpx + env(safe-area-inset-bottom)); ///兼容 IOS>11.2/
		background: left top/750rpx 390rpx no-repeat fixed;
		overflow: hidden; 
	}
	.header {
		position: relative;
		z-index: 6;
		display: flex;
		align-items: center;
		padding-right: 34rpx;
		height: 43px;
		padding-left: 33rpx;
		.head-menu {
			display: -webkit-box;
			display: -webkit-flex;
			display: flex;
			-webkit-box-align: center;
			-webkit-align-items: center;
			align-items: center;
			height: 27px;
			width: 70px;
			background: rgba(0, 0, 0, 0.25);
			border-radius: 13px;
			.icon-xiangzuo {
				font-size: 32rpx;
				color: #FFFFFF;
			}
			.iconfont {
				-webkit-box-flex: 1;
				-webkit-flex: 1;
				flex: 1;
				text-align: center;
				color: #fff;
				box-sizing: border-box;
				&.icon-xiangzuo {
					border-right: 1px solid rgba(255,255,255,.3);
				}
			}
		}
		.search {
			flex: 1;
			display: flex;
			align-items: center;
			min-width: 0;
			height: 58rpx;
			border-radius: 29rpx;
			margin-left: 32rpx;
			background-color: #FFFFFF;
			font-weight: 500;
			font-size: 26rpx;
			color: #999999;
			/* #ifdef MP */
			margin-right: 200rpx;
			/* #endif */
			.iconfont {
				margin-right: 13rpx;
				margin-left: 30rpx;
				font-size: 24rpx;
			}
		}
	}
	.main_count{
		background-color: #ffffff;
		padding: 30rpx 20rpx;
		.list{
			width: 710rpx;
			height: 280rpx;
			margin-bottom: 30rpx;
			position: relative;
			.picture{
				width: 710rpx;
				height: 280rpx;
				border-radius: 16rpx;
			}
		}
	}
	.main {
		flex: 1;
		min-height: 0rpx;
		box-sizing: border-box;
		padding: 0;
	}
	.store {
		position: relative;
		z-index: 6;
		display: flex;
		align-items: center;
		padding-right: 20rpx;
		padding-left: 20rpx;
		padding-top: 20rpx;
		padding-bottom: 22rpx;
		image {
			width: 74rpx;
			height: 74rpx;
			border-radius: 6rpx;
		}
		.store-margin{
			width: 26rpx;
			height: 26rpx;
			margin-left: 10rpx;
		}
		.kefu{
			color: #ffffff;
			margin-right: 26rpx;
			cursor: pointer;
			.icon-kefu3{
				font-size: 34rpx;
			}
		}
		.text {
			flex: 1;
			min-width: 0;
			margin-right: 20rpx;
			margin-left: 20rpx;
			.text-view {
				display: inline-flex;
				align-items: center;
				max-width: 100%;
				.name {
					flex: 1;
					min-width: 0;
					overflow: hidden;
					white-space: nowrap;
					text-overflow: ellipsis;
					font-weight: bold;
					font-size: 30rpx;
					line-height: 1;
					color: #FFFFFF;
				}
				.iconfont {
					margin-left: 10rpx;
					font-size: 17rpx;
					color: #FFFFFF;
				}
			}
			.score {
				display: flex;
				align-items: center;
				margin-top: 17rpx;
				font-weight: 500;
				font-size: 24rpx;
				line-height: 1;
				color: #FFFFFF;
				.star {
					position: relative;
					width: 111rpx;
					height: 19rpx;
					margin-right: 10rpx;
					background: url(../../columnGoods/images/star.png) left top/100% 100% no-repeat;
					overflow: hidden;
					view {
						position: absolute;
						top: 0;
						left: 0;
						width: 100%;
						height: 100%;
						background: url(../../columnGoods/images/star_active.png) left top/111rpx 19rpx no-repeat;
						&.star_purple{
							background: url(../../columnGoods/images/star_active_purple.png) left top/111rpx 19rpx no-repeat;
						}
					}
				}
			}
		}
		button {
			display: flex;
			justify-content: center;
			align-items: center;
			width: 113rpx;
			height: 48rpx;
			border-radius: 24rpx;
			background-image: linear-gradient(-90deg, var(--view-bntColor21) 0%, var(--view-bntColor22) 100%);
			font-weight: 500;
			font-size: 22rpx;
			color: #FFFFFF;
			.iconfont {
				margin-right: 6rpx;
				font-size: 22rpx;
			}
			&.gary {
				background-color: #999;
			}
		}
	}
	.font-bg-red {
		background-color: var(--view-theme);
		border: 1px solid var(--view-theme);
	}
	.nav.fixed {
		position: fixed;
		left: 0;
		width: 100%;
		.nav-cont {
			position: absolute;
			width: 100%;
		}
	}
	.nav {
		position: relative;
		.nav-cont {
			display: flex;
			align-items: center;
			height: 84rpx;
			.item {
				flex: 1;
				display: flex;
				justify-content: center;
				align-items: center;
				min-width: 0;
				.cont {
					display: flex;
					justify-content: center;
					align-items: center;
					width: 116rpx;
					height: 44rpx;
					border-radius: 22rpx;
					font-weight: 500;
					font-size: 24rpx;
					color: #FFFFFF;
					.arrow-icon {
						margin-left: 10rpx;
						font-size: 18rpx;
					}
					.layout-icon {
						font-size: 32rpx;
					}
					.icon-pailie {
						font-size: 32rpx;
					}
					image {
						width: 15rpx;
						height: 21rpx;
						margin-left: 7rpx;
					}
				}
			}
			.active {
				.cont {
					background-color: #FFFFFF;
					font-weight: bold;
					color: var(--view-theme);
				}
			}
		}
		.select {
			position: absolute;
			top: 100%;
			left: 0;
			z-index: 2;
			width: 100%;
			padding-right: 40rpx;
			padding-bottom: 28rpx;
			padding-left: 74rpx;
			border-bottom-right-radius: 24rpx;
			border-bottom-left-radius: 24rpx;
			background-color: #FFFFFF;
			box-shadow: 0px 2px 10px 0px rgba(0, 0, 0, 0.06);
			.item {
				margin-top: 28rpx;
				font-size: 24rpx;
				color: #454545;
			}
			.active {
				color: var(--view-theme);
				.iconfont{
					color: var(--view-theme);
					float: right;
					font-size: 20rpx;
				}
			}
		}
	}
	.goods {
		display: flex;
		flex-wrap: wrap;
		justify-content: space-between;
		padding-top: 20rpx;
		padding-right: 20rpx;
		padding-left: 20rpx;
		background-color: #F5F5F5;
		width: 750rpx;
		.item {
			width: 345rpx;
			border-radius: 16rpx;
			margin-bottom: 20rpx;
			background-color: #FFFFFF;
			overflow: hidden;
			.sell_out {
				display: flex;
				width: 110rpx;
				height: 110rpx;
				align-items: center;
				justify-content: center;
				border-radius: 100%;
				background: rgba(0,0,0,.6);
				color: #fff;
				font-size: 24rpx;
				position: absolute;
				top: 50%;
				left: 50%;
				margin: -55rpx 0 0 -55rpx;
				&::before{
					content: "";
					display: block;
					width: 100rpx;
					height: 100rpx;
					border-radius: 100%;
					border: 1px dashed #fff;
					position: absolute;
					top: 5rpx;
					left: 5rpx;
				}
			}
			.image,.easy-loadimage,image,uni-image {
				width: 345rpx;
				height: 345rpx;
				image {
					display: block;
					width: 100%;
					height: 100%;
				}
			}
			.text {
				padding: 20rpx 20rpx 25rpx;
				.name {
					font-weight: 500;
					font-size: 30rpx;
					line-height: 1;
					color: #222222;
					display: flex;
					align-items: center;
					.name_text{
						display: inline-block;
						max-width: 400rpx;
					}
				}
				.money-wrap {
					display: flex;
					align-items: center;
					margin-top: 43rpx;
					.money {
						font-weight: bold;
						font-size: 26rpx;
						color: var(--view-priceColor);
						text {
							font-size: 34rpx;
							line-height: 1;
						}
					}	
				}
				.score {
					margin-top: 20rpx;
					font-weight: 500;
					font-size: 20rpx;
					line-height: 1;
					color: #737373;
				}
			}
			.item_tags{
				margin-top: 8rpx;
			}
			.item_tags .tags_item {
				display: inline-block;
				font-size: 20rpx;
				text-align: center;
				border-radius: 5rpx;
				padding: 0 4rpx;
				line-height: 28rpx;
				margin-right: 8rpx;
			}
			.item_tags .tags_item.ticket{
				border: 1px solid var(--view-theme);
				color: var(--view-theme);
			}
			.item_tags .tags_item.delivery{
				color: #FF9000;
				border: 1px solid #FF9000;
			}
			.foot {
				display: flex;
				justify-content: center;
				align-items: center;
				height: 52rpx;
				background-image: linear-gradient(-90deg, var(--view-bntColor21) 0%, var(--view-bntColor22) 100%);
				font-weight: 500;
				font-size: 24rpx;
				color: #FFFFFF;
				.iconfont {
					margin-right: 10rpx;
					font-size: 22rpx;
					line-height: 1;
				}
			}
		}
	}

	.column {
		padding: 0;
		background-color: #FFFFFF;
		.item {
			position: relative;
			display: flex;
			width: 100%;
			padding: 30rpx 20rpx;
			border-radius: 0;
			margin-bottom: 0;
			&::before {
				content: " ";
				position: absolute;
				top: 0;
				right: 20rpx;
				left: 250rpx;
				border-top: 1px solid #F5F5F5;
			}
			/deep/.image,/deep/.easy-loadimage,/deep/image,/deep/uni-image {
				width: 260rpx;
				height: 260rpx;
				border-radius: 16rpx;
				overflow: hidden;
				position: relative;
				
			}
			.border-picture {
				position: absolute;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				background: center/cover no-repeat;
			}
			.text {
				position: relative;
				width: 450rpx;
				min-width: 0;
				padding-top: 0;
				padding-right: 0;
				padding-bottom: 0;
				.name {
					color: #282828;
					width: 100%;
					display: flex;
				}
				.item_bot{
					width: 100%;
				}
				.money-wrap {
					display: flex;
					align-items: center;
					margin-top: 52rpx;
					.ticket {
						height: 28rpx;
						padding-right: 12rpx;
						padding-left: 12rpx;
						border: none;
						border-radius: 0;
						margin-top: 17rpx;
						margin-left: 0;
						background: url(../../../static/images/yh.png) top left/100% 100% no-repeat;
						line-height: 28rpx;
					}
				}
			}
			.foot {
				position: absolute;
				right: 20rpx;
				bottom: 30rpx;
				height: 44rpx;
				padding-right: 17rpx;
				padding-left: 17rpx;
				border-radius: 22rpx;
				font-size: 22rpx;
				color: #F5F5F5;
			}
		}
	}
	.category {
		padding-top: 34rpx;
		padding-right: 20rpx;
		padding-left: 20rpx;
		.section {
			border-radius: 10rpx;
			margin-bottom: 20rpx;
			background-color: #FFFFFF;
			.head {
				position: relative;
				display: flex;
				align-items: center;
				height: 90rpx;
				padding-right: 20rpx;
				padding-left: 36rpx;
				font-weight: bold;
				color: #282828;
				&::before {
					content: " ";
					position: absolute;
					top: 50%;
					left: 20rpx;
					width: 6rpx;
					height: 24rpx;
					background-color: var(--view-theme);
					transform: translateY(-50%);
				}
				.title {
					flex: 1;
					min-width: 0;
					overflow: hidden;
					white-space: nowrap;
					text-overflow: ellipsis;
					font-size: 30rpx;
				}
				.iconfont {
					font-size: 22rpx;
					line-height: 1;
				}
			}
			.body {
				display: flex;
				flex-wrap: wrap;
				justify-content: space-between;
				align-items: center;
				padding: 9rpx 36rpx 14rpx;
				.item {
					width: 314rpx;
					height: 84rpx;
					padding-right: 30rpx;
					padding-left: 30rpx;
					border-radius: 10rpx;
					background-color: #F5F5F5;
					margin-bottom: 10rpx;
					overflow: hidden;
					white-space: nowrap;
					text-overflow: ellipsis;
					font-weight: 500;
					font-size: 26rpx;
					line-height: 84rpx;
					color: #282828;
				}
			}
		}
	}
	.empty-box{
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
		margin-top: 0;
		padding-top: 200rpx;
		image{
			width: 414rpx;
			height: 240rpx;
		}
		.txt{
			font-size: 26rpx;
			color: #999;
		}
	}
	.footer {
		position: fixed;
		bottom: 0;
		left: 0;
		z-index: 5;
		display: flex;
		width: 100%;
		height: 100rpx;
		height: calc(100rpx+ constant(safe-area-inset-bottom)); ///兼容 IOS<11.2/
		height: calc(100rpx + env(safe-area-inset-bottom)); ///兼容 IOS>11.2/
		background-color: #FFFFFF;
		opacity: 0.96;
		.item {
			flex: 1;
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
			font-weight: 500;
			font-size: 20rpx;
			color: #282828;
			.iconfont {
				font-size: 43rpx;
			}
			.icon-zhuanti{
				font-size: 38rpx;
			}
		}
		.active {
			color: var(--view-theme);
		}
	}
	.p-b-75{
		padding-bottom: 150rpx;
	}
	.vip-money {
		color: #282828;
		font-size: 22rpx;
		margin-left: 6rpx;
		font-weight: bold;
	}
	.vipImg {
		width: 65rpx;
		height: 28rpx;
		margin-left: 4rpx;
		image {
			width: 100%!important;
			height: 100%!important;
			display: block;
		}
	}
</style>
