<template>
  <view class="relative mb-20" @touchstart="handleTouchStart" @touchmove="handleTouchMove">
    <view class="record-item relative z-1 bg-white px-12 py-10 br-12" :class="showActions ? 'active' : ''" @click="$emit('click', $event)">
      <view class="mb-10 overflow-hidden" style="max-height: 120rpx;">{{ record.content }}</view>
      <view class="flex justify-content-between color-muted">
        <text>{{ record.updatedAt }}</text>
        <text v-if="record.source">脚本来源：{{ record.source }}</text>
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
  }
</style>