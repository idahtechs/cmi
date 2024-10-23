<template>
  <c-page bg-theme="none">
    <template #nav>
      <custom-nav :show-title="false" bg-color="transparent"/>
    </template>

    <image src="/static/images/bg-index.png" class="bg" mode="widthFix" />

    <view class="entries">
      <view class="entry" v-for="entry in entries" :key="entry.title">
        <image class="entry-cover" :src="entry.cover" mode="aspectFill" />
        <view class="entry-info">
          <view class="fs-16 font-bold">{{ entry.title }}</view>
          <view class="fs-16">{{ entry.desc }}</view>
          <button class="cmi-btn cmi-btn-small entry-btn" type="primary" @click="goTo(entry)">{{ entry.buttonText }}</button>
        </view>
      </view>
    </view>
  </c-page>
</template>

<script>
export default {
  data() {
    return {
      entries: [
        {
          title: '视频变种',
          desc: '创意内容生成',
          cover: '/static/images/cover-bzgc.png',
          url: '/pages/ai/video_script_extract/index',
          buttonText: '开始写作'
        }
      ]
    }
  },

  onShareAppMessage() {
    return {
      title: '创作鬼才'
    }
  },

  methods: {
    goTo(entry) {
      this.$util.gotoPage({ url: entry.url })
    }
  }
}
</script>

<style lang="scss" scoped>
.bg {
  position: absolute !important;
  left: 0; 
  top: 0;
  width: 100%;
}

.entries {
  display: flex;
  flex-direction: column;
  gap: 32rpx;
  padding: 226rpx 40rpx 40rpx;
}

.entry {
  position: relative;
  z-index: 1;
  display: flex;
  min-height: 338rpx;
  border-radius: 24rpx;
  overflow: hidden;
  background-color: white;
  box-shadow: 0px 4px 4px 0px #2196F30F;

  &-cover {
    width: 300rpx;
    height: auto;
  }

  &-info {
    padding: 30rpx 40rpx 30rpx 24rpx;
    flex: auto;
    display: flex;
    gap: 22rpx;
    flex-direction: column;
  }

  &-btn {
    margin-top: auto;
  }
}
</style>