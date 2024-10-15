<template>
  <view @click="handleDownload"><slot>下载</slot></view>
</template>

<script>
  export default {
    props: {
      fileUrl: String,
      fileName: String,
      type: {
        type: String,
        default: 'image'
      }
    },

    methods: {
      handleDownload() {
        if (this.fileUrl) {
          if (this.type === 'image') {
            this.downloadImage(this.fileUrl)
          } else {
            this.downloadFile(this.fileUrl)
          }
        }
      },

      async downloadImage(url) {
        uni.showLoading()

        // #ifdef MP
        await this.$util.checkAuthorize({
          scope: 'scope.writePhotosAlbum'
        })
        const res = await uni.downloadFile({
          url
        })
        uni.hideLoading()
        if (res[1].tempFilePath) {
          await uni.saveImageToPhotosAlbum({
            filePath: res[1].tempFilePath
          });
          uni.showToast({
            title: '保存成功',
            icon: 'success'
          });
        } else {
          uni.showToast({
            title: '保存失败',
            icon: 'none'
          });
        }
        // #endif

        // #ifdef H5
        this.$util.h5DownloadImage(url, {
          filename: this.fileName || 'download.png'
        })
        // #endif

        uni.hideLoading()
      },

      async downloadFile(url) {
        // TODO: 暂无需求，尚未实现
        console.warn('尚未实现文件下载')
      }
    }
  }
</script>