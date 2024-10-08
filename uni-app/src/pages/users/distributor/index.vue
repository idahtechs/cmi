<template>
	<view class="distributor" :style="viewColor">
		<view class="headerBg" :style="{ 'background-image': `url(${domain}/static/diy/distribution${keyColor}.png)` }">
			<view v-if="openActivity" class="explain" @click="explain">分销说明</view>
			<view class="picTxt acea-row row-middle">
				<view class="pictrue">
					<image class="avatar" :src='userInfo.avatar' v-if="userInfo.avatar"></image>
					<image v-else class="avatar" src="/static/images/f.png" mode=""></image>
				</view>
				<view class="text">
					<view class="name line1">{{userInfo.nickname}}</view>
					<view class="info line1">直接推广人越多，获得的奖励越多哦！</view>
				</view>
			</view>
		</view>
		<!-- menu -->
		<view class='nav acea-row' v-if="menus.length && openActivity">
			<block v-for="(item,index) in menus" :key="index">
				<view class='item'>
					<view class='pictrue' style="width: 90rpx; height: 90rpx; background-color: #FFF8EC; border-radius: 100%; text-align: center;">
						<image :src='item.img'></image>
					</view>
					<view class="menu-txt area-row">{{item.title}}</view>
				</view>
			</block>
		</view>
		<!-- 推荐礼包 -->
		<view class="recommend" v-if="openActivity">
			<view v-if="fastList.length > 0" class="public_title acea-row row-center-wrapper">
				<image :src="domain+'/static/diy/linefx'+keyColor+'.png'"></image>
				<view class="name">推荐礼包</view>
				<image :src="domain+'/static/diy/linefx'+keyColor+'.png'" class="right"></image>
			</view>
			<view v-if="fastList.length > 0" class='scroll-product'>
				<scroll-view class="scroll-view_x" scroll-x style="width:auto;overflow:hidden;">
					<block v-for="(item,index) in fastList" :key='index'>
						<view class="itemCon">
							<view class="item acea-row row-right">
								<view class="picTxt acea-row row-between-wrapper" @click="godDetail(item)">
									<view class="pictrue">
										<image :src="item.image"></image>
									</view>
									<view class="text">
										<view class="name line1">{{item.store_name}}</view>
										<!-- <text v-if="item.merchant && item.merchant.type_name" class="font-bg-red mt8 b-color">{{item.merchant.type_name}}</text>
										<text v-else-if="item.merchant && item.merchant.is_trader" class="font-bg-red mt8  b-color">自营</text> -->
										<view class="acea-row row-bottom price_count">
											<view class="money p-color">￥<text class="num">{{item.price}}</text></view>
											<view v-if="item.show_svip_info&&item.show_svip_info.show_svip_price&&item.svip_price" class="acea-row row-middle svip-count">
												<text class='vip-money'>￥{{item.svip_price}}</text>
												<view class="vipImg">
													<image src="/static/images/svip.png"></image>
												</view>
											</view>
										</view>
									</view>
								</view>
								<view class="circular"></view>
								<view class="open b-color" @click="goBuy(item)">立即开通</view>
							</view>
						</view>
					</block>
				</scroll-view>
			</view>
		</view>
		<view class="pin" v-if="openActivity">
			<view class="public_title acea-row row-center-wrapper">
				<image :src="domain+'/static/diy/linefx'+keyColor+'.png'"></image>
				<view class="name">分销礼包</view>
				<image :src="domain+'/static/diy/linefx'+keyColor+'.png'" class="right"></image>
			</view>
			<view class="list">
				<view class="item acea-row row-between-wrapper" v-for="(item, index) in distribution" :key='index' @click="godDetail(item)">
					<view class="pictrue">
						<image :src="item.image"></image>
					</view>
					<view class="text">
						<view class="name line1">
							<!-- <text v-if="item.merchant && item.merchant.type_name" class="font-bg-red ml8 b-color">{{item.merchant.type_name}}</text>
							<text v-else-if="item.merchant && item.merchant.is_trader" class="font-bg-red ml8 b-color">自营</text> -->
							{{item.store_name}}
						</view>
						<view class="acea-row">
							<view class="money p-color">￥<text class="num">{{item.price}}</text></view>
							<view v-if="item.show_svip_info&&item.show_svip_info.show_svip_price&&item.svip_price" class="acea-row row-middle svip-count">
								<text class='vip-money'>￥{{item.svip_price}}</text>
								<view class="vipImg">
									<image src="/static/images/svip.png"></image>
								</view>
							</view>
						</view>
						<view class="open b-color" @click.stop="goBuy(item)">立即开通</view>
					</view>
				</view>
			</view>
			<view class='loadingicon acea-row row-center-wrapper' v-if='distribution.length > 0 '>
				<text class='loading iconfont icon-jiazai' :hidden='loading==false'></text>{{loadTitle}}
			</view>
		</view>
		<view class="explainTxt" :class="explainShow?'on':''">
			<view class="name">分销说明<text class="iconfont icon-guanbi" @click="close"></text></view>
			<view class="conter">{{explainTxt}}</view>
		</view>
		<view class="mask" v-if="explainShow"></view>
		<!-- 组件 -->
		<productWindow :attr="attr" :isShow='1' :iSplus='1' :destri='1' :svipPrice="true" @myevent="onMyEvent" @ChangeAttr="ChangeAttr"
		 @attrVal="attrVal" @iptCartNum="iptCartNum" @goCat="goPay" id='product-window'></productWindow>
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
	import { getUserInfo } from '@/api/user.js';
	import { goShopDetail } from '@/libs/order.js'
	import { postCartAdd } from '@/api/store.js';
	import { bagExplain, bagRecommend, productBag, getProductDetail } from '@/api/store.js';
	import ProductWindow from "@/components/productWindow";
	import { mapGetters } from "vuex";
	import { HTTP_REQUEST_URL } from '@/config/app';
	export default {
		components: {
			ProductWindow
		},
		computed: mapGetters(['viewColor','keyColor']),
		data() {
			return {
				domain: HTTP_REQUEST_URL,
				explainShow: false,
				menus: [],
				fastList: [],
				userInfo: {},
				distribution: [],
				explainTxt: '',
				attr: {
					cartAttr: false,
					productAttr: [],
					productSelect: {}
				},
				productValue: [], //系统属性
				storeInfo: {},
				attrValue: '', //已选属性
				attrTxt: '请选择', //属性页面提示
				cart_num: 1, //购买数量
				id: 0, //产品id
				loadend: false,
				loading: false,
				loadTitle: '加载更多',
				where: {
					page: 1,
					limit: 20,
				},
				openActivity: false,
			}
		},
		onLoad() {},
		onShow: function() {
			this.bagExplain();
			this.productBag();	
			this.bagRecommend();		
			this.getUserInfo();
		},
		// 滚动到底部
		onReachBottom() {
			this.productBag();
		},
		methods: {
			goBuy: function(item) {
				let that = this;
				that.id = item.product_id;
				that.getGoodsDetails(item);
			},
			onMyEvent: function() {
				this.$set(this.attr, 'cartAttr', false);
			},
			/**
			 * 获取产品详情
			 * 
			 */
			getGoodsDetails: function(item) {
				uni.showLoading({
					title: '加载中',
					mask: true
				});
				let that = this;
				getProductDetail(item.product_id).then(res => {
					uni.hideLoading();
					that.attr.cartAttr = true;
					let storeInfo = res.data;
					that.$set(that, 'storeInfo', storeInfo);
					that.$set(that.attr, 'productAttr', res.data.attr);
					that.$set(that, 'productValue', res.data.sku);
					that.DefaultSelect();
				}).catch(err => {
					uni.hideLoading();
				})
			},
			/**
			 * 属性变动赋值
			 * 
			 */
			ChangeAttr: function(res) {
				let productSelect = this.productValue[res];
				if (productSelect && productSelect.stock > 0) {
					this.$set(this.attr.productSelect, "image", productSelect.image);
					this.$set(this.attr.productSelect, "price", productSelect.price);
					this.$set(this.attr.productSelect, "svip_price", productSelect.svip_price);
					this.$set(this.attr.productSelect, "stock", productSelect.stock);
					this.$set(this.attr.productSelect, "unique", productSelect.unique);
					this.$set(this.attr.productSelect, "cart_num", 1);
					this.$set(this, "attrValue", res);
					this.$set(this, "attrTxt", "已选择");
				} else {
					this.$set(this.attr.productSelect, "image", this.storeInfo.image);
					this.$set(this.attr.productSelect, "price", this.storeInfo.price);
					this.$set(this.attr.productSelect, "svip_price", productSelect.svip_price);
					this.$set(this.attr.productSelect, "stock", 0);
					this.$set(this.attr.productSelect, "unique", "");
					this.$set(this.attr.productSelect, "cart_num", 0);
					this.$set(this, "attrValue", "");
					this.$set(this, "attrTxt", "请选择");
				}
			},
			/**
			 * 默认选中属性
			 * 
			 */
			DefaultSelect: function() {
				let productAttr = this.attr.productAttr;
				let value = [];
				let arr = [];
				let unSortArr = [];
				for (var key in this.productValue) {
					if (this.productValue[key].stock > 0) {
						value = this.attr.productAttr.length ? key.split(",") : [];
						break;
					}
				}
				for (let i = 0; i < productAttr.length; i++) {
					this.$set(productAttr[i], "index", value[i]);
				}
				//sort();排序函数:数字-英文-汉字；
				let productSelect = this.productValue[value.join(",")];
				if (productSelect && productAttr.length) {
					this.$set(
						this.attr.productSelect,
						"store_name",
						this.storeInfo.store_name
					);
					this.$set(this.attr.productSelect, "image", productSelect.image);
					this.$set(this.attr.productSelect, "price", productSelect.price);
					this.$set(this.attr.productSelect, "svip_price", productSelect.svip_price);
					this.$set(this.attr.productSelect, "stock", productSelect.stock);
					this.$set(this.attr.productSelect, "unique", productSelect.unique);

					this.$set(this, "attrValue", value.join(","));
					this.$set(this, "attrTxt", "已选择");
					if (productSelect.stock == 0) {
						this.$set(this.attr.productSelect, "cart_num", 0);
					} else {
						this.$set(this.attr.productSelect, "cart_num", 1);
					}
				} else if (!productSelect && productAttr.length) {
					this.$set(
						this.attr.productSelect,
						"store_name",
						this.storeInfo.store_name
					);
					this.$set(this.attr.productSelect, "image", this.storeInfo.image);
					this.$set(this.attr.productSelect, "price", this.storeInfo.price);
					this.$set(this.attr.productSelect, "svip_price", productSelect.svip_price);
					this.$set(this.attr.productSelect, "stock", 0);
					this.$set(this.attr.productSelect, "unique", "");
					this.$set(this.attr.productSelect, "cart_num", 0);
					this.$set(this, "attrValue", "");
					this.$set(this, "attrTxt", "请选择");
				} else if (!productSelect && !productAttr.length) {
					this.$set(
						this.attr.productSelect,
						"store_name",
						this.storeInfo.store_name
					);
					this.$set(this.attr.productSelect, "image", this.storeInfo.image);
					this.$set(this.attr.productSelect, "price", this.storeInfo.price);
					this.$set(this.attr.productSelect, "svip_price", productSelect.svip_price);
					this.$set(this.attr.productSelect, "stock", this.storeInfo.stock);
					this.$set(
						this.attr.productSelect,
						"unique",
						this.storeInfo.unique || ""
					);
					this.$set(this.attr.productSelect, "cart_num", 1);
					this.$set(this, "attrValue", "");
					this.$set(this, "attrTxt", "请选择");
				} else if (productSelect && !productAttr.length) {
					this.$set(
						this.attr.productSelect,
						"store_name",
						this.storeInfo.store_name
					);
					this.$set(this.attr.productSelect, "image", productSelect.image);
					this.$set(this.attr.productSelect, "price", productSelect.price);
					this.$set(this.attr.productSelect, "svip_price", productSelect.svip_price);
					this.$set(this.attr.productSelect, "stock", productSelect.stock);
					this.$set(this.attr.productSelect, "unique", productSelect.unique);
					this.$set(this, "attrValue", value.join(","));
					this.$set(this, "attrTxt", "已选择");
					if (productSelect.stock == 0) {
						this.$set(this.attr.productSelect, "cart_num", 0);
					} else {
						this.$set(this.attr.productSelect, "cart_num", 1);
					}
				}
			},
			/**
			 * 购物车数量加和数量减
			 * 
			 */
			ChangeCartNum: function(changeValue) {
				//changeValue:是否 加|减
				//获取当前变动属性
				let productSelect = this.productValue[this.attrValue];
				//如果没有属性,赋值给商品默认库存
				if (productSelect === undefined && !this.attr.productAttr.length)
					productSelect = this.attr.productSelect;
				//无属性值即库存为0；不存在加减；
				if (productSelect === undefined) return;
				let stock = productSelect.stock || 0;
				let num = this.attr.productSelect;
				if (changeValue) {
					num.cart_num++;
					if (num.cart_num > stock) {
						this.$set(this.attr.productSelect, "cart_num", stock);
						this.$set(this, "cart_num", stock);
					}
				} else {
					num.cart_num--;
					if (num.cart_num < 1) {
						this.$set(this.attr.productSelect, "cart_num", 1);
						this.$set(this, "cart_num", 1);
					}
				}
			},
			attrVal(val) {
				this.$set(this.attr.productAttr[val.indexw], 'index', this.attr.productAttr[val.indexw].attr_values[val.indexn]);
			},
			/**
			 * 购物车手动填写
			 * 
			 */
			iptCartNum: function(e) {
				this.$set(this.attr.productSelect, 'cart_num', e);
			},
			// 立即购买
			goPay() {
				let that = this,
					productSelect = that.productValue[this.attrValue];
				if (
					that.attr.productAttr.length &&
					productSelect.stock == 0
				)
					return that.$util.Tips({
						title: "产品库存不足，请选择其它"
					});
				let q = {
					product_id: that.id,
					cart_num: Number(that.attr.productSelect.cart_num),
					is_new: 1,
					product_attr_unique: that.attr.productSelect !== undefined ? that.attr.productSelect.unique : ""
				};
				postCartAdd(q)
					.then(function(res) {
						that.attr.cartAttr = false;
						uni.navigateTo({
							url: '/pages/users/order_confirm/index?cartId=' + res.data.cart_id
						});
					})
					.catch(res => {
						return that.$util.Tips({
							title: res
						});
					});
			},

			// 去商品详情
			godDetail(item) {
				goShopDetail(item).then(res => {
					uni.navigateTo({
						url: `/pages/goods_details/index?id=${item.product_id}`
					})
				})
			},
			// 分销
			productBag: function() {
				let that = this;
				if (that.loadend) return;
				if (that.loading) return;
				that.loading = true;
				that.loadTitle = '';
				productBag(that.where).then(res => {
					let list = res.data.list;
					let productList = that.$util.SplitArray(list, that.distribution);
					let loadend = list.length < that.where.limit;
					that.loadend = loadend;
					that.loading = false;
					that.loadTitle = loadend ? '已全部加载' : '加载更多';
					that.$set(that, 'distribution', productList);
					that.$set(that.where, 'page', that.where.page + 1);
				}).catch(err => {
					that.loading = false;
					that.loadTitle = '加载更多';
				});
			},
			// 推荐
			bagRecommend: function() {
				let that = this;
				bagRecommend().then(res => {
					this.fastList = res.data.list
				})
			},
			/**
			 * 获取个人用户信息
			 */
			getUserInfo: function() {
				let that = this;
				getUserInfo().then(res => {
					that.userInfo = res.data
				});
			},
			// 说明以及导航
			bagExplain: function() {
				let that = this;
				bagExplain().then(res => {
					let data = res.data;
					this.menus = data.data;
					this.explainTxt = data.explain;
					this.openActivity = true
				}).catch(res => {
					this.openActivity = false
					return that.$util.Tips({
						title: res
					});
				});
			},

			explain() {
				this.explainShow = true;
			},
			close() {
				this.explainShow = false;
			}
		}
	}
