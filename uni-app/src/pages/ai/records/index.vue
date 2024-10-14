<template>
  <c-page login-required bg-theme="none" @ready="onPageReady">
    <view class="p-20">
      <record-item v-for="record in records" :key="record.id" :record="record" @delete="handleDelete" @click="handleClick(record)"/>
      <view v-if="loading" class="text-center color-muted">加载中...</view>
      <view v-if="finished" class="text-center color-muted">无更多数据</view>
    </view>
  </c-page> 
</template>

<script>
import { getVideoScriptList } from '@/api/ai'
import RecordItem from './components/RecordItem.vue'

export default {
  components: { RecordItem },

  data() {
    return {
      page: 0,
      records: [],
      loading: false,
      finished: false
    }
  },

  onReachBottom() {
    this.loadData()
  },


  methods: {
    onPageReady() {
      this.loadData()
    },

    async loadData() {
      if (this.loading || this.finished) return
      
      const page = this.page + 1
      const limit = 10
      this.loading = true

      const res = await getVideoScriptList({ page, limit })

      this.records = [...this.records, ...res.data.list]
      this.page = page
      this.finished = res.data.list.length < limit
      this.loading = false
    },

    handleClick(record) {
      uni.navigateTo({
        url: `/pages/ai/video_script_generate/index?id=${record.id}`
      })
    },

    handleDelete(record) {
      uni.showModal({
        title: '是否确认删除？',
        cancelText: '否',
        confirmText: '是',
        success: (res) => {
          if (res.confirm) {
            // TODO: 调用删除脚本接口

            this.records = this.records.filter(item => item.id !== record.id)
            uni.showToast({
              title: '删除成功',
              icon: 'none'
            })
          }
        }
      })
    }
  }
}
</script>