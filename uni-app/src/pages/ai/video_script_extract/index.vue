<template>
  <c-page login-required>
    <template #nav>
      <custom-nav bg-color="transparent" />
    </template>

    <view class="cmi-form">
      <view class="cmi-form-item required">
        <view class="cmi-form-label relative">
          选择视频平台
          <view class="cmi-link absolute right-0" @click="handleManualInput">
            <image src="/static/icons/swap-horizontal.svg" class="w-24 h-24 align-middle" />
            使用文案脚本
          </view>
        </view>
        <c-picker :options="platforms" v-model="form.platform" />
      </view>

      <view class="cmi-form-item required">
        <view class="cmi-form-label">视频链接</view>
        <input class="cmi-input" v-model="form.url" placeholder="请输入想要提取文案的视频链接" />
      </view>

      <view class="cmi-form-actions">
        <button class="cmi-btn" type="primary" @click="handleSubmit" :disabled="buttonDisabled">提取文案</button>
        <view class="color-gray text-center mt-4">剩余积分：100</view>
      </view>
    </view>
  </c-page>
</template>

<script>
import { extractVideoScript } from '@/api/ai'
import { getConfigGroupWithCache } from '@/api/public'

export default {
  data() {
    return {
      platforms: [
        // { value: 'douyin', label: '抖音' }, 
      ],

      form: {
        platform: '',
        url: ''
      },
    }
  },

  computed: {
    buttonDisabled() {
      return !this.form.platform || !this.$util.extractURL(this.form.url)
    },
  },

  onLoad() {
    this.loadData()
  },

  methods: {
    handleManualInput() {
      uni.redirectTo({ url: '/pages/ai/video_script_generate/index' })
    },

    handleSubmit() {
      uni.showLoading({ title: '正在提取文案' })
      const data = {
        ...this.form,
        url: this.$util.extractURL(this.form.url)
      }
      extractVideoScript(data).then(res => {
        const extractStorageKey = `video_script_extract_${res.data.id}`
        uni.setStorageSync(extractStorageKey, res.data)
        uni.redirectTo({ url: `/pages/ai/video_script_generate/index?extractStorageKey=${extractStorageKey}` })
        uni.hideLoading()
      }).catch(err => {
        uni.showToast({ title: err, icon: 'none' })
      })
    },

    loadData() {
      getConfigGroupWithCache('script_extraction_platforms').then(res => {
        this.platforms = res.data
      })
    }
  }
}
</script>