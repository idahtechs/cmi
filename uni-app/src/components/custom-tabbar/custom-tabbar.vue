<template>
  <customTab :newData="newData" :activeRouter="activeRouter" v-if="visible"></customTab>
</template>

<script>
import { getNavigation } from "@/api/public";
import { createCacheRequest } from "@/utils/request";
import customTab from '../customTab.vue'

const getNavigationWithCache = createCacheRequest(getNavigation)

export default {
  components: {
    customTab
  },

  data() {
    return {
      newData: {},
      activeRouter: '',
      visible: true
    }
  },
  mounted() {
    const curRoute = this.$util.getCurrentPage().route
    this.activeRouter = '/' + curRoute
    this.visible = this.$util.isTabbarPage(this.activeRouter)

    if (this.visible) {
      this.loadTabBar()
    }
  },
  methods: {
    loadTabBar() {
      getNavigationWithCache().then(res => {
        this.newData = res.data
        if (this.newData.status && this.newData.status.status) {
          uni.hideTabBar()
        } else {
          uni.showTabBar()
        }
      })
    }
  }
}

</script>