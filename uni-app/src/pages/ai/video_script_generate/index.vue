<template>
  <c-page login-required flex>
    <template #nav>
      <custom-nav bg-color="transparent"/>
    </template>

    <!-- 表单 -->
    <view class="cmi-form">
      <view class="cmi-form-item">
        <view class="cmi-form-label relative">
          脚本内容
          <view class="cmi-link absolute right-0" @click="handleVideoExtract" v-if="isNewRecord">
            <image src="/static/icons/swap-horizontal.svg" class="w-24 h-24 align-middle"/>
            提取视频脚本
          </view>
          <!-- TODO: -->
          <text class="color-muted absolute right-0" v-else>脚本来源：抖音</text>
        </view>

        <textarea v-if="isNewRecord" class="cmi-input" placeholder="请输入脚本内容" :value="record.referenceScript" :maxlength="-1" auto-height style="min-height: 380rpx;" />
        <view  v-else  class="collapsable-container">
          <more-or-less :threshold="32" :trigger-value="record.referenceScript" more-text="展开并编辑" >
            <textarea class="cmi-input" placeholder="请输入脚本内容" :value="record.referenceScript" :maxlength="-1" auto-height />
          </more-or-less>
        </view>
      </view>

      <view class="cmi-form-item">
        <view class="cmi-form-label relative">
          提示词
          <view class="cmi-link absolute right-0" @click="handleTemplatePromptImport">
            导入模板
          </view>
        </view>
        <textarea class="cmi-input" placeholder="描述对文章的其他要求。例如：引用名人名言，加入成语，删去XX部分..." v-model="record.prompt" :maxlength="-1" auto-height style="min-height: 200rpx;"/>
      </view>

      <view class="pb-36">
        <button class="cmi-btn" type="primary" @click="handleGenerate" :disabled="generateButtonDisabled">生成脚本</button>
      </view>

      <!-- 生成结果 -->
      <view v-for="(result, index) in record.results" :key="result.content" class="mb-20">
        <view class="flex mb-8">
          生成脚本
          <text class="color-muted ml-4 fs-12">{{ result.generateAt }}</text>
          <text class="iconfont icon-shanchu1 ml-auto cmi-link fs-16" @click="handleDelete(index)"></text>
        </view>
        <view class="bg-white px-14 py-10 br-8">
          <view class="pre-wrap">{{ result.content }}</view>

          <view class="flex gap-8 mt-12">
            <button class="cmi-btn flex-1" type="primary" plain @click="handlePolishRequest(index)">润色</button>
            <copy-button class="flex-1" button-class="cmi-btn cmi-btn-primary flex-1" :text="result.content">复制全文</copy-button>
          </view>
        </view>
      </view>
    </view>

    <!-- 润色 -->
    <uni-popup ref="polishPopup" type="bottom">
      <view class="bg-white px-20 pb-22" style="border-radius: 32rpx 32rpx 0 0;">
        <view class="py-16 text-center fs-15 font-bold">润色</view>
        <view class="color-muted mb-8">润色提示词（必填）</view>
        <view class="px-8 py-10 br-8 bd-1">
          <textarea v-model="polishPrompt" :maxlength="-1" style="width: 100%; height: 560rpx;"/>
        </view>
        <button class="cmi-btn mt-20" type="primary" @click="handlePolish" :disabled="!polishPrompt">开始润色</button>
      </view>
    </uni-popup>
  </c-page>
</template>

<script>
export default {

  data() {
    return {
      id: '',
      record: {
        referenceScript: '',
        prompt: '',
        results: [
          { generateAt: '2024-09-01 14:25', content: '### 脚本内容\n - 这里是脚本内容\n - 这里是脚本内容 ' },
          { generateAt: '2024-09-01 14:38', content: '### 脚本内容2\n - 这里是脚本内容2\n - 这里是脚本内容2 ' },
        ],
        
      },

      // 润色提示词
      polishPrompt: '',

      // 操作结果索引
      actionTargetIndex: -1
    }
  },

  computed: {
    isNewRecord({ id }) {
      return !id
    },

    generateButtonDisabled() {
      return false
    }
  },

  onLoad({ id }) {
    this.id = id
    this._handleUsePromptTemplate = (promptTemplate) => {
      this.record.prompt = promptTemplate
    }
    uni.$on('use_prompt_template', this._handleUsePromptTemplate)
  },

  onUnload() {
    uni.$off('use_prompt_template', this._handleUsePromptTemplate)
  },

  methods: {
    handleVideoExtract() {
      uni.redirectTo({
        url: '/pages/ai/video_script_extract/index'
      })
    },

    handleTemplatePromptImport() {
      uni.navigateTo({
        url: '/pages/ai/prompt_templates/index',
      })
    },

    handleGenerate() {
      // todo: 调用生成脚本接口
    },

    handleDelete(result) {
      // todo: 调用删除脚本接口
      uni.showModal({
        title: '是否确认删除？',
        cancelText: '否',
        confirmText: '是',
        success: (res) => {
          if (res.confirm) {
            // todo: 调用删除脚本接口
          }
        }
      })
    },

    handlePolishRequest(index) {
      this.actionTargetIndex = index
      this.$refs.polishPopup.open()
    },

    async handlePolish() {
      // todo: 调用润色接口
      uni.showLoading()
      await new Promise(resolve => setTimeout(resolve, 2000))
      uni.hideLoading()
      this.$refs.polishPopup.close()
      this.polishPrompt = ''
    }
  }
}
</script>

<style lang="scss" scoped>
.collapsable-container {
  background-color: white;
  border-radius: 16rpx;
}
</style>