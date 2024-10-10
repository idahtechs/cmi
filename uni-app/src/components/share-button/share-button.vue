<template>
  <view class="share-button">
    <!-- #ifdef MP -->
    <button class="share" open-type="share"><slot></slot></button>
    <canvas type="2d" :id="canvasId" class="hidden-canvas"></canvas>
    <!-- #endif -->
    <!-- #ifndef MP -->
    <view class="share" @click="showSharePopup = true"><slot></slot></view>
    <image v-if="showSharePopup" class="share-popup" :src="'/static/images/share-info-wechat.png'" alt="" mode="widthFix"
            @click="showSharePopup = false" />
    <!-- #endif -->
  </view>
</template>

<script>
  let id = 0
  export default {
    props: {
      // 图片裁剪模式
      clipMode: {
        type: String,
        default: 'aspectFit', // aspectFit|aspectFill|scaleToFill
      }
    },

    data() {
      return {
        canvasId: `share_canvas_${id++}`,
        showSharePopup: false
      }
    },

    methods: {
      genShareMessage(originShareInfo) {
        const shareInfo = { ...originShareInfo }
        const imageUrl = originShareInfo.imageUrl

        if (imageUrl) {
          uni.showLoading()
          shareInfo.promise = this.drawShareImage(imageUrl).then(tempFilePath => {
            uni.hideLoading()
            return {
              ...originShareInfo,
              imageUrl: tempFilePath
            }
          }).catch(() => {
            uni.hideLoading()
            return originShareInfo
          })
        }

        return shareInfo
      },

      drawShareImage(imageUrl) {
        const { promise, resolve, reject } = this.$util.createDefferred()
        const query = this.createSelectorQuery()
        query.select(`#${this.canvasId}`)
          .fields({ node: true, size: true })
          .exec(async (res) => {
            try {
              const canvas = res[0].node
              const ctx = canvas.getContext('2d')
              const { width, height } = res[0]

              const dpr = wx.getSystemInfoSync().pixelRatio
              canvas.width = width * dpr
              canvas.height = height * dpr
              ctx.scale(dpr, dpr)
              ctx.clearRect(0, 0, width, height)

              const image = await this.createImage(canvas, imageUrl)
              const drawParams = this.imageDrawParams({ clipMode: this.clipMode, canvasWidth: width, canvasHeight: height, image })
              
              ctx.drawImage(
                image,
                drawParams.sx,
                drawParams.sy,
                drawParams.sWidth,
                drawParams.sHeight,
                drawParams.dx,
                drawParams.dy,
                drawParams.dWidth,
                drawParams.dHeight
              )

              uni.canvasToTempFilePath({
                canvas,
                success: ({ tempFilePath }) => {
                  resolve(tempFilePath)
                },
                fail: (e) => {
                  reject(e)
                }
              }, this)
            } catch(e) {
              reject(e)
            }
          })

        return promise
      },

      imageDrawParams({ clipMode, canvasWidth, canvasHeight, image }) {
        
        const ratio = canvasWidth / canvasHeight
        let sx = 0
        let sy = 0
        let sWidth = image.width
        let sHeight = image.height
        let dx = 0
        let dy = 0
        let dWidth = canvasWidth
        let dHeight = canvasHeight
        let imageRatio = sWidth / sHeight

        switch (clipMode) {
          case 'aspectFit': 
            if (imageRatio > ratio) {
              dHeight = Math.round(dWidth / imageRatio)
              dy = Math.round((canvasHeight - dHeight) / 2)
             
            } else if (imageRatio < ratio) {
              dWidth = Math.round(dHeight * imageRatio)
              dx = Math.round((canvasWidth - dWidth) / 2)
            }
            break

          case 'aspectFill':
            if (imageRatio > ratio) {
              sWidth = Math.round(sHeight * ratio)
              sx = Math.round((image.width - sWidth) / 2)
            } else if (imageRatio < ratio) {
              sHeight = Math.round(sWidth / ratio)
              sy = Math.round((image.height - sHeight) / 2)
            }
            break
        }
        
        return { sx, sy, sWidth, sHeight, dx, dy, dWidth, dHeight }
      },

      createImage(canvas, imageUrl) {
        const { resolve, reject, promise } = this.$util.createDefferred()

        const image = canvas.createImage()
        image.src = imageUrl
        image.onload = () => {
          resolve(image)
        }

        image.onerror = reject

        return promise
      }
    }
  }
</script>

<style lang="scss" scoped>
  .share-button {
    .share {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 100%;
      border: none;
      border-radius: 0;
      padding: 0;
      background-color: transparent;
      color: inherit;
      font-size: inherit;
      font-weight: inherit;
      line-height: inherit;
    }

    .share-popup {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 9999;
    }
  }

  .hidden-canvas {
    opacity: 0;
    // 分享图片比例是5:4
    width: 500px;
    height: 400px;
    position: fixed; 
    right: -999999rpx;
  }
</style>