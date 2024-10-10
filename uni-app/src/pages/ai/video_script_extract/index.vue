<template>
  <c-page login-required>
    <template #nav>
      <custom-nav bg-color="transparent"/>
    </template>

    <view class="cmi-form">
      <view class="cmi-form-item required">
        <view class="cmi-form-label relative">
          选择视频平台
          <view class="cmi-link absolute right-0" @click="handleManualInput">
            <image src="/static/icons/swap-horizontal.svg" class="w-24 h-24 align-middle"/>
            使用文案脚本
          </view>
        </view>
        <view class="cmi-picker-container" @click="handlePlatformPick">
          <input class="cmi-input" placeholder="请选择视频链接所属平台" :value="form.platform"/>
          <text class="cmi-picker-arrow iconfont icon-jiantou" />
        </view>
      </view>

      <view class="cmi-form-item required">
        <view class="cmi-form-label">视频链接</view>
        <input class="cmi-input" v-model="form.videoUrl"  placeholder="请输入想要提取文案的视频链接" />
      </view>

      <view class="cmi-form-actions">
        <button class="cmi-btn" type="primary" @click="handleSubmit" :disabled="buttonDisabled">提取文案</button>
        <view class="color-gray text-center mt-4">剩余积分：100</view>
      </view>
    </view>

    <c-popup-picker ref="popupPicker" title="选择视频链接平台" :columns="pickerColumns" />
  </c-page>
</template>

<script>
  export default {
    data() {
      return {
        pickerColumns: [[
          {
            label: '抖音',
            value: '抖音',
          },
          {
            label: '小红书',
            value: '小红书',
          },
          {
            label: 'Bilibili',
            value: 'Bilibili',
          },
        ]],

        form: {
          platform: '',
          videoUrl: ''
        }
      }
    },

    computed: {
      buttonDisabled() {
        return !this.form.platform || !this.form.videoUrl
      }
    },

    methods: {
      handleManualInput() {
        uni.redirectTo({ url: '/pages/ai/video_script_generate/index' })  
      },

      async handlePlatformPick() {
        const res = await this.$refs.popupPicker.pick()
        this.form.platform = res.selectedItems[0]?.value
      },

      handleSubmit() {

      }
    }
  }
</script>



