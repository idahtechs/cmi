<template>
  <view class="vip-tag br-4 px-4 py-2 inline-flex align-items-center" v-show="isVip">
    <image src="/static/images/icon-vip.png" class="w-18 h-18" mode="aspectFit" />
    <text class="fs-12 font-bold">专业VIP</text> 
  </view>
</template>

<script>
export default {
  computed: {
    isVip() {
      return this.$store.getters.isVip
    }
  }
};
</script>

<style lang="scss" scoped>
.vip-tag {
  color: #FFE2A7;
  background: linear-gradient(93.05deg, #6A6A6A 8.9%, #232323 93.73%);
}
</style>