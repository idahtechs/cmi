<template>
  <c-page login-required flex @ready="onPageReady">
    <template #nav>
      <custom-nav bg-color="transparent"/>
    </template>

    <!-- 表单 -->
    <scroll-view class="absolute left-0 top-0 w-full h-full" scroll-with-animation scroll-y :scroll-into-view="scrollIntoView">
      <view class="cmi-form" :style="loaded ? 'visibility: visible' : 'visibility: hidden'">
        <view class="cmi-form-item">
          <view class="cmi-form-label relative">
            文案内容
            <view class="cmi-link absolute right-0" @click="handleVideoExtractSwitch" v-if="isNewRecord && !extractRecord">
              <image src="/static/icons/swap-horizontal.svg" class="w-24 h-24 align-middle"/>
              使用链接
            </view>
          </view>
  
          <more-or-less :threshold="32" :trigger-value="record.content" more-text="展开并编辑" :disabled="isNewRecord && !extractRecord" class="block bg-white br-16">
            <textarea class="cmi-input" placeholder="请输入文案内容" v-model="record.content" :maxlength="-1" auto-height style="min-height: 380rpx;" />
          </more-or-less>
        </view>
  
        <view class="cmi-form-item">
          <view class="cmi-form-label relative">
            提示词
            <view class="cmi-link absolute right-0" @click="handleTemplatePromptImport('generate')">
              导入模版
            </view>
          </view>
          <more-or-less :threshold="32" :trigger-value="record.prompt" more-text="展开并编辑" :disabled="isNewRecord" class="block bg-white br-16">
            <textarea class="cmi-input" placeholder="描述对文章的其他要求。例如：引用名人名言，加入成语，删去XX部分..." v-model="record.prompt" :maxlength="-1" auto-height style="min-height: 200rpx;" />
          </more-or-less>
        </view>
  
        <view class="pb-36">
          <button class="cmi-btn" type="primary" @click="handleGenerate" :disabled="generateButtonDisabled">{{ isNewRecord ? '生成文案' : '重新生成' }}</button>
        </view>
  
        <!-- 生成结果 -->
        <view class="flex flex-column gap-20" id="generated_versions">
          <view v-for="(result, index) in generatedVersions" :key="result.id" :class="(index === 0 && scrollIntoView) ? 'highlight-version' : ''">
            <view class="flex mb-8">
              生成文案
              <text class="color-muted ml-4 fs-12">{{ result.createTime || '' }}</text>
              <text class="iconfont icon-shanchu1 ml-auto cmi-link fs-16" @click="handleDelete(result)"></text>
            </view>
            <view class="bg-white px-14 py-10 br-8">
              <rich-text :nodes="result.contentHtml"></rich-text>
    
              <view class="flex gap-8 mt-12">
                <button class="cmi-btn flex-1" type="primary" plain @click="handlePolishRequest(index)">润色</button>
                <copy-button class="cmi-btn cmi-btn-primary flex-1" :text="result.contentPlainText">复制全文</copy-button>
              </view>
            </view>
          </view>
        </view>
      </view>
    </scroll-view>

    <!-- 润色 -->
    <uni-popup ref="polishPopup" type="bottom">
      <view class="bg-white px-20 pb-22" style="border-radius: 32rpx 32rpx 0 0;">
        <view class="py-16 text-center fs-15 font-bold">润色</view>
        <view class="color-muted mb-8 relative">
          润色提示词（必填）
          <view class="cmi-link absolute right-0" @click="handleTemplatePromptImport('polish')">
            导入模版
          </view>
        </view>
        <view class="px-8 py-10 br-8 bd-1">
          <textarea v-model="polishPrompt" :maxlength="-1" style="width: 100%; height: 560rpx;" adjust-keyboard-to="bottom" />
        </view>
        <button class="cmi-btn mt-20" type="primary" @click="handlePolish" :disabled="!polishPrompt">开始润色</button>
      </view>
    </uni-popup>
  </c-page>
</template>

<script>
import {
  getContentDetail,
  generateContent,
  regenerateContent,
  polishContent,
  deleteContentVersion,
} from '@/api/ai'
import createGlobalEventHandlersMixin from '@/mixins/createGlobalEventHandlersMixin'

