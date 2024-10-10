<template>
	<view :class="pageClass" :style="pageStyle" v-show="completed">
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
    loginRequired: Boolean,
    flex: Boolean,
    bgTheme: {
      typeof: String,
      default: 'light'
    }
  },

	data() {
		return {
      completed: false
		};
	},

  computed: {
    ...mapGetters(['isLogin', 'viewColor']),

    pageClass() {
      return [
        'c-page',
        this.flex && 'c-page-flex',
      ].filter(Boolean).join(' ')
    },

    pageStyle({ viewColor, bgTheme }) {
      const bgThemes = {
        'none': '--page-bg: transparent',
        'light': '--page-bg: #F5FCFF'
      }

      const bg = bgThemes[bgTheme]

      const styles = [
        bg
      ].filter(Boolean).join(';')
      return `${viewColor};${styles}`
    }
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
    background-color: var(--page-bg, transparent);

    &-flex {
      display: flex;
      flex-direction: column;
      height: calc(100vh - var(--window-bottom));

      .c-page-body {
        flex: auto;
        min-height: 1px;
        overflow: auto;
        -webkit-overflow-scrolling: touch;
      }
    }
  }
</style>
