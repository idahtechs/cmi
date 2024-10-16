<template>
  <view class="relative mb-20" @touchstart="handleTouchStart" @touchmove="handleTouchMove">
    <view class="record-item relative z-1 bg-white px-12 py-10 br-12 cursor-pointer" :class="showActions ? 'active' : ''" @click="$emit('click', $event)">
      <view class="mb-10 overflow-hidden record-item-content" style="max-height: 120rpx;">{{ contentText }}</view>
      <view class="flex justify-content-between color-muted">
        <text>{{ record.updateTime }}</text>
      </view>
    </view>
    <view class="absolute top-0 right-0 w-full h-full flex align-items-center justify-content-end gap-9 pr-12">
      <view class="flex align-items-center justify-content-center w-28 h-28 iconfont icon-shanchu1 fs-16 br-rounded color-white bg-danger" @click="handleDelete"></view>
    </view>
  </view>
</template>

<script>
export default {
  props: {
    record: {
      type: Object,
      default: () => ({})
    }
  },

  data() {
    return {
      showActions: false
    }
  },

  computed: {
    contentText() {
      return this.record.content ? this.$util.markdownToPlainText(this.record.content) : ''
    }
  },

  methods: {
    handleDelete() {
      this.$emit('delete', this.record)
    },
    handleTouchStart(e) {
      this._touchStartX = e.touches[0].clientX
      this._touchStartY = e.touches[0].clientY
    },

    handleTouchMove(e) {
      const distanceX = e.changedTouches[0].clientX - this._touchStartX
      const distanceY = e.changedTouches[0].clientY - this._touchStartY

      if (Math.abs(distanceY) > 30) {
        return
      }

      if (distanceX < -20) {
        this.showActions = true
      } else if (distanceX > 20) {
        this.showActions = false
      }
    },
  }
}
</script>

<style lang="scss" scoped> 
  .record-item {
    transition: transform 0.3s;

    &.active {
      transform: translateX(-140rpx);
    }

    &-content {
      display: -webkit-box;
      -webkit-box-orient: vertical;
      -webkit-line-clamp: 3;
      overflow: hidden;
    }
  }
</style>