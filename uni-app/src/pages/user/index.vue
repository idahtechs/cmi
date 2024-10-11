<template>
  <c-page>
    <view class="px-20 pt-20 pb-24">
      <image src="/static/images/bg-user-index.png" class="absolute left-0 top-0 w-full" mode="widthFix" />
      <view class="relative z-1 flex gap-8" @click="onUserCardClick">
        <image class="user-avatar" :src="userInfo && userInfo.avatar || '/static/images/f.png'"></image>
        <view class="fs-15 font-bold mt-8">{{userInfo ? userInfo.nickname : '请点击登录'}}</view>
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

export default {
  data() {
    return {
      userMenus: [
        { text: '我的积分', url: '', postfix: '100分' },
        { text: '我的创作记录', url: '/pages/ai/records/index', postfix: '' },
        { text: '联系客服', url: '', postfix: '' },
        { text: '设置', url: '/pages/users/user_setting/index', postfix: '' },
      ]
    }
  },

  computed: {
    ...mapGetters(['isLogin', 'userInfo'])
  },

  onShow() {
    this.$store.dispatch('USERINFO', true)
  },

  methods: {
    onUserCardClick() {
      this.$util.ensureLogin()
    },

    onMenuClick(menu) {
      if (!this.isLogin) {
        return this.$util.ensureLogin()
      }

      this.$util.gotoPage({ url: menu.url })
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