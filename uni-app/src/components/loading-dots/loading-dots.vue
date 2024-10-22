<template>
	<view class="loading-dots">
		<view v-for="(dot, index) in dots" :key="dot.id" class="dot" :style="{'animation-delay': index * 0.5 + 's', 'animation-duration': dots.length * 0.5 + 's' }"></view>
	</view>
</template>

<script>
export default {
	props: {
		dotCount: {
			type: Number,
			default: 6
		}
	},

	computed: {
		dots({ dotCount }) {
			return new Array(dotCount).fill(1).map((_, idx) => ({ id: idx }))
		}
	}
}
</script>

<style lang="scss" scoped>
@keyframes scale {
	0% {
		transform: scale(1.333333);
		background-color: var(--view-theme);
	}
	80% {
		transform: scale(1);
		background-color: #ccc;
	}
}
.loading-dots {
	display: inline-flex;
	align-items: center;
	gap: 16rpx;

	.dot {
		width: 12rpx;
		height: 12rpx;
		border-radius: 50%;
		animation: scale 3s infinite;
		background-color: #ccc;
		transform: scale(1);
	}
}
</style>
