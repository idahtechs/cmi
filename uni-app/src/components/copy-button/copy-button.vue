<template>
  <!-- #ifdef H5 -->
  <view :class="buttonClass" class="copy-button" :id="elId" :data-clipboard-text="text">
    <slot>复制</slot>
  </view>
  <!-- #endif -->
  <!-- #ifndef H5 -->
  <view :class="buttonClass" :id="elId" @click="copyText">
    <slot>复制</slot>
  </view>
  <!-- #endif -->
</template>

<script>
// #ifdef H5
import ClipboardJS from "@/plugin/clipboard/clipboard.js";
// #endif
let id = 1;

export default {
  props: {
    text: {
      type: String,
      default: ''
    },
    successText: {
      type: String,
      default: '复制成功'
    },
    errorText: {
      type: String,
      default: '复制失败'
    },
    buttonClass: {
      type: String,
      default: ''
    }
  },

  data() {
    return {
      elId: `copy_button_${id++}`
    }
  },

  mounted() {
    // #ifdef H5
    var clipboard = new ClipboardJS(`#${this.elId}`);
    clipboard.on('success', (e) => {
      uni.showToast({
        title: this.successText,
        icon: 'none'
      })
    });
    clipboard.on('error', (e) => {
      uni.showToast({
        title: this.errorText,
        icon: 'none'
      })
    });
    // #endif 
  },

  methods: {
    copyText() {
      uni.setClipboardData({
        data: this.text,
        success: () => {
          uni.showToast({
            title: this.successText,
            icon: 'none'
          })
        },
        fail: () => {
          uni.showToast({
            title: this.errorText,
            icon: 'none'
          })
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.copy-button {
  display: inline-block;
}
</style>