<template>
	<view>
		<view :class="bgTabVal==2?'fullsize noRepeat':bgTabVal==1?'repeat ysize':'noRepeat ysize'"
			:style="'background-color:'+bgColor+';background-image: url('+bgPic+');min-height:'+windowHeight+'px;'" id="home">
			<skeleton :show="showSkeleton" :isNodes="isNodes" ref="skeleton" loading="chiaroscuro" selector="skeleton" bgcolor="transparent"></skeleton>
			<view :style="{visibility: showSkeleton ? 'hidden' : 'visible'}">
				<view class="page-index skeleton" :class="{ bgf: navIndex > 0 }">
					<block>
						<!-- #ifdef H5 -->
						<homeComb v-if="smallPage" class="home-comb" :isScale="isScale" :isMenu="isMenu" :dataConfig="homeCombData" :userInfo="userInfo" :isFixed="isFixed" :isScrolled="isScrolled" @changeDiy="diyData"></homeComb>
						<view v-for="(item, index) in styleConfig" :key="index">
							<block v-if="item.name != 'headerSerch' && item.name != 'tabNav' && item.name != 'shopList' && item.name != 'homeComb' && item.name != 'hotRanking' && item.name != 'pageFoot'">
								<component
									:is="item.name"
									:index="index"
									:isFixed="isFixed"
									:dataConfig="item"
									:tempArr="tempArr"
									:userInfo="userInfo"
									:tabTitle="navTop"
									:navIndex="navIndex"
									@changeTab="changeTab"
									@detail="goDetail"
									v-show="navIndex == 0"
								></component>
							</block>
							<block v-if="item.name == 'headerSerch'">
								<headerSerch
									:dataConfig="item"
									:tempArr="tempArr"
									:userInfo="userInfo"
									:tabTitle="navTop"
									@changeTab="changeTab"
									@detail="goDetail"
								></headerSerch>
							</block>
							<block v-if="item.name == 'shopList' && hide_mer_status != 1 && navIndex == 0">
								<shopList :dataConfig="item"></shopList>
							</block>
							<block v-if="item.name == 'tabNav'">
								<tabNav
									:isFixed="isFixed"
									:dataConfig="item"
									:tempArr="tempArr"
									:userInfo="userInfo"
									:tabTitle="navTop"
									@changeTab="changeTab"
									@detail="goDetail"
								></tabNav>
							</block>
							<block v-if="item.name == 'homeComb' && !smallPage">
								<homeComb :isMenu="isMenu" :dataConfig="item" :isFixed="isFixed" :isScrolled="isScrolled" :userInfo="userInfo" @changeDiy="diyData"></homeComb>
							</block>
							<block v-if="item.name == 'hotRanking' && hot_ranking_switch != 0 && navIndex == 0">
								<hotRanking :dataConfig="item"></hotRanking>
							</block>
						</view>				
						<!-- #endif -->
						<!-- #ifdef MP || APP-PLUS -->
						<homeComb class="home-comb" v-if="smallPage" :isScale="isScale" :isMenu="isMenu" :dataConfig="homeCombData" :isFixed="isFixed" :isScrolled="isScrolled" :userInfo="userInfo" @changeDiy="diyData"	@bindHeight="bindHeighta"></homeComb>
						<block v-for="(item, index) in styleConfig" :key="index" >
							<view v-show="navIndex == 0">
								<homeComb v-if="item.name == 'homeComb' && !smallPage" :isMenu="isMenu" :dataConfig="item" :isFixed="isFixed" :isScrolled="isScrolled" :userInfo="userInfo" @changeDiy="diyData" @bindHeight="bindHeighta"></homeComb>
								<activeParty v-if="item.name == 'activeParty'" :dataConfig="item"></activeParty>
								<bargain v-if="item.name == 'bargain'" :dataConfig="item"></bargain>
								<blankPage v-if="item.name == 'blankPage'" :dataConfig="item"></blankPage>
								<combination v-if="item.name == 'combination'" :dataConfig="item"></combination>
								<topic v-if="item.name == 'topic'" :dataConfig="item"></topic>
								<coupon v-if="item.name == 'coupon'" :dataConfig="item"></coupon>
								<customerService v-if="item.name == 'customerService'" :dataConfig="item"></customerService>
								<goodList v-if="item.name == 'goodList'" :dataConfig="item" @detail="goDetail"></goodList>
								<guide v-if="item.name == 'guide'" :dataConfig="item"></guide>
								<liveBroadcast v-if="item.name == 'liveBroadcast'" :dataConfig="item"></liveBroadcast>
								<menus :class="isMenu ? 'transform-view' : ''" v-if="item.name == 'menus'" :dataConfig="item"></menus>
								<news v-if="item.name == 'news'" :dataConfig="item"></news>
								<presellList v-if="item.name == 'presellList'" :dataConfig="item"></presellList>
								<plantList v-if="item.name == 'plantList'" :dataConfig="item"></plantList>
								<hotRanking v-if="item.name == 'hotRanking' && hot_ranking_switch != 0" :dataConfig="item"></hotRanking>
								<promotionList v-if="item.name == 'promotionList'" :dataConfig="item" @changeTab="changeTab" :tempArr="tempArr" @detail="goDetail"></promotionList>
								<richText v-if="item.name == 'richText'" :dataConfig="item"></richText>
								<seckill v-if="item.name == 'seckill'" :dataConfig="item"></seckill>
								<swiperBg v-if="item.name == 'swiperBg'" :dataConfig="item"></swiperBg>
								<pictureCube v-if="item.name == 'pictureCube'" :dataConfig="item"></pictureCube>
								<titles v-if="item.name == 'titles'" :dataConfig="item"></titles>
								<shopList v-if="item.name == 'shopList' && hide_mer_status != 1" :dataConfig="item"></shopList>
							</view>
							<headerSerch v-if="item.name == 'headerSerch'" :dataConfig="item" :userInfo="userInfo"></headerSerch>
							<tabNav v-if="item.name == 'tabNav'" :dataConfig="item" class="tabNav" :tabTitle="navTop"
							@bindHeight="bindHeighta" @changeTab="changeTab" :isFixed="isFixed"></tabNav>
						</block>
						<!-- #endif -->
					</block>
					<!-- 分类页 -->
					<view class="productList" v-if="navIndex > 0">
						<view class="sort acea-row" v-if="sortList.length>0"  :style="{ marginTop: sortMarTop + 'px' }">
							<navigator
								hover-class="none"
								v-if="index < 9"
								:url="'/pages/columnGoods/goods_list/index?id=' + item.store_category_id + '&title=' + item.cate_name"
								class="item"
								v-for="(item, index) in sortList"
								:key="item.store_category_id"
							>
								<view class="pictrue cate_pictrue">
									<easy-loadimage :image-src="item.pic"></easy-loadimage>
									<view v-if="item.border_pic" :style="{ backgroundImage: `url(${item.border_pic})` }" class="border-picture"></view>
								</view>
								<view class="text">{{ item.cate_name }}</view>
							</navigator>
							<view class="item" @click="bindMore" v-if="sortList.length >= 9">
								<view class="pictrues acea-row row-center-wrapper"><text class="iconfont icon-gengduo1"></text></view>
								<view class="text" style="margin-top: 22rpx;">更多</view>
							</view>
						</view>	
						<block v-if="sortProduct.length > 0">
							<view class="list acea-row row-between-wrapper">
								<navigator @tap="goDetails(item)" class="item" v-for="(item, index) in sortProduct" :key="item.product_id">
									<view class="pictrue">
										<easy-loadimage :image-src="item.image"></easy-loadimage>
										<view v-if="item.border_pic" :style="{ backgroundImage: `url(${item.border_pic})` }" class="border-picture"></view>
									</view>
									<view class="text">
										<view class="name line1">
											<text class="name_text line2">{{ item.store_name }}</text>
										</view>
										<view class="acea-row row-middle">
											<view class="money font-color-red">
												￥
												<text class="num">{{ item.price }}</text>
											</view>
										</view>
										<view class="item_tags acea-row">
											<text v-if="item.merchant.type_name && item.product_type == 0" class="font-bg-red">{{ item.merchant.type_name }}</text>
											<text v-else-if="item.merchant.is_trader && item.product_type == 0" class="font-bg-red">自营</text>
											<text v-if="item.product_type != 0" :class="'font_bg-red type' + item.product_type">
												{{ item.product_type == 1 ? '秒杀' : item.product_type == 2 ? '预售' : item.product_type == 3 ? '助力' : item.product_type == 4 ? '拼团' : '' }}
											</text>
											<text class="tags_item ticket" v-if="item.issetCoupon">领券</text>
											<text class="tags_item delivery" v-if="item.delivery_free == 1">包邮</text>
										</view>
									</view>
								</navigator>
								<view class="loadingicon acea-row row-center-wrapper" v-if="sortProduct.length > 0 || sortProductLoading">
									<text class="loading iconfont icon-jiazai" :hidden="loading == false"></text>
									{{ loadTitle }}
								</view>
							</view>
						</block>		
						<block v-if="sortProduct.length == 0">
							<view class="noCommodity">
								<view class="pictrue" style="margin: 0 auto;">
									<image :src="`${domain}/static/images/noCart.png`"></image>
									<view>暂无商品，去看点什么吧</view>
								</view>
							</view>
						</block>
					</view>	
				</view>
			</view>
			<home></home>
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
let app = getApp();
import { getUserInfo } from '@/api/user.js';
import { getPageDiy, getIndexData } from '@/api/api.js';
import { getStorage } from '@/libs/uniApi.js';
// #ifdef MP-WEIXIN
import { SUBSCRIBE_MESSAGE, TIPS_KEY } from '@/config/cache';
// #endif
import { getShare, getconfig } from '@/api/public.js';
import { goShopDetail } from '@/libs/order.js';
import { mapGetters } from 'vuex';
import { getProductslist, storeCategory } from '@/api/store.js';
import { initiateAssistApi } from '@/api/activity.js';
import { spread } from '@/api/user.js';
import recommend from '@/components/recommend';
// #ifdef H5
import mConfig from '../index/component/index.js';
// #endif
import customTab from '@/components/customTab';
// #ifdef MP || APP-PLUS
import activeParty from '../index/component/activeParty';
import headerSerch from '../index/component/headerSerch';
import homeComb from '../index/component/homeComb';
import coupon from '../index/component/coupon';
import bargain from '../index/component/bargain';
import blankPage from '../index/component/blankPage';
import combination from '../index/component/combination';
import customerService from '../index/component/customerService';
import goodList from '../index/component/goodList';
import guide from '../index/component/guide';
import liveBroadcast from '../index/component/liveBroadcast';
import menus from '../index/component/menus';
import news from '../index/component/news';
import topic from '../index/component/topic';
import presellList from '../index/component/presellList';
import plantList from '../index/component/plantList';
import pictureCube from '../index/component/pictureCube'
import shopList from '../index/component/shopList';
import richText from '../index/component/richText';
import seckill from '../index/component/seckill';
import swiperBg from '../index/component/swiperBg';
import tabNav from '../index/component/tabNav';
import titles from '../index/component/titles';
import hotRanking from "../index/component/hotRanking.vue";
// #endif
import { silenceBindingSpread, configMap, isWeixin } from '@/utils/index';
import history from '@/mixins/history';
import shareScence from '@/libs/spread';
import easyLoadimage from '@/components/easy-loadimage/easy-loadimage.vue';
import {getNewPeopleCouponLst} from '@/api/activity.js'
import { HTTP_REQUEST_URL } from '@/config/app';
import home from '@/components/home/index.vue';
import { toLogin } from '@/libs/login.js';
export default {
	computed: configMap({hide_mer_status:0,recommend_switch:0,beian_sn:'',share_title:'',site_name:'',hot_ranking_switch:0,navigation: {}},
	mapGetters(['isLogin','uid','keyColor'])),
	mixins: [history],
	components: {
		easyLoadimage,
		home,
		// #ifdef H5
		...mConfig,
		// #endif
		// #ifdef MP || APP-PLUS
		activeParty,
		headerSerch,
		homeComb,
		bargain,
		coupon,
		blankPage,
		combination,
		customerService,
		goodList,
		guide,
		liveBroadcast,
		menus,
		news,
		topic,
		presellList,
		plantList,
		pictureCube,
		shopList,
		richText,
		seckill,
		swiperBg,
		tabNav,
		titles,
		hotRanking
		// #endif
	},
	data() {
		return {
			domain: HTTP_REQUEST_URL,
			couponTypeMsg:{
				10: '通用券',
				11: '品类券',
				12: '跨店券'
			},
			styleConfig: [],
			showSkeleton: true, //骨架屏显示隐藏
			isNodes: 0, //控制什么时候开始抓取元素节点,只要数值改变就重新抓取
			userInfo: {},
			loading: false,
			navIndex: 0,
			navTop: [],
			subscribe: false,
			followUrl: '',
			followHid: true,
			followCode: false,
			sortList: [],
			window: false,
			navH: '',
			couponList: [],
			marTop: 0,
			loadend: false,
			loading: false,
			loadTitle: '加载更多',
			sortProduct: [],
			where: {
				pid: 0,
				page: 1,
				limit: 6
			},
			is_switch: true,
			hotPage: 1,
			hotLimit: 30,
			hotScroll: true,
			hotLoading: false,
			hotTitle: '加载更多',
			// #ifdef MP || APP-PLUS
			isFixed: true,
			// #endif
			// #ifdef H5
			isFixed: false,
			// #endif
			isScrolled: false,
			scrolled: false,
			tempArr: [], //精品推荐临时数组
			d: '',
			h: '',
			m: '',
			s: '',
			sum_h: '',
			sortMarTop: 0,
			globalDatas: {},
			// #ifdef MP || APP-PLUS
			mpHeight: 108,
			// #endif
			// #ifndef MP || APP-PLUS
			mpHeight: 0,
			// #endif
			currSpid: '',
			_options:{},
			isIntegral: false,
			appUpdate: {},
			sortMpTop: 0,
			bgColor: '',
			bgPic: '',
			bgTabVal: '',
			windowHeight: 0,
			domHeight: 0,
			isHeaderSerch: false,
			showCoupon: false,
			couponArray: [],
			smallPage: false,
			isMenu: false,
			isScale: false,
			homeCombData: {},
			footerStatus: false,
			newData: {},
			activeRouter: '',
			overflow: false,
		};
	},
	/**
	 * 用户点击右上角分享
	 */
	// #ifdef MP
	onShareAppMessage: function() {
		let that = this;
		wx.showShareMenu({
			withShareTicket: true,
			menus: ['shareAppMessage', 'shareTimeline']
		});
		return {
			title: that.share_title,
			path: '/pages/index/index?spid=' + that.uid
		};
	},
	onShareTimeline: function() {
		let that = this;
		return {
			title: that.share_title,
			query: {
				spid: that.uid
			},
			imageUrl: ''
		};
	},
	// #endif
	onLoad(options) {
		let that = this
		this._options = options;	
		that.$nextTick(function() {
			uni.getSystemInfo({
				success: function(res) {
					that.windowHeight = res.windowHeight;
				}
			});
		})
		that.pageLoad(options);
	
	},
	created(options) {
		let routes = getCurrentPages(); // 获取当前打开过的页面路由数组
		let curRoute = routes[routes.length - 1].route //获取当前页面路由
		this.activeRouter = '/' + curRoute
	},
	onShow() {
		let that = this
		that.isIntegral = uni.getStorageSync('isIntegral')
		that.$nextTick(function() {
			const query = uni.createSelectorQuery();
			query.select('#home').boundingClientRect(); //获取当前元素距离顶部的距离
			query.exec(res =>{
				if(res[0].top <= -60)that.isScrolled = true
			})
		})		
	},
	onHide() {
		uni.setStorageSync('isIntegral',false)
	},
	onReady() {

	},
	watch: {
		globalDatas(nVal, oVal) {
			// #ifdef H5
			this.ShareInfo(nVal);
			// #endif
		},
	},
	onPullDownRefresh(){
		this.overflow = true;

	},
	methods: {
		pageLoad(options){
			let that = this;
			if (options.spid) {
				that.currSpid = options.spid;
				app.globalData.spid = options.spid;
			}else if(getStorage('spid')){
				that.currSpid = getStorage('spid');
				app.globalData.spid = getStorage('spid');
			}
			// #ifdef MP
			if (options.scene) {
				let value = that.$util.getUrlParams(decodeURIComponent(options.scene));
				if (value.id) options.id = value.id;
				//记录推广人uid
				if (value.spid) {
					that.currSpid = value.spid;
					app.globalData.spid = value.spid;
				}else if(getStorage('spid')){
					that.currSpid = getStorage('spid');
					app.globalData.spid = getStorage('spid');
				}
			}
			// #endif
			shareScence(that.currSpid, that.isLogin);
			this.isLogin && silenceBindingSpread();
			this.getIndexConfig();
			Promise.all([
				this.diyData(options.id),
			]);
			if (this.isLogin) {
				this.getUserInfo();
			}
		},
		closeShare(){
			uni.setStorageSync('isIntegral',false);
			this.isIntegral = uni.getStorageSync('isIntegral');
		},
		bindHeighta(data) {
			// #ifdef APP-PLUS
			this.sortMpTop = data.top + data.height;
			// #endif
		},
		// 对象转数组
		objToArr(data) {
			let obj = Object.keys(data).sort();
			let m = obj.map(key => data[key]);
			return m;
		},
		goRouter(item) {
			var pages = getCurrentPages();
			var page = (pages[pages.length - 1]).$page.fullPath;
			if (item.link == page) return
			uni.switchTab({
				url: item.link,
				fail(err) {
					uni.redirectTo({
						url: item.link
					})
				}
			})
		},
		diyData(id) {
			let that = this;
			let data = {
				id: id
			}
			that.styleConfig = []
			getPageDiy({id: id,version:'221'}).then(res => {
				setTimeout(() => {
					that.isNodes++;
				}, 0);
				let data = res.data.data;
				uni.setNavigationBarTitle({
					title: data.title
				});
				if (data.is_bg_color) {
					that.bgColor = data.color_picker
				}
				if (data.is_bg_pic) {
					that.bgPic = data.bg_pic
					that.bgTabVal = data.bg_tab_val
				}
				that.styleConfig = that.objToArr(data.value);
				that.styleConfig.forEach((item, index, arr) => {
					if (item.name == 'headerSerch' || item.name == 'homeComb') {
						that.isHeaderSerch = true
						if(item.name == 'homeComb'){
							// that.homeCombData = item
							that.$set(that, 'homeCombData', item)
							that.smallPage = true;
						}
					}
					if (item.name == 'pageFoot') {
						if (item.status && item.status.status) {
							this.newData = item
							setTimeout((e) => {
								that.$set(that, 'footerStatus', true);
							}, 50)
						}
						uni.setStorageSync('FOOTER_BAR', item.status && item.status.status ? true : false)
						item.menuList.map((path, index) => {
							if (path.link === '/pages/order_addcart/order_addcart') {
								uni.setStorageSync('FOOTER_ADDCART', index)
							}
						})
						arr.splice(index, 1);
					}
					if(item.name == 'menus'){
						that.isMenu = true;
					}
					uni.pageScrollTo({
						scrollTop: 0,
					})
					that.overflow = false;
				});
			}).catch(err => {
				uni.showToast({
					title: err,
					icon: 'none'
				})
			});
		},
		// 分类页更多
		bindMore() {
			try {
				uni.setStorageSync('storeIndex', this.navTop[this.navIndex].pid);
				uni.switchTab({
					url: '/pages/goods_cate/goods_cate'
				});
			} catch (e) {}
		},
		goDetails(item) {
			goShopDetail(item, this.uid).then(res => {
				if (this.isLogin) {
					initiateAssistApi(item.activity_id)
						.then(res => {
							let id = res.data.product_assist_set_id;
							uni.hideLoading();
							uni.navigateTo({
								url: '/pages/activity/assist_detail/index?id=' + id
							});
						})
						.catch(err => {
							uni.showToast({
								title: err,
								icon: 'none'
							});
						});
				} else {
					toLogin()
				}
			});
		},
		/**
		 * 获取个人用户信息
		 */
		getUserInfo: function() {
			let that = this;
			getUserInfo().then(res => {
				that.userInfo = res.data;
			});
		},
		// 导航分类切换
		changeTab(e) {
			let self = this;
			if (this.navIndex == e.index) return;
			this.navIndex = e.index;
			if (e.index > 0) {
				storeCategory({
					pid: e.pid
				}).then(res => {
					this.sortList = res.data.length > 9 ? res.data.splice(0, 9) : res.data;
					if (this.sortList.length > 0) {
						this.where.pid = e.pid;
						this.where.page = 1;
						this.loadend = false;
						this.loading = false;
						this.sortProduct = [];
						this.get_product_list();
						uni.pageScrollTo({
							scrollTop: 0,
						})
					}
				});
				self.sortMarTop = 10;
			}
		},
		//分类产品
		get_product_list: function() {
			let that = this;
			if (that.loading) return;
			that.loading = true;
			that.loadTitle = '';
			getProductslist(that.where)
				.then(res => {
					let list = res.data.list;
					let productList = that.$util.SplitArray(list, that.sortProduct);
					let loadend = list.length < that.where.limit;
					that.loadend = loadend;
					that.loading = false;
					that.loadTitle = loadend ? '已全部加载' : '加载更多';
					that.$set(that, 'sortProduct', productList);
					that.$set(that.where, 'page', that.where.page + 1);
				})
				.catch(err => {
					that.loading = false;
					that.loadTitle = '加载更多';
				});
		},
		
		// 首页数据
		getIndexConfig: function() {
			let that = this;
			getIndexData().then(res => {
				res.data.category.unshift({
					cate_name: '首页'
				});
				that.$set(that, 'navTop', res.data.category);
				// #ifdef H5
				that.subscribe = res.data.subscribe;
				// #endif
				// 小程序判断用户是否授权；
				// #ifdef MP
				uni.getSetting({
					success(res) {
						if (!res.authSetting['scope.userInfo']) {
							that.window = that.couponList.length ? true : false;
						} else {
							that.window = false;
						}
					}
				});
				// #endif
				// #ifndef MP
				if (that.isLogin) {
					that.window = false;
				} else {
					that.window = that.couponList.length ? true : false;
				}
				// #endif
				that.reloadData();
			});
		},
		reloadData() {
			setTimeout(() => {
				this.showSkeleton = false
			}, 500)
		},
		// 首发新品详情
		goDetail(item) {
			if (item.activity && item.activity.type === '2' && !this.isLogin) {
				// #ifdef H5
				uni.showModal({
					title: '提示',
					content: '您未登录，请登录！',
					success: function(res) {
						if (res.confirm) {
							uni.navigateTo({
								url: '/pages/users/login/index'
							});
						} else if (res.cancel) {
						}
					}
				});
				// #endif
				// #ifdef MP
				toLogin()
				// #endif
				return;
			} else {
				goShopDetail(item, this.uid).then(res => {
					uni.navigateTo({
						url: `/pages/goods_details/index?id=${item.id}`
					});
				});
			}
		},
		//#ifdef H5
		ShareInfo(datas) {
			let data = this.storeInfo;
			let href = location.href;
			if (this.$wechat.isWeixin()) {
				if (this.isLogin) {
					href = href.indexOf('?') === -1 ? href + '?spid=' + this.uid : href + '&spid=' + this.uid;
				} else {
					href = href;
				}
				let configAppMessage = {
					desc: datas.share_info,
					title: datas.share_title,
					link: href,
					imgUrl: datas.share_pic
				};
				this.$wechat
					.wechatEvevt(['updateAppMessageShareData', 'updateTimelineShareData', 'onMenuShareAppMessage', 'onMenuShareTimeline'], configAppMessage)
					.then(res => {})
					.catch(err => {
						console.log(err);
					});
			}
		},
		//#endif
	},
	mounted() {
		let query = uni.createSelectorQuery().in(this);
		query.select("#home").boundingClientRect();
		query.exec(res => {
			this.domHeight = res[0].height;
		})
	},
	// 滚动到底部
	onReachBottom() {
		// 分类栏目加载更多
		if (this.sortProduct.length > 0) {
			this.get_product_list();
		}	
	},
	// 滚动监听
	onPageScroll(e) {
		if (this.isHeaderSerch) {
			let isScroll = false
			isScroll = e.scrollTop > 50
			this.isScrolled = isScroll;
		} else {
			this.isScrolled = false
		}
		// 传入scrollTop值并触发所有easy-loadimage组件下的滚动监听事件
		uni.$emit('scroll');
	}
};
</script>
<style>

