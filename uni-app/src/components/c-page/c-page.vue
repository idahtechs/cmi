<template>
	<view class="c-page" :style="viewColor" v-show="completed">
		<slot name="nav"></slot>
		<view class="c-page-body">
      <slot></slot>
    </view>
  
    <custom-tabbar />
	</view>
</template>

<script>
import { mapGetters } from 'vuex'

// 通用页面layout组件
export default {

  props: {
    loginRequired: Boolean
  },

	data() {
		return {
      completed: false
		};
	},

  computed: {
    ...mapGetters(['isLogin', 'viewColor']),
  },

	watch: {
    isLogin: {
      handler(isLogin) {
        // 防止过早跳转到login页面导致无法获取当前页面路径
        setTimeout(() => {
          if (this.loginRequired && !isLogin) {
            return this.$util.ensureLogin()
          }
          
          this.completed = true
          this.$emit('ready')
        })
      },
      immediate: true
    }
  },

	methods: {
		
	},
};
</script>
<style lang="scss" scoped>
  .c-page {
    position: relative;
    min-height: calc(100vh - var(--window-bottom));
  }
</style>
