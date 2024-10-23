<template>
  <c-page login-required bg-theme="none">
    <view class="p-20">
      <record-item v-for="record in aiRecords" :key="record.id" :record="record" @delete="handleDelete" @click="handleClick(record)"/>
      <view v-if="aiLoading" class="text-center color-muted">加载中...</view>
      <view v-if="aiFinished" class="text-center color-muted">无更多数据</view>
    </view>
  </c-page> 
</template>

<script>
import { getContentList, deleteContent } from '@/api/ai'
import RecordItem from './components/RecordItem.vue'
import createGlobalEventHandlersMixin from '@/mixins/createGlobalEventHandlersMixin'
import createPagingRecordsMixin from '../../../mixins/createPagingRecordsMixin'

export default {
  mixins: [
    createGlobalEventHandlersMixin({
      'ai_records_updated': function() { this.aiReload() }
    }),
    createPagingRecordsMixin({
      resourceName: 'ai',
      pagingFetcher: getContentList,
      pageSize: 10
    })
  ],

  components: { RecordItem },

  methods: {
    handleClick(record) {
      const detailPageMapping = {
        video_script: '/pages/ai/video_script_generate/index',
        copy: '/pages/ai/copy_generate/index'
      }

      const pagePath = detailPageMapping[record.type] || detailPageMapping.copy

      uni.navigateTo({
        url: `${pagePath}?id=${record.id}`
      })
    },

    handleDelete(record) {
      uni.showModal({
        title: '是否确认删除？',
        cancelText: '否',
        confirmText: '是',
        success: (res) => {
          if (res.confirm) {
            deleteContent(record.id).then(() => {
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