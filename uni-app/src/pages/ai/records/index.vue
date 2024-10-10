<template>
  <c-page login-required bg-theme="none" @ready="onPageReady">
    <view class="p-20">
      <record-item v-for="record in records" :key="record.id" :record="record" @delete="handleDelete" @click="handleClick(record)"/>
      <view v-if="loading" class="text-center color-muted">加载中...</view>
    </view>
  </c-page> 
</template>

<script>
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
    console.log('onReachBottom')
    this.loadData()
  },


  methods: {
    onPageReady() {
      this.loadData()
    },

    async loadData() {
      console.log('loadData')
      if (this.loading || this.finished) return
      
      const page = this.page + 1
      const pageSize = 10
      this.loading = true

      // TODO
      const res = await new Promise(resolve => setTimeout(() => resolve({
        data: {
          records: new Array(pageSize).fill(0).map((_, index) => {
            return { id: page * pageSize + index + 1, title: `标题${page * pageSize + index + 1}`, content: `内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容内容${page * pageSize + index + 1}`, updatedAt: `2020-0${index + 1}-0${index + 1}`, source: '小红书' }
          })
        }
      }), 1000)) 

      this.records = [...this.records, ...res.data.records]
      this.page = page
      this.finished = res.data.records.length < pageSize
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