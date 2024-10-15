<template>
  <view class="ai-generated-result">
    <view class="ai-generated-result-title">
      {{ title }}
    </view>
    <view class="ai-generated-result-content">{{ result.content }}</view>
    <view class="ai-generated-result-actions">
      <button type="primary" plain @click="handlePolishRequest">润色</button>
      <copy-button button-class="cmi-btn" :text="result.content">复制全文</copy-button>
    </view>
  </view>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      default: '生成结果'
    },
    result: {
      type: String,
      default: ''
    }
  },
  methods: {
    handlePolishRequest() {
      this.$emit('polishRequest')
    }
  }
}
</script>

<sty