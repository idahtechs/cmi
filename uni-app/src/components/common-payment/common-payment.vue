<template>
	<view>
		<view class="payment" :class="showPaymentWidget ? 'on' : ''">
			<view class="title acea-row row-center-wrapper">
				选择付款方式<text class="iconfont icon-guanbi" @click='emitClose'></text>
			</view>
			<view class="item acea-row row-between-wrapper" @click='goPay(item)' v-for="item in payModesToSelect"
				:key="item.value">
				<view class="left acea-row row-between-wrapper">
					<view class="iconfont" :class="item.icon"></view>
					<view class="text">
						<view class="name">{{ item.name }}</view>
						<view class="info" v-if="item.number">
							{{ item.title }} <span class="money">￥{{ item.number }}</span>
						</view>
						<view class="info" v-else>{{ item.title }}</view>
					</view>
				</view>
				<view class="iconfont icon-xiangyou"></view>
			</view>
		</view>
		<view class="mask" ref="close" @click='emitClose' v-if="showPaymentWidget"></view>
	</view>
</template>

<script>
import { getUserInfo } from '@/api/user.js';

// 公共的支付选择组件, 选择支付方式后由父组件主动调用本组件的支付方法
// 对比payment组件, 本组件将创建订单的相关逻辑进行解藕, 创建订单逻辑交给父组件处理, 本组件仅处理支付相关逻辑,
// 使用例子:
// template:
//    <common-payment
//      ref="payment"
//      :enabledPayModes="['wechat', 'balance']"
//      :showPaymentWidget="showPaymentWidget"
//      @prepareToPay="prepareToPay"
//      @close="showPaymentWidget = false"
//    />
//
// script:
//    export default {
//      data() {
//        return {
//          showPaymentWidget: false
//        }
//      },
//      methods: {
//        prepareToPay({ payType, payMode }) {
//          this.$refs.payment.pay({
//            payMode,
//            paymentAmount: this.paymentAmount,
//            createOrderPromiseFn: () => createServicePackageOrder({
//              mer_id: this.merId,
//              specification_id: this.selectedSpecification?.id,
//              pay_type: payType,
//              cart_num: this.amount,
//              // #ifdef H5
//              return_url: '/pages/users/order_list/index',
//              // #endif
//            }),
//            success: () => {},
//            fail: () => {}
//          })
//        }
//      }
//    }