export default {
  mixins: [
    createGlobalEventHandlersMixin({
      'use_prompt_template': function({ prompt, ticket }) {
        switch (ticket) {
          case 'generate':
            this.record.prompt = prompt
            break
          case 'polish':
            this.polishPrompt = prompt
            break
        }
      }
    })
  ],

  data() {
    return {
      id: '',
      extractStorageKey: '', // 用于提取视频脚本的本地存储key
      extractRecord: null,
      record: {
        content: '',
        prompt: '',
        versions: [],
      },

      // 润色提示词
      polishPrompt: '',

      // 操作结果索引
      actionVersionIndex: -1,

      // 防止界面闪烁
      loaded: false,

      scrollIntoView: ''
    }
  },

  computed: {
    isNewRecord({ id }) {
      return !id
    },

    generateButtonDisabled() {
      return !this.record.content || !this.record.prompt
    },

    generatedVersions() {
      return this.record.versions.map(v => {
        const html = this.$util.markdownToHtml(v.content)
        const plainText = this.$util.stripHtmlTags(html)

        return {
          ...v,
          contentHtml: html,
          contentPlainText: plainText
        }
      })
    }
  },

  onLoad({ id, extractStorageKey }) {
    this.id = id
    this.extractStorageKey = extractStorageKey
  },

  methods: {
    onPageReady() {
      const { id, extractStorageKey } = this
      // 读取创作记录
      if (id) {
        getContentDetail(id).then(res => {
          this.record = {
            ...this.record,
            ...res.data,
          }
        }).catch(e => {
          uni.showToast({
            title: e,
            icon: 'none'
          })
        }).finally(() => {
          setTimeout(() => this.loaded = true, 100)
        })

      // 读取提取纪录  
      } else if (extractStorageKey) {
        const extractRecord = uni.getStorageSync(extractStorageKey)
        if (extractRecord) {
          this.extractRecord = extractRecord
          uni.removeStorageSync(extractStorageKey)
          this.record.content = extractRecord.content
          this.showIntegralDeductionInfo(extractRecord.used)
        }

        setTimeout(() => this.loaded = true, 100)
      } else {
        this.loaded = true
      }
    },

    handleVideoExtractSwitch() {
      uni.redirectTo({
        url: '/pages/ai/copy_extract/index'
      })
    },

    handleTemplatePromptImport(ticket) {
      // notice: 在mixin中通过全局事件处理器（use_prompt_template）接收用户选中的模版
      uni.navigateTo({
        url: '/pages/ai/prompt_templates/index?ticket=' + ticket,
      })
    },

    async handleGenerate() {
      uni.showLoading({ title: '正在生成文案' })
      const { content: original, prompt } = this.record
      const promise = this.isNewRecord 
        ? generateContent({ original, prompt, extract_copy_id: this.extractRecord?.id, type: 'copy' })
        : regenerateContent(this.id, { original, prompt })
      const [err, res] = await this.$util.ef(promise)
      uni.hideLoading()

      if (!err) {
        this.record.versions = [res.data, ...this.record.versions]
        if (this.isNewRecord) {
          this.id = res.data.initiation_id
        }
        this.showIntegralDeductionInfo(res.data.used)
        this.scrollToFirstVersion()
        uni.$emit('ai_records_updated')
      } else {
        uni.showModal({
          title: '提示',
          content: err.message,
          showCancel: false,
        })
      }
    },

    handlePolishRequest(index) {
      this.actionVersionIndex = index
      this.$refs.polishPopup.open()
    },

    async handlePolish() {
      const version = this.record.versions[this.actionVersionIndex]
      if (!version) {
        return
      }

      uni.showLoading()
      const [err, res] = await this.$util.ef(
        polishContent(version.id, { prompt: this.polishPrompt })
      )
      uni.hideLoading()

      if (!err) {
        this.record.versions = [res.data, ...this.record.versions]
        this.$refs.polishPopup.close()
        this.polishPrompt = ''
        this.showIntegralDeductionInfo(res.data.used)
        this.scrollToFirstVersion()
        uni.$emit('ai_records_updated')
      } else {
        uni.showToast({
          title: err.message,
          icon: 'none'
        })
      }
    },

    handleDelete(version) {
      uni.showModal({
        title: '是否确认删除？',
        cancelText: '否',
        confirmText: '是',
        success: (res) => {
          if (res.confirm) {
            deleteContentVersion(version.id).then(() => {
              this.record.versions = this.record.versions.filter(v => v.id !== version.id)
              uni.showToast({
                title: '已成功删除',
                icon: 'none'
              })
              uni.$emit('ai_records_updated')
            }).catch(err => {
              console.log(err)
              uni.showToast({
                title: '删除失败，请重试',
                icon: 'none'
              })
            })
          }
        }
      })
    },

    scrollToFirstVersion() {
      setTimeout(() => {
        this.scrollIntoView = 'generated_versions'

        setTimeout(() => {
          this.scrollIntoView = ''
        }, 1200)
      }, 50)
    },

    showIntegralDeductionInfo(deductionIntegral) {
      if (deductionIntegral > 0) {
        uni.showToast({
          title: `本次扣除${deductionIntegral}积分`,
          icon: 'none',
          duration: 5000
        })
      }
    }
  }
}
</script>

<style lang="scss" scoped>
@keyframes ani-highlight-version {
  0% {
    transform: translateY(0);
  }

  50% {
    transform: translateY(0%);
  }

  99% {
    transform: translateY(100%);
    opacity: 1;
  }

  100% {
    opacity: 0;
    display: none;
  }
}

.highlight-version {
  position: relative;
  overflow: hidden;

  &::after {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to bottom, transparent, white 5%);
    z-index: 1;
    pointer-events: none;
    animation: ani-highlight-version 1s linear forwards;
  }

}
</style>