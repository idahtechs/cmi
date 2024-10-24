<template>
  <c-page login-required bg-theme="none" :loading="loading" @ready="onPageReady">
    <view class="p-20">
      <view class="bg-white p-16 mb-16 br-12 flex gap-8">
        <image class="flex-none" style="width: 120rpx; height: 120rpx;" src="../static/images/icon-link.png" mode="aspectFill"></image>
        <view class="flex-auto">
          <view class="br-4 px-4 py-2 mb-6 fs-12 break-all" style="background: #f2f2f2; color: #484848; min-height: 68rpx;">{{ link }}</view>
          <view class="text-right" v-if="link">
            <copy-button class="cmi-link" :text="link" success-text="链接复制成功">复制链接分享</copy-button>
          </view>
        </view>
      </view>
  
      <view class="bg-white p-16 br-12 flex align-items-center gap-8">
        <image class="flex-none" style="width: 120rpx; height: 120rpx;" src="../static/images/icon-qrcode.png" mode="aspectFill"></image>
        <view class="flex-auto text-right">
          <navigator :url="'/pages/users/share_qrcode/index?qrcode=' + qrcode" class="cmi-link">分享二维码</navigator>
        </view>
      </view>
    </view>
  </c-page>
</template>

<script>
import { getSpreadImage } from '@/api/user'

export default {
  data() {
    return {
      loading: true,
      link: '',
      qrcode: ''
    }
  },

  methods: {
    async onPageReady() {
      this.loading = true
      let type = 'h5'
      // #ifdef MP-WEIXIN
      type = 'routine'
      // #endif
      const [err, res] = await this.$util.ef(getSpreadImage({ type }))
      this.loading = false

      if (err) {
        return uni.showToast({
          title: err.message,
          icon: 'none'
        })
      }

      const { shortLink, qrcode } = res.data
      this.link = shortLink
      this.qrcode = qrcode || ''
    }
  }
}
</script>