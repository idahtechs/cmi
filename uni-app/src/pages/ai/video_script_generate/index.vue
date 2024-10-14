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
            脚本内容
            <view class="cmi-link absolute right-0" @click="handleVideoExtractSwitch" v-if="isNewRecord">
              <image src="/static/icons/swap-horizontal.svg" class="w-24 h-24 align-middle"/>
              提取视频脚本
            </view>
          </view>
  
          <more-or-less :threshold="32" :trigger-value="record.content" more-text="展开并编辑" :disabled="isNewRecord || !loaded" class="block bg-white br-16">
            <textarea class="cmi-input" placeholder="请输入脚本内容" v-model="record.content" :maxlength="-1" auto-height style="min-height: 380rpx;" />
          </more-or-less>
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
        <view class="flex flex-column gap-20" id="generated_versions">
          <view v-for="(result, index) in generatedVersions" :key="result.id">
            <view class="flex mb-8">
              生成脚本
              <text class="color-muted ml-4 fs-12">{{ result.generateAt || '' }}</text>
              <text class="iconfont icon-shanchu1 ml-auto cmi-link fs-16" @click="handleDelete(index)"></text>
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
import {
  getVideoScriptDetail,
  rewriteVideoScript,
  polishVideoScript
} from '@/api/ai'

export default {

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
      return false
    },

    generatedVersions() {
      return this.record.versions.reverse().map(v => {
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

    this._handleUsePromptTemplate = (promptTemplate) => {
      this.record.prompt = promptTemplate
    }
    uni.$on('use_prompt_template', this._handleUsePromptTemplate)
  },

  onUnload() {
    uni.$off('use_prompt_template', this._handleUsePromptTemplate)
  },

  methods: {
    onPageReady() {
      const { id, extractStorageKey } = this
      // 读取创作记录
      if (id) {
        getVideoScriptDetail(id).then(res => {
          this.record = {
            ...this.record,
            ...res.data,
            content: this.$util.markdownToPlainText(res.data.content)
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
          this.record.content = this.$util.markdownToPlainText(extractRecord.content)
          if (extractRecord.used > 0) {
            uni.showToast({
              title: `已扣除${extractRecord.used}积分`,
            })
          }
        }

        this.loaded = true
      } else {
        this.loaded = true
      }
    },

    handleVideoExtractSwitch() {
      uni.redirectTo({
        url: '/pages/ai/video_script_extract/index'
      })
    },

    handleTemplatePromptImport() {
      uni.navigateTo({
        url: '/pages/ai/prompt_templates/index',
      })
    },

    async handleGenerate() {
      uni.showLoading({ title: '正在生成脚本' })
      const [err, res] = await this.$util.ef(
        rewriteVideoScript({
          original: this.record.content,
          prompt: this.record.prompt,
          extract_copy_id: this.extractRecord?.id,
        })
      )
      uni.hideLoading()

      if (!err) {
        this.record.versions.push(res.data)
        this.scrollToFirstVersion()
      } else {
        uni.showModal({
          title: '提示',
          content: err.message,
          showCancel: false,
        })
      }
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
        polishVideoScript(version.id, { prompt: this.polishPrompt })
      )
      uni.hideLoading()

      if (!err) {
        this.record.versions.push(res.data)
        this.$refs.polishPopup.close()
        this.polishPrompt = ''
        this.scrollToFirstVersion()
      } else {
        uni.showToast({
          title: err.message,
          icon: 'none'
        })
      }
    },

    scrollToFirstVersion() {
      this.scrollIntoView = ''

      setTimeout(() => {
        this.scrollIntoView = 'generated_versions'
      }, 50)
    }
  }
}
</script>