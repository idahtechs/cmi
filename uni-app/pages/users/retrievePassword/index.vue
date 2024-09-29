<template>
	<div class="register absolute" :style="viewColor">
		<!-- <div class="shading">
			<div class="pictrue acea-row row-center-wrapper">
				<image :src="login_logo" v-if="login_logo" />
			</div>
		</div> -->
		<div class="whiteBg" :style="{ 'background-image': `url(${domain}/static/images/logo_bgl.png)`}">
			<view class="login_title">
				<view class="title_h">找回密码</view>
			</view>
			<div class="list">
				<div class="item">
					<input type="text" placeholder="输入手机号码" placeholder-class="placeholder" v-model="account" autocomplete="off" />
					<input type="text" style="height: 0;opacity: 0">
				</div>
				<div class="item">
					<input type="password" placeholder="填写您的新密码" placeholder-class="placeholder" v-model="password" autocomplete="off" />
				</div>
				<div class="item">
					<input type="password" placeholder="再次输入新密码" placeholder-class="placeholder" v-model="confirm_pwd" autocomplete="off" />
				</div>
				<div class="item">
					<input type="text" placeholder="填写验证码" placeholder-class="placeholder" class="codeIput" v-model="captcha" autocomplete="off" />
					<button class="code" :disabled="disabled" :class="disabled === true ? 'on' : ''" @click="handleVerify">
						{{ text }}
					</button>
				</div>
			</div>
			<div class="logon" @click="registerReset">确认</div>
			<div class="tip">
				<text @click="back">立即登录</text>
			</div>
		</div>
		<div class="bottom"></div>
      <Verify @success="success" :captchaType="'blockPuzzle'" :imgSize="{ width: '330px', height: '155px' }" ref="verify"></Verify>
	</div>
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
	const app = getApp();
	import { mapGetters} from "vuex";
	import sendVerifyCode from "@/mixins/SendVerifyCode";
	import {
		registerVerify,
		registerForget,
		getCodeApi,
		getCaptcha
	} from "@/api/user";
	import { validatorDefaultCatch } from "@/utils/dialog";
	import attrs, {
		required,
		alpha_num,
		chs_phone
	} from "@/utils/validate";
	import { configMap } from '@/utils';
	import { HTTP_REQUEST_URL } from '@/config/app';
  import Verify from '@/components/verify/verify.vue';
	export default {
		name: "RetrievePassword",
      components: {
        Verify
		},
		mixins: [sendVerifyCode],
		data: function() {
			return {
				account: "",
				password: "",
				confirm_pwd: "",
				captcha: "",
				codeKey: "",
				codeUrl: "",
				codeVal: "",
				isShowCode: false,
				domain: HTTP_REQUEST_URL,
			};
		},
		computed: configMap(['login_logo'], mapGetters(['viewColor'])),
		onReady() {
		},
		mounted: function() {
		},
		methods: {
			back() {
				uni.navigateBack();
			},
			again() {
				this.codeUrl =	VUE_APP_API_URL + "/captcha?" + this.keyCode + Date.parse(new Date());
			},

			async code(data) {
				let that = this;
				if (!that.account) return that.$util.Tips({
					title: '请填写手机号码'
				});
				if (!/^1(3|4|5|7|8|9|6)\d{9}$/i.test(that.account)) return that.$util.Tips({
					title: '请输入正确的手机号码'
				});
				await registerVerify({
						phone: that.account,
						type: 'change_pwd',
            captchaType: 'blockPuzzle',
						captchaVerification: data.captchaVerification
					})
					.then(res => {
						that.$util.Tips({
							title: res.message
						});
						that.sendCode();
					}).catch(res => {
						that.$util.Tips({
							title: res
						});
					});

			},

			getcaptcha() {
				let that = this
				getCaptcha().then(data => {
					that.codeUrl = data.data.captcha; //图片路径
					that.codeVal = data.data.code; //图片验证码
					that.codeKey = data.data.key //图片验证码key
				})
				that.isShowCode = true;
			},
			async registerReset() {
				var that = this;
				if (!that.account) return that.$util.Tips({
					title: '请填写手机号码'
				});
				if (!/^1(3|4|5|7|8|9|6)\d{9}$/i.test(that.account)) return that.$util.Tips({
					title: '请输入正确的手机号码'
				});
				if (that.password == '123456') return that.$util.Tips({
					title: '您输入的密码过于简单'
				});
				if (that.password != that.confirm_pwd) return that.$util.Tips({
					title: '两次密码不一致'
				});
				if (!that.captcha) return that.$util.Tips({
					title: '请填写验证码'
				});
				registerForget({
						phone: that.account,
						sms_code: that.captcha,
						pwd: that.password,
						confirm_pwd: that.confirm_pwd
					})
					.then(res => {
						that.$util.Tips({
							title: res.message
						}, {
							tab: 3
						})
					})
					.catch(res => {
						that.$util.Tips({
							title: res
						})
					});
			},
        success(data) {
				this.$refs.verify.hide();
				this.code(data);
			},
			handleVerify() {
				this.$refs.verify.show();
			}
		}
	};
</script>
<style lang="scss" scoped>
	.register{
		background: #ffffff;
		height: 100vh;
	}
	.register .list .item .code {
		color: var(--view-theme);
	}
	.whiteBg .logon {
		display: flex;
		align-items: center;
		justify-content: center;
		width: 100%;
		height: 86rpx;
		margin-top: 48rpx;
		background-color:var(--view-theme);
		border-radius: 120rpx;
		color: #FFFFFF;
		font-size: 30rpx;
	}
	.whiteBg{
		background-repeat: no-repeat;
		background-size: 100% auto;
	}
</style>
