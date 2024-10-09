<template>
  <customTab :newData="newData" :activeRouter="activeRouter"></customTab>
</template>

<script>
import { getNavigation } from "@/api/public";
import customTab from '../customTab.vue'

export default {
  components: {
    customTab
  },

  data() {
    return {
      newData: {},
      activeRouter: ''
    }
  },
  mounted() {
    const routes = getCurrentPages();
    const curRoute = routes[routes.length - 1].route
    this.activeRouter = '/' + curRoute
    this.loadTarbar()
  },
  methods: {
    loadTarbar() {
      getNavigation().then(res => {
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