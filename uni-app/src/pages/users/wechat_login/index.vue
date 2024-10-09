<template>
	<view :style="viewColor" class="wrapper">
		<view class="bag">
			<img :src="`${domain}/static/images/logo_bgh.png`" alt="" srcset="">
		</view>
		<view class="system-height" :style="{ height: statusBarHeight }"></view>
		<!-- #ifdef MP -->
		<view class="title-bar" style="height: 43px;">
			<view class="icon" @click="back" v-if="!isHome">
				<text class="iconfont icon-fanhui"></text>
			</view>
			<view class="icon" @click="home" v-else>
				<text class="iconfont icon-shouye2"></text>
			</view>
			商城登录
		</view>
		<!-- #endif -->
		<view class="merchant-msg">
			<img :src="login_logo" />
			<view class="name">
				{{site_name}}
			</view>
		</view>
		<view class="wechat_login">
			<view class="btn-wrapper">
				<!-- #ifdef H5 -->
				<button hover-class="none" @click="wechatLogin" class="bg-theme btn1">微信登录</button>
				<!-- #endif -->
				<!-- #ifdef MP -->
				<template>
					<!--受否配置微信公众号-->
					<button class="bg-theme btn1" v-if="wechat_phone_switch==1 && bindPhone" open-type="getPhoneNumber"
						@getphonenumber="getphonenumber">授权登录</button>
					<button v-else class="bg-theme btn1" @click="getAuthLogin">
						授权登录
					</button>
				</template>
				<!-- #endif -->
			</view>
		</view>
		<view class="protocol" v-if="!canGetPrivacySetting">
			<checkbox-group @click.stop='ChangeIsDefault'>
				<checkbox :class="inAnimation?'trembling':''" @animationend='inAnimation=false'
					:checked="protocol ? true : false" /> <text @click.stop='ChangeIsDefault'>已阅读并同意</text>
				<text class="main-color" @click.stop="privacy(4)">《用户协议》</text>
				与<text class="main-color" @click.stop="privacy(3)">《隐私协议》</text>
			</checkbox-group>
		</view>
		<block>
		<editUserModal :isShow="showUserUpdateModal" @closeEdit="closeEdit" @editSuccess="editSuccess">
		</editUserModal>
		</block>
		<!-- #ifdef MP -->
		<privacyAgreementPopup v-if="canGetPrivacySetting" @onReject="onReject" @onAgree="onAgree">
		</privacyAgreementPopup>
		<!-- #endif -->
	</view>
</template>