</style>
<style lang="scss" scoped>
.bnt1{
	color: red;
}
.overflow-mask{
	position: fixed;
	top: 0;
	left: 0;
	z-index: 1001;
	width: 100%;
	height: 100vh;
	background: transparent;
	transition: all .5s;
}
page {
	display: flex;
	flex-direction: column;
}
::v-deep  .home-comb .my-main{
	left: 0;
	position: fixed;
	top: 0;
	width: 100%;
	z-index: 30;
}
.main {
	padding: 0 20rpx;
}
.colum0{
	white-space: nowrap; 
	display: flex;
}
.ysize {
	background-size: 100%;
}
.fullsize {
	background-size: 100% 100%;
}
.repeat {
	background-repeat: repeat;
}
.noRepeat {
	background-repeat: no-repeat;
}
.privacy-wrapper {
	z-index: 999;
	position: fixed;
	left: 0;
	top: 0;
	bottom: 0;
	right: 0;
	width: 100%;
	height: 100%;
	background: #7f7f7f;
	.privacy-box {
		position: absolute;
		left: 50%;
		top: 50%;
		transform: translate(-50%, -50%);
		width: 560rpx;
		padding: 50rpx 45rpx 0;
		background: #fff;
		border-radius: 20rpx;
		.title {
			text-align: center;
			font-size: 32rpx;
			text-align: center;
			color: #333;
			font-weight: 700;
		}
		.content {
			margin-top: 20rpx;
			line-height: 1.5;
			font-size: 26rpx;
			color: #666;
			navigator {
				display: inline-block;
				color: #e93323;
			}
		}
		.btn-box {
			margin-top: 40rpx;
			text-align: center;
			font-size: 30rpx;
			.btn-item {
				height: 82rpx;
				line-height: 82rpx;
				background: linear-gradient(90deg, #f67a38 0%, #f11b09 100%);
				color: #fff;
				border-radius: 41rpx;
			}
			.btn {
				padding: 30rpx 0;
			}
		}
	}
}
.coupon_popups{
	z-index: 999;
	position: fixed;
	left: 0;
	top: 0;
	width: 100%;
	height: 100%;
	text-align: center;
	.bg{
		position: absolute;
		left: 0;
		top: 0;
		width: 100%;
		height: 100%;
		background-color: rgba(0,0,0,.5);
	}
	.con{
		position: absolute;
		left: 50%;
		top: 50%;
		transform: translate(-50%,-50%);
		width: 680rpx;
		height:900rpx;
		background-size: 100% 700rpx;
		background-repeat: no-repeat;
		scroll-view{
			width: 610rpx;
			height: 306rpx;
			padding-top: 20rpx;
			margin: 300rpx auto 0;
		}
		.item{
			display: flex;
			align-items: center;
			width: 100%;
			height: 164rpx;
			background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAABDgAAAFICAMAAACC1L7bAAAAilBMVEUAAAD////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////2N2iNAAAALXRSTlMA9xnrZyf0790ev4d1Egr7cl9TI+XNbVpAtKSUjX0NB6ucgnpDMdjRxUw7LdTK5bIiAAAF40lEQVR42uzcWW7CMBSF4UNImAIBEcpUKJQixt79b68qD1VLgNi8VHL+bxHH9tH1VUE+SeedVs1QkMmNAYGotTrzdNJWiSRtGe7J5aRnQFh2m1z3ndeGB05ycjYgOPNct2WLvuGRoZxMDAhPbbnSDQmPlDKpnCwMCNE+UsGxYSixl5MXA4JUP+lKbCgXyUHbgED1J/pjbHAwloONAcGKqfP8jVYqlTUNCNdQPxKDmw+V6hoQsEZOm+et0VOJHiUzwnbgjPQ3Uwlm6BC6WBcRZ6SHpa7QMqNimj19ezd42OqBLcO3CF/K2IG/5lR3Tbm8oQIuVd/S4CfmnYJqiyXtDJ5eM92QdAyohI6UG7zVB7qWpQZUxRufVJ4z6kb65dytG1AZR80NzzkMTkkkKY/XpAaqJWVqFICvmfiSBcDTp1hnDsDTiFX+AHzVCA4A3ggOAAQHgAKCA8D/Izi+2KmDAQAAAAZC/tYDGMB9CiJAHMARB9ATByAO4IgD6IkDEAdwxAH0xAGIAzjiAHriAMQBHHEAPXEA4gCOOICeOABxAEccQE8cgDiAIw6gJw5AHMARB9ATByAOxk4dDAAAADAQ8rcewADuUxDBEQfQEwcgDuCIA+iJAxAHcMQB9MQBiAM44gB64gDEARxxAD1xAOIAjjiAnjgAcQBHHEBPHIA4gCMOoCcOQBzAEQfQEwcgDuCIA+iJAxAHcMQB9MQBiAM44gB64gDEARxxAD1xAOIAjjiAnjgAcQBHHEBPHIA4gCMOoCcOQBzAEQfQEwcgDuCIA+iJAxAHcMQB9MQBjJ06GAAAAGAg5G89gAHcpyASB3DEAfTEAYgDOOIAeuIAxAEccQA9cQDiAI44gJ44AHEARxxATxyAOIAjDqAnDkAcwBEH0BMHIA7giAPoiQMQB3DEAfTEAYgDOOIAeuIAxAEccQA9cQDiAI44gJ44AHEARxxATxyAOIAjDqAnDkAcwBEH0BMHIA7giAPoiQMQB3DEAfTEAYgDOOIAeuIAxAEccQA9cTB26mAAAACAgZC/9QAOYJ+CCMQBhDiAP3EA4gBCHMCfOABxACEO4E8cgDiAEAfwJw5AHECIA/gTByAOIMQB/IkDEAcQ4gD+xAGIAwhxAH/iAMQBhDiAP3EA4gBCHMCfOABxACEO4E8cgDiAEAfwJw5AHECIA/gTByAOIMQB/IkDEAcQ4gD+xAGIAwhxAH/iAMQBhDiAP3EA4gBCHMCfOABxACEOYOzUwQAAAAADIX/rAQzgPgVRTxyAOIAjDqAnDkAcwBEH0BMHIA7giAPoiQMQB3DEAfTEAYgDOOIAeuIAxAEccQA9cQDiAI44gJ44AHEARxxATxyAOIAjDqAnDkAcwBEH0BMHIA7giAPoiQMQB3DEAfTEAYgDOOIAeuIAxAEccQA9cQDiAI44gJ44AHEARxxATxyAOIAjDqAnDkAcwBEH0BMHIA7giIOxU4cEAIBAEMBAIUny/esR4BTqzBZi0CcOQBxAEAfQJw5AHEAQB9AnDkAcQBAH0CcOQBxAEAfQJw5AHEAQB9AnDkAcQBAH0CcOQBxAEAfQt+4AfNnrDK+dO8tNGIaiMHwSoBAKlERAQ4FQhnSQeve/vaoPRVWayTza/7cI2/ecKwNwMtDEAMDJRFMDACdb7Qz3WZ3SRJqX2fJ1YEBI9no23GGyGemP8sLZgYBkig3uLqpaLDk6EIy5tDY42iaqsTgZEISppJnBSZSqwdKAEAwlZhVHg1KNcrZiEICHQhK9ipOoVIucRVz476wfcWToLVOroQGeG8wlkXI42akF7zeE4BbyvRv6WS3UoaCVhd+m+jUy9JPqhmoFQRrHukkNfUzU7bgywF+fXJPONmpFZATvDSttAD1it6hQMwY/+O8pVcWV3aVOW/XBFyfw1TjXP8mXod1MvewN8NE6Vo3jmU2wdrlaEDXDb9HhqHqjF0OLWL1cDfDOKVGz5PBmaCKJdBQhWl9idUiy/QczSy31UxjgjYfpYxar6hufchGebA48QgAAAABJRU5ErkJggg==');
			background-repeat: no-repeat;
			background-size: 100% 100%;
			margin-bottom: 20rpx;
			border-radius: 20rpx;
			position: relative;
			.left{
				width: 160rpx;
				text-align: center;
				&::after{
					content: "";
					display: block;
					height: 110rpx;
					border-right: 1px dashed #E6E6E6;
					position: absolute;
					left: 178rpx;
					top: 25rpx;
				}
				.price{
					color: #E93323;
					font-size: 26rpx;
					font-weight: bold;
					text{
						font-size: 46rpx;
					}
				}
				.max_price{
					color: #999999;
					font-size: 18rpx;
					margin-top: 8rpx;
				}
			}
			.right{
				width: 440rpx;
				padding: 0 20rpx;
				.title{
					color: #333333;
					font-size: 26rpx;
				}
				.time{
					color: #999999;
					font-size: 22rpx;
					margin-top: 20rpx;
				}
				.bnt1{
					.titleSize{
						font-weight: 700;
						font-size: 33rpx;
					}
					.titleColor{
						margin-top: 20rpx;
						color: #999999;
					}
				}
				.coupon_type{
					position: absolute;
					color: #fff;
					font-size: 15rpx;
					width: 30rpx;
					text-align: center;
					height: auto;
					background: #E93323;
					top: 0;
					right: 20rpx;
					line-height: 26rpx;
					padding: 5rpx 0 15rpx;
					&::after{
						content: "";
						width: 0;
						height: 0;
						border-left: 14rpx solid transparent;
						border-right: 14rpx solid transparent;
						border-bottom: 14rpx solid #fff;
						position: absolute;
						bottom: -4rpx;
						left: 2rpx;
					}
				}
			}
		}
		.text{
			width: 600rpx;
			margin: 30rpx auto 0;
			font-size: 20rpx;
			color: #ffffff;
		}
		.icon-guanbi3{
			color: #ffffff;
			font-size: 70rpx;
			position: absolute;
			bottom: -80rpx;
			left: 320rpx;
		}
	}
}
.item_tags{
	margin-top: 8rpx;
	display: flex;
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
	color: var(--view-theme);
	border: 1px solid var(--view-theme);
}
.item_tags .tags_item.delivery{
	color: #FF9000;
	border: 1px solid #FF9000;
}
.copyRight{
	margin: 60rpx auto 20rpx;
	max-width: 690rpx;
	font-size: 20rpx;
	color: #707070;
	text-decoration: none;
}
.share-box {
	z-index: 1000;
	position: fixed;
	left: 0;
	top: 0;
	width: 100%;
	height: 100%;
	image {
		width: 100%;
		height: 100%;
	}
}
.error-network {
	position: fixed;
	left: 0;
	top: 0;
	display: flex;
	flex-direction: column;
	align-items: center;
	width: 100%;
	height: 100%;
	padding-top: 40rpx;
	background: #fff;
	padding-top: 30%;
	image {
		width: 414rpx;
		height: 336rpx;
	}
	.title {
		position: relative;
		top: -40rpx;
		font-size: 32rpx;
		color: #666;
	}
	.con {
		font-size: 24rpx;
		color: #999;
		.label {
			margin-bottom: 20rpx;
		}
		.item {
			margin-bottom: 20rpx;
		}
	}
	.btn {
		display: flex;
		align-items: center;
		justify-content: center;
		width: 508rpx;
		height: 86rpx;
		margin-top: 100rpx;
		border: 1px solid #d74432;
		color: #e93323;
		font-size: 30rpx;
		border-radius: 120rpx;
	}
}
.area-row {
	overflow: hidden;
	text-overflow: ellipsis;
	white-space: nowrap;
	display: block;
	width: 100%;
	text-align: center;
}
.page-index {
	display: flex;
	flex-direction: column;
	min-height: 100%;
	.page_content {
		/* #ifdef MP || APP-PLUS */
		padding-top: 270rpx;
		/* #endif */
		.page_bg{
			background: linear-gradient(180deg, #fff 0%, #f5f5f5 100%);
		}
		.nav {
			padding: 0 0rpx 30rpx;
			flex-wrap: wrap;
			/* #ifdef MP */
			margin-top: 0;
			/* #endif */
			/* #ifdef H5 */
			margin-top: 0;
			/* #endif */
			.item {
				display: flex;
				flex-direction: column;
				align-items: center;
				justify-content: center;
				width: 20%;
				margin-top: 30rpx;
				image {
					width: 82rpx;
					height: 82rpx;
				}
			}
		}
		.index-product-wrapper {
			.nav-bd {
				display: flex;
				align-items: center;
				.item {
					display: flex;
					flex-direction: column;
					align-items: center;
					justify-content: center;
					width: 25%;
					.txt {
						font-size: 32rpx;
						color: #282828;
					}
					.label {
						display: flex;
						align-items: center;
						justify-content: center;
						width: 124rpx;
						height: 32rpx;
						margin-top: 5rpx;
						font-size: 24rpx;
						color: #999;
					}
					&.active {
						color: $theme-color;
						.label {
							background: linear-gradient(90deg, $bg-star 0%, $bg-end 100%);
							border-radius: 16rpx;
							color: #fff;
						}
					}
				}
			}
			.list-box {
				display: flex;
				flex-wrap: wrap;
				justify-content: space-between;
				.item {
					width: 345rpx;
					margin-bottom: 20rpx;
					background-color: #fff;
					border-radius: 10px;
					overflow: hidden;
					image {
						width: 100%;
						height: 345rpx;
					}
					.text-info {
						padding: 10rpx 20rpx 15rpx;
						.title {
							color: #222222;
						}
						.old-price {
							margin-top: 8rpx;
							font-size: 26rpx;
							color: #aaaaaa;
							text-decoration: line-through;
							text {
								margin-right: 2px;
								font-size: 20rpx;
							}
						}
						.price {
							display: flex;
							align-items: flex-end;
							color: $theme-color;
							font-size: 34rpx;
							font-weight: 800;
							text {
								padding-bottom: 4rpx;
								font-size: 24rpx;
								font-weight: normal;
							}
							.txt {
								display: flex;
								align-items: center;
								justify-content: center;
								width: 28rpx;
								height: 28rpx;
								margin-left: 15rpx;
								margin-bottom: 10rpx;
								border: 1px solid $theme-color;
								border-radius: 4rpx;
								font-size: 22rpx;
								font-weight: normal;
							}
						}
					}
				}
				&.on {
					display: flex;
				}
			}
		}
	}
}
.productList {
	background-color: #f1f1f1;
	min-height: 70vh;
	.sort {
		width: 710rpx;
		max-height: 380rpx;
		background: rgba(255, 255, 255, 1);
		border-radius: 16rpx;
		padding: 8rpx 0rpx 30rpx;
		flex-wrap: wrap;
		margin: 25rpx auto 0 auto;
		.item {
			width: 20%;
			margin-top: 30rpx;
			text-align: center;
			.pictrues {
				width: 90rpx;
				height: 90rpx;
				background: rgba(248, 248, 248, 1);
				border-radius: 50%;
				margin: 0 auto;
			}
			.easy-loadimage {
				width: 90rpx;
				height: 90rpx;
				display: inline-block;
			}
			.text {
				color: #272727;
				font-size: 24rpx;
				margin-top: 10rpx;
				overflow: hidden;
				white-space: nowrap;
				text-overflow: ellipsis;
			}
		}
	}
}
.productList .list {
	padding: 0 20rpx;
}
.productList .list.on {
	background-color: #fff;
	border-top: 1px solid #f6f6f6;
}
.productList .list .item {
	width: 345rpx;
	margin-top: 20rpx;
	background-color: #fff;
	border-radius: 10rpx;
	.name{
		display: flex;
		align-items: center;
	}
}
.productList .list .item.on {
	width: 100%;
	display: flex;
	border-bottom: 1px solid #f6f6f6;
	padding: 30rpx 0;
	margin: 0;
}
.productList .list .item .pictrue {
	position: relative;
	width: 100%;
	height: 345rpx;
}
.productList .list .item .pictrue.on {
	width: 180rpx;
	height: 180rpx;
}
::v-deep .productList .list .item .pictrue image,
::v-deep .productList .list .item .pictrue uni-image,
::v-deep .productList .list .item .pictrue .easy-loadimage{
	width: 100%;
	height: 100%;
	border-radius: 10rpx 10rpx 0 0;
}
::v-deep .productList .list .item .pictrue uni-image.origin-img{
	border-radius: 10rpx 10rpx 0 0;
}
.productList .list .item .pictrue image.on {
	border-radius: 6rpx;
}
.productList .list .item .text {
	padding: 14rpx 17rpx 26rpx 17rpx;
	font-size: 28rpx;
	color: #212121;
}
.productList .list .item .text.on {
	width: 508rpx;
	padding: 0 0 0 22rpx;
}
.productList .list .item .text .money {
	font-size: 26rpx;
	font-weight: bold;
	margin-top: 8rpx;
}
.productList .list .item .text .coupon {
	background: rgba(255, 248, 247, 1);
	border: 1px solid rgba(233, 51, 35, 1);
	border-radius: 4rpx;
	font-size: 20rpx;
	margin-left: 18rpx;
	padding: 1rpx 4rpx;
}
.productList .list .item .text .money.on {
	margin-top: 50rpx;
}
.productList .list .item .text .money .num {
	font-size: 34rpx;
}
.pictrue {
	position: relative;
	
	.border-picture {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		border-radius: 10rpx 10rpx 0 0;
		background: center/cover no-repeat;
	}
}
.cate_pictrue,::v-deep .cate_pictrue image,::v-deep .cate_pictrue .easy-loadimage,::v-deep .cate_pictrue uni-image{
	width: 82rpx;
	height: 82rpx;
	margin: 0 auto;
}
.fixed {
	z-index: 100;
	position: fixed;
	left: 0;
	top: 0;
	background-color: #fff;
	box-shadow: 0 10rpx 20rpx -5rpx rgba(0, 0, 0, 0.06);
}
.page-footer {
	position: fixed;
	bottom: 0;
	z-index: 30;
	display: flex;
	align-items: center;
	justify-content: space-around;
	width: 100%;
	height: calc(98rpx + constant(safe-area-inset-bottom)); ///兼容 IOS<11.2/
	height: calc(98rpx + env(safe-area-inset-bottom)); ///兼容 IOS>11.2/
	box-sizing: border-box;
	border-top: 1px solid #F3F3F3;
	background-color: #fff;
	box-shadow: 0px 0px 17rpx 1rpx rgba(206, 206, 206, 0.32);
	padding-bottom: constant(safe-area-inset-bottom); ///兼容 IOS<11.2/
	padding-bottom: env(safe-area-inset-bottom); ///兼容 IOS>11.2/
	&.filter{
		filter: blur(2rpx);
	}
	.foot-item {
		display: flex;
		width: max-content;
		align-items: center;
		justify-content: center;
		flex-direction: column;
		position: relative;
		.count-num {
			position: absolute;
			display: flex;
			justify-content: center;
			align-items: center;
			width: 40rpx;
			height: 40rpx;
			top: 0rpx;
			right: -15rpx;
			color: #fff;
			font-size: 20rpx;
			background-color: #FD502F;
			border-radius: 50%;
			padding: 4rpx;
		}
	}
	.foot-item image {
		height: 50rpx;
		width: 50rpx;
		text-align: center;
		margin: 0 auto;
	}
	.foot-item .txt {
		font-size: 24rpx;
	}
}
</style>
