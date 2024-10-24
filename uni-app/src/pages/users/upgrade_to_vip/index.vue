<template>
  <c-page login-required bg-theme="none" @ready="onPageReady">
    <view v-if="vipProduct && vipProduct.def" style="padding-bottom: 180rpx;">
      <image
        v-for="url in vipProduct.def.posters"
        :key="url"
        class="w-full block"
        :src="url"
        mode="widthFix"
      />

      <view class="px-24 py-12 flex align-items-center fixed left-0 bottom-0 w-full bg-white">
        <view class="flex-auto">
          <view class="golden fs-24 font-bold">¥ {{ vipProduct.def.price }}</view>
          <view class="fs-12">
            <view class="inline-block" @click="agree = !agree">
              <checkbox :checked="agree" class="events-disabled" />
              已阅读并同意
            </view>
            <navigator class="golden inline-block" url="/pages/users/privacy/index?type=sys_svip">《会员协议》</navigator>
          </view>
        </view>

        <button class="cmi-btn cmi-btn-sm buy-button" @click="buyNow">立即升级</button>
      </view>
    </view>

    <common-payment
      ref="payment"
      :enabledPayModes="['wechat']"
      :showPaymentWidget="showPaymentWidget"
      @prepareToPay="prepareToPay"
      @close="showPaymentWidget = false"
    />
  </c-page>
</template>

<script>
  import { memberCard, memberCardCreate } from '@/api/user.js'

  export default {
    data() {
      return {
        vipProduct: null,
        agree: false,
        showPaymentWidget: false,
      }
    },

    methods: {
      onPageReady() {
        memberCard().then(res => {
          console.log(res)
          this.vipProduct = res.data
        })
      },

      buyNow() {
        if (!this.agree) {
          return uni.showModal({
            title: '请确认会员条款',
            content: '已阅读并同意《会员协议》',
            success: (res) => {
              if (res.confirm) {
                this.agree = true
                this.showPaymentWidget = true
              }
            }
          })
        } else {
          this.showPaymentWidget = true
        }
      },

      prepareToPay({ payType, payMode }) {
        const item = this.vipProduct.def
        this.$refs.payment.pay({
          payMode,
          paymentAmount: item.price,
          createOrderPromiseFn: () => memberCardCreate(item.group_data_id, {
            pay_type: payType,
            // #ifdef H5
            return_url: location.origin + '/pages/user/index'
            // #endif
          }),
          success: () => {
            uni.navigateBack()
          },
          fail: () => {}
        })
      }
    }
  }
</script>

<style lang="scss" scoped>
  .golden {
    color: #F3B33D;
  }

  .buy-button {
    color: white;
    background: linear-gradient(97.29deg, #FFD177 5.67%, #F3B33D 94.33%);
    padding-left: 80rpx;
    padding-right: 80rpx;
  }
</style>