<script>
	const app = getApp();
	let statusBarHeight = uni.getSystemInfoSync().statusBarHeight + 'px';

	import editUserModal from '@/components/eidtUserModal/index.vue'
	import privacyAgreementPopup from '@/components/privacyAgreementPopup/index.vue'
	import { commonAuth } from '@/api/public';
	import { EXPIRES_TIME,	USER_INFO } from '@/config/cache';
	import { getUserInfo,loginMpPhone,getAuthType } from '@/api/user.js';
	import Routine from '@/libs/routine';
	import wechat from '@/libs/wechat';
	import auth from '@/libs/wechat.js';
	import { HTTP_REQUEST_URL } from '@/config/app';
	import { isWeixin,configMap } from "@/utils";
	import Cache from '@/utils/cache';
	import { toLogin } from '@/libs/login.js';
	import { mapGetters } from "vuex";
	export default {
		data() {
			return {
				domain: HTTP_REQUEST_URL,
				isUp: false,
				canClose: true,
				phone: '',
				statusBarHeight: statusBarHeight,
				isHome: false,
				isPhoneBox: false,
				protocol: false,
				showUserUpdateModal: false,
				logoUrl: '',
				code: '',
				codeVal: "",
				authKey: '',
				options: '',
				userInfo: {},
				codeNum: 0,
				canUseGetUserProfile: false,
				canGetPrivacySetting: false,
				inAnimation: false,
				colorStatus: uni.getStorageSync('color_status'),
				mp_is_new: this.$Cache.get('MP_VERSION_ISNEW') || false,
				configData: Cache.get('BASIC_CONFIG'),
				bindPhone: false,
				wechat_phone_switch: 0,
			};
		},
		computed:{
			...configMap({login_logo:'',site_name: '',first_avatar_switch: ''}, mapGetters(['isLogin','viewColor'])),
		},
		components: {
			editUserModal,
			privacyAgreementPopup
		},
		watch:{	

		},
		onLoad(options) {
			if (uni.getUserProfile) {
				this.canUseGetUserProfile = true
			}
			// #ifdef MP
			if (wx.getPrivacySetting) {
				this.canGetPrivacySetting = true
			}
			// #endif
			let that = this;
			let pages = getCurrentPages();
			let prePage = pages[pages.length - 2];
			if (prePage && prePage.route == 'pages/order_addcart/order_addcart') {
				this.isHome = true;
			} else {
				this.isHome = false;
			}
		},
		onReady(){
			this.getCode()
		},
		created() {
			// #ifdef MP
			if (wx.getUserProfile) {
				this.canUseGetUserProfile = true
			}
			// this.getCode()
			// #endif
		},
		methods: {
			// 小程序 22.11.8日删除getUserProfile 接口获取用户昵称头像
			getCode() {
				let that = this
				// #ifdef MP
				Routine.getCode().then(code => {
					uni.hideLoading();
					that.code = code;
					let data = {code: code, spread: app.globalData.spid}
					getAuthType(data).then(res => {
						that.bindPhone = res.data.bindPhone
						that.wechat_phone_switch = res.data.wechat_phone_switch
						uni.setStorageSync('auth_token',res.data.key);
					}).catch(err => {
						uni.showToast({
							title: err,
							duration: 2000
						});
					});
				}).catch(e => {
					uni.hideLoading();
					uni.showToast({
						title: '登录失败',
						duration: 2000
					});
				})
				// #endif
				// #ifndef MP
				that.code = 1;
				// #endif
			},
			getAuthLogin() {
				let self = this;
				if(!self.protocol && !self.canUseGetUserProfile){
					return self.$util.Tips({
						title: '请勾选用户协议与隐私政策'
					});
				}
				uni.showLoading({
					title: "正在登录中"
				});
				self.canUseGetUserProfile = false;
				Routine.getUserProfile()
					.then(res => {
						let userInfo = res.userInfo;
						userInfo.code = self.code;
						userInfo.spread = app.globalData.spid; //获取推广人ID
						userInfo.spread_code = app.globalData.code; //获取推广人分享二维码ID
						commonAuth({
							auth: {
							type:'routine',
							auth: userInfo
						}
						}).then(res=>{
							if(res.data.status == 200){
								let time = res.data.result.expires_time - Cache.time();
								self.$store.commit('UPDATE_USERINFO', res.data.result.user);
								self.$store.commit('LOGIN', {token:res.data.result.token, time:time});
								self.$store.commit('SETUID', res.data.result.user.uid);
								Cache.set(EXPIRES_TIME,res.data.result.expires_time,time);
								Cache.set(USER_INFO,res.data.result.user,time);
								if(res.data.result.user.isNew && self.mp_is_new && self.first_avatar_switch==1){
									uni.hideLoading();
									self.showUserUpdateModal = true;
								}else{
									this.$util.Tips({
										title: '授权成功',
										icon: 'success'
									}, {
										tab: 3
									});
								}	
							}else{
								uni.setStorageSync('auth_token',res.data.result.key);
								return uni.navigateTo({
									url:'/pages/users/login/index'
								})
							}
						}).catch(res => {
							uni.hideLoading();
							uni.showToast({
								title: res,
								icon: 'none',
								duration: 2000,
							});
						});
					})
					.catch(res => {
						uni.hideLoading();
					});
			},
			onAgree() {
				this.protocol = true
			},
			ChangeIsDefault(e) {
				this.$set(this, 'protocol', !this.protocol);
			},
			editSuccess() {
				this.showUserUpdateModal = false
			},
			closeEdit() {
				this.showUserUpdateModal = false
				this.$util.Tips({
					title: '登录成功',
					icon: 'success'
				}, {
					tab: 3
				});
			},
			onReject() {
				uni.switchTab({
					url: '/pages/index/index'
				})
			},
			// #ifdef MP
			back() {
				if (this.isLogin) {
					this.$store.commit('LOGIN', {
						token: '',
						time: 0
					});
				}
				uni.navigateBack();
			},
			// #endif
			// #ifndef MP
			back() {
				uni.navigateBack({
					delta: 1
				})
			},
			// #endif
			home() {
				uni.switchTab({
					url: '/pages/index/index'
				})
			},
			// 弹窗关闭
			maskClose(new_user) {
				this.isUp = false;
				// #ifdef MP
				if (new_user) {
					this.showUserUpdateModal = true
				}
				// #endif
			},
			bindPhoneClose(data) {
				this.isPhoneBox = false;
				if (data.isStatus) {
					// #ifdef MP
					this.getUserInfo(data.new_user)
					// #endif
					// #ifndef MP
					this.$util.Tips({
						title: '登录成功',
						icon: 'success'
					}, {
						tab: 3
					});
					// #endif
				}
			},
			// #ifdef MP
			// 小程序获取手机号码
			getphonenumber(e) {
				let that = this;
				if(!that.protocol && !that.canGetPrivacySetting){
					return that.$util.Tips({
						title: '请勾选用户协议与隐私政策'
					});
				}else{
					if(e.detail.iv){
						Routine.getCode().then(code => {
							uni.hideLoading();
							that.code = code;
							loginMpPhone({
								iv:e.detail.iv,
								encryptedData:e.detail.encryptedData,
								code:code,
								auth_token: uni.getStorageSync('auth_token'),
								}).then(({
									data
								}) => {
									that.$Cache.clear('login_back_url');
									that.$store.commit("LOGIN", {
										'token': data.token,
										'time': data.exp
									});
									that.$store.commit("SETUID", data.user.uid);
									that.$store.commit('UPDATE_USERINFO', data.user);

									if (data.user.isNew && that.mp_is_new && that.first_avatar_switch == 1) {
										that.showUserUpdateModal = true;
									} else {
										this.$util.Tips({
											title: '授权成功',
											icon: 'success'
										}, {
											tab: 3
										});
									}	
								})
								.catch(res => {
									that.$util.Tips({
										title: res
									});
								});
						}).catch(e => {
							uni.hideLoading();
							uni.showToast({
								title: '登录失败',
								duration: 2000
							});
						})
					}
				}
			},	
			// #endif
			getPath(url) {
				if (url.indexOf("?") != -1) {
					url = url.split("?")[0];
					console.log(url);
				}
				return url
			},
			/**
			 * 获取个人用户信息
			 */
			getUserInfo(new_user, back_url) {
				let that = this;
				getUserInfo().then(res => {
					uni.hideLoading();
					that.userInfo = res.data;
					that.$store.commit('SETUID', res.data.uid);
					that.$store.commit('UPDATE_USERINFO', res.data);
					if (new_user) {
						this.showUserUpdateModal = true
					} else {
						// #ifdef MP
						that.$util.Tips({
							title: '登录成功',
							icon: 'success'
						}, {
							tab: 3
						});
						// #endif
						// #ifndef MP
						that.$util.Tips({
							title: '登录成功',
							icon: 'success'
						}, {
							tab: 4,
							url: back_url || '/pages/user/index'
						});
						// #endif
					}
				}).catch(err => {
					uni.hideLoading();
					uni.showToast({
						title: err.msg,
						icon: 'none',
						duration: 2000
					});
				});
			},
			privacy(type) {
				uni.navigateTo({
					url: "/pages/users/privacy/index?type=" + type
				})
			},
			// #ifdef H5
			// 公众号登录
			wechatLogin() {
				let that = this;
				if(!this.protocol){
					return this.$util.Tips({
						title: '请勾选用户协议与隐私政策'
					});
				}else{
					auth.oAuth();
				}	
			},
			// #endif
		}
	};
