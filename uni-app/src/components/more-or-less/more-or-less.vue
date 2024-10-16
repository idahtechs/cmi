<template>
  <view class="mol" :class="className">
    <view class="mol-container" :style="containerStyles">
      <view class="mol-content">
        <slot></slot>
      </view>
    </view>
    <view class="mol-action" v-if="enable" @click="showMore = !showMore">
      <text class="ml-4 iconfont icon-jinru1"></text>
      {{ showMore ? lessText : moreText }}
    </view>
  </view>
</template>

<script>
  export default {
    props: {
      threshold: {
        type: Number,
        default: 100
      },

      moreText: {
        type: String,
        default: '查看更多'
      },

      lessText: {
        type: String,
        default: '收起'
      },

      disabled: {
        type: Boolean,
        default: false
      },

      // 触发内容长度检测的对象，只要变化，则触发重新检测（变相检测方法，因小程序不支持MutationObserver）
      triggerValue: {
        type: [ Number, String, Boolean, Object, Array ],
        default: () => ''
      }
    },

    data() {
      return {
        showMore: false,
        contentHeight: 0,
        inited: false
      }
    },

    computed: {
      enable({ disabled, threshold, contentHeight }) {
        return !disabled && contentHeight > threshold
      },

      className({ showMore, enable, inited }) {
        const classes = [
          showMore ? '' : 'mol-less',
          enable ? '' : 'mol-disabled',
          inited ? 'mol-inited' : ''
        ]
        return classes.filter(Boolean).join(' ')
      },

      containerStyles({ enable, showMore, threshold, contentHeight }) {
        if (enable) {
          return `height: ${showMore ? contentHeight: threshold}px`
        }

        return ''
      }
    },

    watch: {
      triggerValue() {
        this._debounceDetect()
      },

      enable(enable) {
        if (enable) {
          this._debounceDetect()
        }
      }
    },
    
    created() {
      this._debounceDetect = this.$util.debounce(this.detect, 50)
      this._debounceDetect()
    },

    methods: {
      detect() {
        const query = this.createSelectorQuery()
        query.select('.mol-content').boundingClientRect()
        query.exec(([rect]) => {
          if (rect) {
            this.contentHeight = rect.height
          }

          if (!this.inited) {
            this.inited = true
          }
        })
      }
    }
  }
</script>

<style lang="scss" scoped>
  .mol {
    position: relative;
    visibility: hidden;

    &-inited {
      visibility: visible;
    }

    &-container {
      overflow: hidden;
      transition: height 0.3s ease-in-out;
    }

    &-action {
      text-align: center;
      padding: 16rpx;
      color: var(--view-theme);
      font-size: 28rpx;

      .iconfont {
        display: inline-block;
        margin-right: 8rpx;
        font-size: 26rpx;
        transform: rotate(-90deg);
      }
    }

    &-less {
      &:not(.mol-disabled) .mol-container .mol-content {
        pointer-events: none;
      }
      .mol-action {
        .iconfont {
          transform: rotate(90deg);
        }
      }
    }
  }
</style>

