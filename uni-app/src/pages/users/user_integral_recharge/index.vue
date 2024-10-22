<template>
  <c-page login-required bg-theme="none" @ready="onPageReady">
    <view class="bg-white">
      <view class="flex gap-12 px-24 pt-40 pb-32 overflow-auto">
        <view
          class="text-center px-16 py-18 br-20 bd-2 flex-none"
          :class="activeIndex === index ? 'active' : null" 
          style="width: 252rpx" 
          v-for="(item, index) in list" 
          :key="item.group_data_id"
          @click="activeIndex = index"
        >
          <view class="fs-12 font-bold color-view-theme">积分</view>
          <view class="fs-32 font-bold">{{ item.integral }}</view>
          <view class="color-muted fs-20">¥ {{ item.price }}</view>
        </view>
      </view>
      <view class="text-center px-24 pb-20 fs-12">
        <button class="cmi-btn" type="primary" @click="buyNow">立即购买</button>
        <view class="mt-16">
          <view class="inline-block" @click="agree = !agree">
            <checkbox :checked="agree" class="events-disabled" />
            已阅读并同意
          </view>
          <navigator class="cmi-link" url="/pages/users/privacy/index?type=3">《用户服务协议》</navigator>
        </view>
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
  import { getIntegralLst, integralRecharge } from '@/api/user'

  export default {
    data() {
      return {
        agree: false,
        activeIndex: 0,
        list: [],

        showPaymentWidget: false
      }
    },

    methods: {
      buyNow() {
        if (!this.agree) {
          return uni.showModal({
            title: '请确认会员条款',
            content: '已阅读并同意《用户服务协议》',
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

      async onPageReady() {
        uni.showLoading()
        const [err, res] = await this.$util.ef(getIntegralLst())
        uni.hideLoading()

        if (err) {
          return uni.showToast({
            title: err.message,
            icon: 'none'
          })
        }

        this.list = res.data.list
      },

      prepareToPay({ payType, payMode }) {
        const item = this.list[this.activeIndex]
        this.$refs.payment.pay({
          payMode,
          paymentAmount: item.price,
          createOrderPromiseFn: () => integralRecharge(item.group_data_id, {
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
  .active {
    background-color: #E5FDFE;
    border-color: var(--view-theme);;
  }
</style>