</script>

<style lang="scss">
	page {
		background: #fff;
	}
	::v-deep uni-checkbox .uni-checkbox-input,
	::v-deep checkbox .wx-checkbox-input{
		border-radius: 100%;
		width: 28rpx;
		height: 28rpx;
	}
	::v-deep checkbox .uni-checkbox-input.uni-checkbox-input-checked,
	::v-deep checkbox .wx-checkbox-input.wx-checkbox-input-checked {
		border: 1px solid var(--view-theme)!important;
		background-color: var(--view-theme)!important;
	}
	.wrapper {
		position: relative;
		height: 100vh;
		.bag {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			opacity: .8;
			z-index: -1;
			/* #ifdef H5 */
			z-index: 0;
			/* #endif */
			img {
				width: 100%;
				height: 838rpx;
			}
		}
		.merchant-msg {
			padding-top: 252rpx;
			display: flex;
			justify-content: center;
			align-items: center;
			flex-direction: column;
			z-index: 2;
			/* #ifdef H5 */
			position: relative;
			/* #endif */
			img {
				width: 152rpx;
				height: 152rpx;
				border-radius: 50%;
			}
			.name {
				font-size: 40rpx;
				font-weight: 500;
				color: #333333;
				line-height: 56rpx;
				margin-top: 32rpx;
			}
		}
	}
	.wechat_login {
		margin-top: 96rpx;
		.img image {
			width: 100%;
		}
		.btn-wrapper {
			padding: 0 66rpx;
			button {
				width: 100%;
				height: 86rpx;
				line-height: 86rpx;
				margin-bottom: 40rpx;
				border-radius: 120rpx;
				font-size: 30rpx;
				&.btn1 {
					color: #fff;
					background: var(--view-theme);
				}
				&.btn2 {
					color: #666666;
					border: 1px solid #DDDDDD;
				}
			}
		}
	}
	.title-bar {
		position: relative;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 34rpx;
		font-weight: 500;
		color: #333333;
		line-height: 48rpx;
	}
	.icon {
		position: absolute;
		left: 30rpx;
		top: 0;
		display: flex;
		align-items: center;
		justify-content: center;
		width: 80rpx;
		height: 80rpx;
		image {
			width: 50rpx;
			height: 50rpx;
		}
	}
	.protocol {
		position: fixed;
		bottom: 52rpx;
		left: 0;
		width: 100%;
		margin: 0 auto;
		color: #999999;
		font-size: 24rpx;
		line-height: 22rpx;
		text-align: center;
		bottom: calc(52rpx + constant(safe-area-inset-bottom)); ///兼容 IOS<11.2/
		bottom: calc(52rpx + env(safe-area-inset-bottom)); ///兼容 IOS>11.2/
		.main-color {
			color: var(--view-theme);
		}
		.trembling {
			animation: shake 0.6s;
		}
	}
</style>