export default {
	props: {
		enabledPayModes: {
			type: Array,
			default: function () {
				return [] // ['wechat', 'alipay', 'balance']
			}
		},
		showPaymentWidget: {
			type: Boolean,
			default: false,
		}
	},
	data() {
		return {
			userBalance: 0
		}
	},

	computed: {
		payModesToSelect({ enabledPayModes, userBalance }) {
			const allPayModes = [
				{
					name: "微信支付",
					icon: "icon-weixinzhifu",
					value: 'wechat',
					title: '微信快捷支付',
					payStatus: 1
				},
				{
					name: "支付宝支付",
					icon: "icon-zhifubao",
					value: 'alipay',
					title: '支付宝支付',
					payStatus: this.$store.getters.globalData.alipay_open
				},
				{
					name: "余额支付",
					icon: "icon-yuezhifu",
					value: 'balance',
					title: '可用余额:',
					number: userBalance,
					payStatus: this.$store.getters.globalData.yue_pay_status
				}
			]

			return enabledPayModes.map(name => {
				return allPayModes.find(payMode => payMode.value === name)
			}).filter(payMode => !!payMode)
		}
	},

	watch: {
		showPaymentWidget(yes) {
			if (yes && this.enabledPayModes.includes('balance')) {
				this.refreshUserBalance()
			}
		}
	},

	methods: {
		refreshUserBalance() {
			return getUserInfo().then(res => {
				this.userBalance = res.data.now_money
			})
		},

		emitClose: function () {
			this.$emit('close')
		},

		goPay: function (payMode) {
			const payType = this.getType(payMode.value)

			// 选择支付方式后由父组件创建订单并调用本组件的订单处理
			this.$emit('prepareToPay', { payType, payMode })
		},

		getType(paytype) {
			let type = paytype
			if (paytype == 'wechat') {
				// #ifdef H5
				type = this.$wechat.isWeixin() ? 'weixin' : 'h5'
				// #endif
				// #ifdef APP-PLUS
				type = 'weixin'
				// #endif
				// #ifdef MP
				type = 'routine'
				// #endif
			} else if (paytype == 'alipay') {
				// #ifndef MP
				type = 'alipay'
				// #endif
				// #ifdef MP
				type = 'alipayQr'
				// #endif
			}
			return type
		},

		// 支付, 通过父组件显式调用
		pay({
			// 支付方式对象
			payMode,

			// 支付总价
			paymentAmount,

			// 创建订单的函数, 无参数, 必须返回promise
			createOrderPromiseFn,

			// 成功回调
			success = () => { },

			// 失败回调
			fail = () => { }
		}) {
			const {
				$util,
				$wechat,
				emitClose
			} = this
			const number = payMode.number || 0
			const paytype = payMode.value
			const type = this.getType(paytype)

			const paymentSuccessCallback = (res) => {
				console.log('pay success: ', res)
				uni.hideLoading()
				emitClose()
				$util.Tips({
					title: '支付成功',
					icon: 'success'
				})
				setTimeout(success, 1500)
			}

			const paymentFailCallback = (err, isCancel) => {
				console.log('pay fail: ', err)
				uni.hideLoading()
				fail(err)
				emitClose()
				return $util.Tips({
					title: isCancel ? '取消支付' : '支付失败'
				})
			}

			if (type == 'balance' && parseFloat(number) < parseFloat(paymentAmount)) {
				return $util.Tips({
					title: '余额不足！'
				})
			}

			uni.showLoading({
				title: '支付中'
			})

			// 创建订单, 然后自动发起支付
			createOrderPromiseFn().then(res => {
				console.log('create order: ', res)

				const status = res.data.status
				const orderId = res.data.result.order_id
				const jsConfig = res.data.result.config
				const callback_key = res.data.result.pay_key

				switch (status) {
					case 'ORDER_EXIST':
					case 'EXTEND_ORDER':
					case 'PAY_ERROR':
					case 'error':
						uni.hideLoading()
						fail(res)
						emitClose()
						return $util.Tips({
							title: res.message
						})
					case 'success':
						return paymentSuccessCallback()
					case 'alipay':
					case 'alipayQr':
						uni.hideLoading()
						emitClose()
						return uni.navigateTo({
							url: '/pages/order_pay_back/index?keyCode=' + callback_key + '&url=' + jsConfig,
						})

					// #ifndef MP
					case "wechat":
					case "weixin":
					case "weixinApp":
						jsConfig.timeStamp = jsConfig.timestamp

						// #ifndef APP-PLUS
						$wechat.pay(jsConfig).then(res => {
							paymentSuccessCallback(res)
						}).catch(res => {
							const isCancel = res.errMsg == 'chooseWXPay:cancel'
							paymentFailCallback(res, isCancel)
						})
						// #endif

						// #ifdef APP-PLUS
						let mp_pay_name = ''
						if (uni.requestOrderPayment) {
							mp_pay_name = 'requestOrderPayment'
						} else {
							mp_pay_name = 'requestPayment'
						}
						uni[mp_pay_name]({
							provider: 'wxpay',
							orderInfo: jsConfig,
							success: (e) => {
								paymentSuccessCallback(e)
							},
							fail: (e) => {
								paymentFailCallback(e)
							}
						})
						// #endif

						break
					// #endif

					// #ifdef MP
					case "routine":
						jsConfig.timeStamp = jsConfig.timestamp
						let mp_pay_name = ''
						if (uni.requestOrderPayment) {
							mp_pay_name = 'requestOrderPayment'
						} else {
							mp_pay_name = 'requestPayment'
						}
						uni[mp_pay_name]({
							...jsConfig,
							success: function (res) {
								paymentSuccessCallback(res)
							},
							fail: function (e) {
								const isCancel = e.errMsg === 'requestPayment:fail cancel' || e.errMsg === 'requestOrderPayment:fail cancel'
								paymentFailCallback(e, isCancel)
							}
						})
						break
					// #endif

					case "balance":
						uni.hideLoading()
						emitClose()
						//余额不足
						return $util.Tips({
							title: res.message
						})

					// #ifdef H5
					case 'h5':
						let host = window.location.protocol + "//" + window.location.host
						let url = `${host}/pages/order_pay_status/index?order_id=${orderId}`
						let eUrl = encodeURIComponent(url)
						let jsurl = jsConfig.mweb_url || jsConfig.h5_url
						let locations = `${jsurl}&redirect_url=${eUrl}`
						setTimeout(() => {
							location.href = locations
						}, 100)
						break
					// #endif

					// #ifdef APP-PLUS
					case 'alipayApp':
						uni.requestPayment({
							provider: 'alipay',
							orderInfo: jsConfig,
							success: (e) => {
								paymentSuccessCallback(e)
							},
							fail: (e) => {
								paymentFailCallback(e)
							}
						})
						break
					// #endif
				}
			}).catch(err => {
				uni.hideLoading()
				return $util.Tips({
					title: err
				})
			})
		}
	}
}
</script>

<style scoped lang="scss">
.payment {
	position: fixed;
	bottom: 0;
	left: 0;
	width: 100%;
	max-height: 600rpx;
	border-radius: 16rpx 16rpx 0 0;
	background-color: #fff;
	padding-bottom: 60rpx;
	z-index: 99;
	transition: all 0.3s cubic-bezier(0.25, 0.5, 0.5, 0.9);
	transform: translate3d(0, 100%, 0);
}

.payment.on {
	transform: translate3d(0, 0, 0);
}

.payment .title {
	text-align: center;
	height: 123rpx;
	font-size: 32rpx;
	color: #282828;
	font-weight: bold;
	padding-right: 30rpx;
	margin-left: 30rpx;
	position: relative;
	border-bottom: 1px solid #eee;
}

.payment .title .iconfont {
	position: absolute;
	right: 30rpx;
	top: 50%;
	transform: translateY(-50%);
	font-size: 43rpx;
	color: #8a8a8a;
	font-weight: normal;
}

.payment .item {
	border-bottom: 1px solid #eee;
	height: 130rpx;
	margin-left: 30rpx;
	padding-right: 30rpx;
}

.payment .item .left {
	width: 610rpx;
}

.payment .item .left .text {
	width: 540rpx;
}

.payment .item .left .text .name {
	font-size: 32rpx;
	color: #282828;
}

.payment .item .left .text .info {
	font-size: 24rpx;
	color: #999;
}

.payment .item .left .text .info .money {
	color: #ff9900;
}

.payment .item .left .iconfont {
	font-size: 45rpx;
	color: #09bb07;
}

.payment .item .left .iconfont.icon-zhifubao {
	color: #00aaea;
}

.payment .item .left .iconfont.icon-yuezhifu {
	color: #ff9900;
}

.payment .item .left .iconfont.icon-yuezhifu1 {
	color: #eb6623;
}

.payment .item .iconfont {
	font-size: 0.3rpx;
	color: #999;
}
</style>