</script>

<style lang="scss" scoped>
	page {
		background-color: #fff;
	}
	.area-row {
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
		display: block;
		width: 80%;
		text-align: center;
	}
	.b-color {
		border: 1rpx solid var(--view-theme);
		background-color: var(--view-theme);
	}
	.svip-count{
	
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
		margin: 4rpx 0 0 4rpx;
		image {
			width: 100%;
			height: 100%;
			display: block;
		}
	}
	.distributor {
		.headerBg {
			width: 100%;
			height: 371rpx;
			background-size: 100% 100%;
			background-repeat: no-repeat;
			position: relative;
			.explain {
				position: absolute;
				top: 30rpx;
				right: 0;
				width: 134rpx;
				height: 40rpx;
				background: linear-gradient(-90deg, rgba(239, 215, 168, 1) 0%, rgba(248, 230, 193, 1) 100%);
				border-radius: 20px 0px 0px 20px;
				color: var(--view-theme);
				font-size: 24rpx;
				font-weight: 500;
				text-align: center;
				line-height: 40rpx;
			}
			.picTxt {
				position: absolute;
				left: 60rpx;
				bottom: 38rpx;
				.pictrue {
					width: 84rpx;
					height: 84rpx;
					border-radius: 50%;
					image {
						width: 100%;
						height: 100%;
						border-radius: 50%;
					}
				}
				.text {
					font-size: 34rpx;
					color: #814C07;
					margin-left: 19rpx;
					width: 500rpx;
				
				}
				.info {
					font-size: 28rpx;
					color: #BB8D59;
					margin-top: 10rpx;
				}
			}
		}
		.nav {
			padding: 0 0rpx 30rpx;
			flex-wrap: wrap;
			.item {
				display: flex;
				flex-direction: column;
				align-items: center;
				justify-content: center;
				width: 25%;
				margin-top: 30rpx;
				image {
					width: 48rpx;
					height: 48rpx;
					border-radius: 50%;
					position: relative;
					top: 20rpx;							
				}
				.picture{
					border: 40rpx solid #FFF8EC;					
				}				
				.menu-txt {
					color: #B99450;
					font-size: 32rpx;
					margin-top: 20rpx;
				}
			}
		}		
		.public_title {
			margin-top: 20rpx;
			image {
				width: 119rpx;
				height: 15rpx;
				&.right {
					transform: rotate(180deg);
				}
			}
			.name {
				font-size: 34rpx;
				color: var(--view-theme);
				margin: 0 19rpx;
			}
		}
		.recommend {
			.scroll-product {
				white-space: nowrap;
				margin-top: 45rpx;
				padding-left: 30rpx;
				height: 200rpx;
				.scroll-view_x {
					height: 100%;
				}
				.itemCon {
					display: inline-block;
					.item {
						width: 430rpx;
						height: 156rpx;
						margin-right: 30rpx;
						border-radius: 8rpx;
						position: relative;
						box-shadow: 0 10rpx 20rpx -5rpx #eee;
						.circular {
							width: 20rpx;
							height: 20rpx;
							border-radius: 50%;
							position: absolute;
							top: 50%;
							margin-top: -10rpx;
							right: 40rpx;
							background: var(--view-theme);
						}
						.open {
							width: 60rpx;
							writing-mode: vertical-lr;
							writing-mode: tb-lr;
							color: #fff;
							height: 100%;
							text-align: center;
							line-height: 70rpx;
							border-radius: 0 8rpx 8rpx 0;
							border: none;
						}
						.picTxt {
							width: 382rpx;
							height: 100%;
							border-radius: 0 8rpx 8rpx 0;
							position: absolute;
							left: 0;
							top: 0;
							background-color: #fff;
							.pictrue {
								width: 156rpx;
								height: 100%;
								image {
									width: 100%;
									height: 100%;
									border-radius: 8rpx 0 0 8rpx;
								}
							}
							.text {
								width: 210rpx;
								padding-right: 10rpx;
								box-sizing: border-box;
								.name {
									width: 210rpx;
									font-size: 26rpx;
									color: #282828;
									white-space: nowrap;
									text-overflow: ellipsis;
									overflow: hidden;
									margin-bottom: 20rpx;
								}
								.price_count{
									height: 76rpx;
								}
								.money {
									font-size: 28rpx;
									color: var(--view-priceColor);
									font-weight: bold;
									.num {
										font-size: 32rpx;
										
									}
								}
							}
						}
					}
				}
			}
		}
		.pin {
			.list {
				padding-left: 30rpx;
				margin-top: 50rpx;
				.item {
					position: relative;
					.pictrue {
						width: 210rpx;
						height: 210rpx;
						border-radius: 16px;
						image {
							width: 100%;
							height: 100%;
						}
					}
					&~.item {
						margin-top: 60rpx;
						.text {
							&::after {
								width: 100%;
								height: 1px;
								position: absolute;
								content: '';
								top: -30rpx;
								background-color: #F0F0F0;
							}
						}
					}
					.text {
						position: relative;
						width: 460rpx;
						font-size: 30rpx;
						color: #282828;
						padding: 10rpx 30rpx 24rpx 0;
						.name{
							height: 70rpx;
						}
						.money {
							font-size: 28rpx;
							color: var(--view-priceColor);
							font-weight: bold;
							.num {
								font-size: 36rpx;
								
							}
						}
						.open {
							width: 144rpx;
							height: 48rpx;
							border-radius: 24rpx;
							color: #fff;
							text-align: center;
							line-height: 48rpx;
							font-size: 24rpx;
							right: 30rpx;
							border: none;
							margin-top: 40rpx;
						}
					}
				}
			}
		}
		.explainTxt {
			position: fixed;
			top: 20%;
			left: 50%;
			width: 600rpx;
			background-color: #fff;
			margin-left: -300rpx;
			border-radius: 8rpx;
			z-index: 32;
			padding: 33rpx;
			transition: all 0.3s ease-in-out 0s;
			opacity: 0;
			transform: scale(0);
			&.on {
				opacity: 1;
				transform: scale(1);
			}
			.name {
				font-size: 38rpx;
				position: relative;
				text-align: center;
				color: var(--view-theme);
				.iconfont {
					position: absolute;
					color: #999999;
					font-size: 37rpx;
					top: -10rpx;
					right: 0rpx;
				}
			}
			.conter {
				margin-top: 30rpx;
				font-size: 26rpx;
				color: #282828;
				line-height: 1.6;
				max-height: 600rpx;
				overflow-y: auto;
			}
		}
	}
</style>
