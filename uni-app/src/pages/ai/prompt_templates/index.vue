<template>
  <c-page flex login-required>
    <template #nav>
      <custom-nav bg-color="transparent"/>
    </template>

    <view class="prompt-templates">
      <view class="prompt-template" v-for="template in templates" :key="template.group_data_id">
        <view class="prompt-template-title">{{ template.name }}</view>
        <view class="prompt-template-content">{{ template.prompt }}</view>
        <button class="cmi-btn cmi-btn-xs" type="primary" @click="handleUseTemplate(template)">使用模版</button>
      </view> 
    </view>
  </c-page>
</template>

<script>
import { getConfigGroupWithCache } from '@/api/public'

export default {
  data() {
    return {
      templates: [],
      ticket: ''
    }
  },

  onLoad({ ticket }) {
    this.ticket = ticket
    this.loadPromptTemplates()
  },

  methods: {
    loadPromptTemplates() {
      getConfigGroupWithCache('prompt_templates').then(res => {
        this.templates = res.data
      })
    },

    handleUseTemplate(template) {
      uni.showModal({
        title: '是否使用模版覆盖现有提示词？',
        cancelText: '否',
        confirmText: '是',
        success: (res) => {
          if (res.confirm) {
            uni.$emit('use_prompt_template', { prompt: template.prompt, ticket: this.ticket })
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