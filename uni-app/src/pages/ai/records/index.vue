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
import { getVideoScriptList, deleteVideoScript } from '@/api/ai'
import RecordItem from './components/RecordItem.vue'
import createGlobalEventHandlers from '@/mixins/createGlobalEventHandlers'

export default {
  mixins: [
    createGlobalEventHandlers({
      'ai_records_updated': function() { this.reload() }
    })
  ],
  components: { RecordItem },

  data() {
    return {
      page: 0,
      records: [],
      loading: false,
      finished: false
    }
  },

  onPullDownRefresh() {
    this.reload().finally(() => uni.stopPullDownRefresh())
  },

  onReachBottom() {
    this.loadData()
  },

  // onLoad() {
  //   this._handleRecordsUpdated = () => this.reload()
  //   uni.$on('ai_records_updated', this._handleRecordsUpdated)
  // },

  // onUnload() {
  //   uni.$off('ai_records_updated', this._handleRecordsUpdated)
  // },

  methods: {
    onPageReady() {
      this.loadData()
    },

    async loadData() {
      if (this.loading || this.finished) return
      
      const page = this.page + 1
      const limit = 10
      this.loading = true

      const [err,res] = await this.$util.ef(getVideoScriptList({ page, limit }))
      this.loading = false

      if (err) {
        return uni.showToast({
          title: err.message,
          icon: 'none'
        })
      } 

      this.records = [...this.records, ...res.data.list]
      this.page = page
      this.finished = res.data.list.length < limit
    },

    reload() {
      this.page = 0
      this.records = []
      this.finished = false
      return this.loadData()
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
            deleteVideoScript(record.id).then(() => {
              this.records = this.records.filter(item => item.id !== record.id)
              uni.showToast({
                title: '已成功删除',
                icon: 'none'
              })
            }).catch(e => {
              console.log(e)
              uni.showToast({
                title: '删除失败，请重试',
                icon: 'none'
              })
            })
           
          }
        }
      })
    }
  }
}
</script>