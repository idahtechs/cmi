<template>
  <c-page flex login-required @ready="onPageReady" :loading="loading">
    <template #nav>
      <custom-nav bg-color="transparent"/>
    </template>

    <view class="prompt-templates">
      <navigator
        class="prompt-add color-view-theme flex flex-column align-items-center justify-content-center fs-12 br-20"
        key="add"
        url="/pages/ai/user_prompt_templates/edit"
        style="min-height: 340rpx;"
      >
        <text class="fs-20">+</text>
        创建自定义模版
      </navigator>
      <view class="prompt-template" v-for="template in templates" :key="template.key">
        <view class="prompt-template-title text-ellipsis">{{ template.name }}</view>
        <view class="prompt-template-content">{{ template.prompt }}</view>
        <button class="cmi-btn cmi-btn-xs" type="primary" @click="handleUseTemplate(template)">使用模版</button>
      </view> 
    </view>
  </c-page>
</template>

<script>
import createGlobalEventHandlersMixin from '@/mixins/createGlobalEventHandlersMixin'
import { getConfigGroupWithCache } from '@/api/public'
import { getUserPromptTemplates } from '@/api/user'

export default {
  mixins: [
    createGlobalEventHandlersMixin({
      'user_prompt_templates_updated': function() { this.loadUserPromptTemplates() }
    }),
  ],

  data() {
    return {
      promptTemplates: [],
      userPromptTemplates: [],
      ticket: '',

      loading: true
    }
  },

  computed: {
    templates() {
      return [...this.userPromptTemplates, ...this.promptTemplates].map(template => {
        return {
          ...template,
          key: template.group_data_id ? `common_${template.group_data_id}` : `user_${template.prompt_template_id}`,
          name: template.name || template.title
        }
      })
    }
  },

  onLoad({ ticket }) {
    this.ticket = ticket
  },

  methods: {
    onPageReady() {
      Promise.all([
        this.loadPromptTemplates().finally(() => { }),
        this.loadUserPromptTemplates().finally(() => { }),
      ]).then(() => {
        this.loading = false
      })
    },

    loadPromptTemplates() {
      return getConfigGroupWithCache('prompt_templates').then(res => {
        this.promptTemplates = res.data
      })
    },

    loadUserPromptTemplates() {
      return getUserPromptTemplates().then(res => {
        this.userPromptTemplates = res.data.list
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

.prompt-add {
  border: 1px dashed var(--view-theme);
  min-height: 280rpx;
}

.prompt-template {
  padding: 40rpx;
  border-radius: 40rpx;
  background-color: white;
  min-width: 1px;

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