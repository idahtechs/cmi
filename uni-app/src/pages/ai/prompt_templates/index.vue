<template>
  <c-page flex login-required>
    <template #nav>
      <custom-nav bg-color="transparent"/>
    </template>

    <view class="prompt-templates">
      <view class="prompt-template" v-for="template in templates" :key="template.id">
        <view class="prompt-template-title">{{ template.title }}</view>
        <view class="prompt-template-content">{{ template.content }}</view>
        <button class="cmi-btn cmi-btn-xs" type="primary" @click="handleUseTemplate(template)">使用模版</button>
      </view> 
    </view>
  </c-page>
</template>

<script>
export default {
  data() {
    return {
      templates: [
        ...new Array(10).fill(0).map((_, index) => {
          return {
            id: index + 1,
            title: `模版${index + 1}`,
            content: `模板内容${index + 1}`
          }
        })
      ]
    }
  },

  methods: {
    handleUseTemplate(template) {
      uni.showModal({
        title: '是否使用模版覆盖现有提示词？',
        cancelText: '否',
        confirmText: '是',
        success: (res) => {
          if (res.confirm) {
            uni.$emit('use_prompt_template', template.content)
            uni.navigateBack()
          }
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.prompt-templates {
  padding: 68rpx 40rpx;
  display: grid;
  grid-template-columns: 1fr 1fr;
  grid-gap: 24rpx 30rpx;
}

.prompt-template {
  padding: 40rpx;
  border-radius: 40rpx;
  background-color: white;

  &-title {
    margin-bottom: 32rpx;
    font-size: 34rpx;
    font-weight: bold;
  }

  &-content {
    margin-bottom: 32rpx;
    font-size: 24rpx;
    height: 102rpx;
    line-height: 34rpx;
    overflow: hidden;
    text-overflow: ellipsis;
  }
}

</style>