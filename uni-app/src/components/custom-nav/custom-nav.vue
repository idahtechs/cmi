<template>
  <view :class="className" :style="styles">
    <!-- #ifdef MP || APP-PLUS -->
    <view :style="{ height: statusBarHeight }"></view>
    <view class="header">
      <view :class="menuContainerClassNames">
        <view v-for="button in buttons" :key="button.key" :class="button.icon" @click="onIconClick(button)"></view>
      </view>
			<view class="custom-nav-title">
				<slot>{{ showTitle ? navTitle : '' }}</slot>
			</view>
    </view>
    <!-- #endif -->
    <!-- #ifdef H5 -->
    <view class="header" >
      <view :class="menuContainerClassNames">
				<view v-for="button in buttons" :key="button.key" :class="button.icon" @click="onIconClick(button)"></view>
      </view>
      <view class="custom-nav-title">
				<slot>{{ showTitle ? navTitle : '' }}</slot>
			</view>
    </view>
    <!-- #endif -->
  </view>
</template>

<script>
export default {
  props: [
		'showTitle',
		'iconColor',
		'textColor',
		'bgColor',
		'hideBackBtn',
		'hideHomeBtn',
		'fixed',
	],

	props: {
		showTitle: {
			type: Boolean,
			default: true
		},
		iconColor: {
			type: String,
			default: '#000'
		},
		textColor: {
			type: String,
			default: '#000'
		},
		bgColor: {
			type: String,
			default: '#fff'
		},
		hideBackBtn: {
			type: Boolean,
			default: false
		},
		hideHomeBtn: {
			type: Boolean,
			default: true
		},
		fixed: {
			type: Boolean,
			default: false
		},
	},

	data() {
		return {
			canNavigateBack: true,
			currentRoute: ''
		}
	},

	computed: {
		statusBarHeight() {
			return uni.getWindowInfo().statusBarHeight + 'px'
		},

		buttons({ hideBackBtn, hideHomeBtn, canNavigateBack }) {
			const allButtons = [
				{ key: 'back', icon: 'iconfont icon-zuo-miaosha', visible: !hideBackBtn && canNavigateBack },
				{ key: 'home', icon: 'iconfont icon-shouye4', visible: !hideHomeBtn },
			];

			return allButtons.filter(btn => btn.visible )
		},

		menuContainerClassNames({ buttons }) {
			return `head-menu ${buttons.length <= 1 ? 'no-background' : ''}`
		},

		className() {
			return [
				'custom-nav',
				this.fixed ? 'fixed' : '',
			].join(' ')
		},

		styles() {
			const styles = []
			if (this.textColor) {
				styles.push(`--custom-nav-text-color: ${this.textColor}`)
			}
			if (this.iconColor) {
				styles.push(`--custom-nav-icon-color: ${this.iconColor}`)
			}
			if (this.bgColor) {
				styles.push(`--custom-nav-bg-color: ${this.bgColor}`)
			}

			return styles.join(';')
		},

		navTitle({ currentRoute }) {
			const pageMap = this.$util.getPageMap()
			return currentRoute ? pageMap[`/${currentRoute}`]?.style?.navigationBarTitleText : ''
		}
	},

	mounted() {
		this.canNavigateBack = getCurrentPages().length > 1
		this.currentRoute = this.$util.getCurrentPage().route
	},

  methods: {
		onIconClick(button) {
			switch (button.key) {
				case 'back': this.goback(); break;
				case 'home': this.goHome(); break;
				default:
			}
		},

    goback() {
			if (getCurrentPages().length > 1) {
				uni.navigateBack()
			}
    },

    goHome() {
      uni.switchTab({
        url: '/pages/index/index'
      });
    }
  }
}
</script>

<style lang="scss" scoped>
.custom-nav {
	--custom-nav-text-color: #000;
	--custom-nav-icon-color: #000;
	--custom-nav-bg-color: #fff;

	position: sticky;
	top: 0;
	z-index: 99;
	background-color: var(--custom-nav-bg-color);

	&.fixed {
		position: fixed;
		width: 100%;
		top: 0;
		z-index: 99;
	}

	.custom-nav-title {
		position: absolute;
		left: 200rpx;
		right: 200rpx;
		text-align: center;
		color: var(--custom-nav-icon-color);
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}
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
			display: flex;
			align-items: center;
			height: 27px;
			background: rgba(0, 0, 0, 0.25);
			border-radius: 13px;

			&.no-background {
				background: none;

				.iconfont:first-child {
					padding-left: 0;
				}
			}

			.icon-xiangzuo {
				font-size: 32rpx;
			}
			.iconfont {
				-webkit-box-flex: 1;
				-webkit-flex: 1;
				flex: 1;
				text-align: center;
				color: #fff;
				box-sizing: border-box;
				padding: 0 16rpx;
				border-left: 1px solid rgba(255,255,255,.3);
				color: var(--custom-nav-icon-color);

				&:first-child {
					border: none;
				}
			}
		}
	}
</style>
