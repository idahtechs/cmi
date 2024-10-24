<template>
  <c-page>
    <view class="px-20 pt-20 pb-24">
      <image src="/static/images/bg-user-index.png" class="absolute left-0 top-0 w-full" mode="widthFix" />
      <view class="relative z-1 flex gap-8" @click="onUserCardClick">
        <image class="user-avatar" :src="userInfo && userInfo.avatar || '/static/images/f.png'"></image>
        <view>
          <view class="fs-15 font-bold mb-12">{{isLogin ? userInfo.nickname : '请点击登录'}}</view>
          <view class="flex flex-column align-items-start ml-6" v-if="isLogin">
            可用积分
            <navigator class="fs-20 font-bold cursor-pointer" url="/pages/users/user_integral_records/index">
              {{userIntegralInfo.integral || 0}}
              <text class="iconfont icon-jiantou ml-4"></text>
            </navigator>
          </view>
        </view>
      </view>
    </view>

    <view class="flex flex-column p-20 gap-12">
      <view 
        class="flex bg-white br-8 px-20 py-12 fs-15"
        v-for="item in userMenus"
        :key="item.text"
        @click="onMenuClick(item)"
      >
        <text>{{ item.text }}</text>
        <view class="ml-auto flex-none">
          {{ item.postfix }}
          <text class="iconfont icon-jiantou"></text>
        </view>
      </view>
    </view>
  </c-page>
</template>

<script>
import { mapGetters } from 'vuex'
import { getIntegralInfo } from '@/api/user'

export default {
  data() {
    return {
      userIntegralInfo: {}
    }
  },

  computed: {
    ...mapGetters(['isLogin', 'userInfo']),
    userMenus() {
      return [
        { text: '购买积分', url: '/pages/users/user_integral_recharge/index', postfix: '' },
        { text: '分享好友', url: '/pages/users/share/index', postfix: '' },
        { text: '我的创作记录', url: '/pages/ai/records/index', postfix: '' },
        { text: '自定义提示词模版', url: '/pages/ai/user_prompt_templates/index', postfix: '' },
        { text: '联系客服', url: '/pages/customer_service_qrcode/index', postfix: '' },
        { text: '设置', url: '/pages/users/user_info/index', postfix: '' },
      ]
    } 
  },

  onShow() {
    this.loadData()
  },

  methods: {
    onUserCardClick() {
      this.$util.ensureLogin()
    },

    onMenuClick(menu) {
      if (!this.isLogin) {
        return this.$util.ensureLogin()
      }

      if (!menu.url) {
        return uni.showModal({
          title: '提示',
          content: '该功能暂未开放，敬请期待',
          showCancel: false,
          confirmText: '我知道了'
        })
      }

      this.$util.gotoPage({ url: menu.url })
    },

    async loadData() {
      if (!this.$store.getters.isLogin) return

      this.$store.dispatch('USERINFO', true)
      const [error, res] = await this.$util.ef(getIntegralInfo())
      if (!error) {
        this.userIntegralInfo = res.data
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.user-avatar {
  width: 146rpx;
  height: 146rpx;
  border-radius: 100%;
}
</style>