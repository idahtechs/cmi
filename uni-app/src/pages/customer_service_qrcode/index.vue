<template>
  <c-page flex>
    <view class="absolute left-0 top-0 w-full h-full flex-1 flex flex-column">
      <view class="relative z-1 flex-1 flex flex-column justify-content-center align-items-center p-20">
        <image class="absolute z--1 left-0 top-0 w-full h-full" src="/static/images/bg-customer-service-qrcode.png" mode="aspectFill" />
        <view style="font-size: 92rpx; font-weight: bold; color: white; text-shadow: 0 8rpx 8rpx rgba(5, 96, 201, 0.7); margin-bottom: 74rpx;">扫码联系客服</view>
        <image :src="qrcode" mode="widthFix" class="w-full br-20"/>
      </view>
      <view class="p-20 flex-none">
        <download-button class="cmi-btn cmi-btn-primary" :file-url="qrcode" file-name="客服二维码.png">保存二维码</download-button>
      </view>
    </view>
  </c-page>
</template>

<script>

export default {
  data() {
    return {
      qrcode: 'https://qa-yp-assets.idahtechs.net/def/a6951202403121840209791.png'
    }
  }
}
</script>