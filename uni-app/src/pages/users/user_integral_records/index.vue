<template>
  <c-page bg-theme="none">
    <view class="p-20 flex align-items-center gap-8 bd-b-1 bg-white ft-15" v-for="item in integralRecords" :key="item.bill_id">
      <view class="flex-auto">
        <text class="color-black">{{ item.title }}</text>
        <view class="color-muted">{{ item.create_time }}</view>
      </view>
      <view class="flex-none" :class="item.pm ? 'color-danger' : 'color-success'">
        {{ item.pm ? '+' : '-' }}{{ parseFloat(item.number) }}
      </view>
    </view>
  </c-page>
</template>

<script>
	import { getIntegralList } from '@/api/user.js';
  import createPagingRecordsMixin from '@/mixins/createPagingRecordsMixin'

  export default {
    mixins: [
      createPagingRecordsMixin({
        resourceName: 'integral',
        pagingFetcher: getIntegralList,
        pageSize: 10
      })
    ],
  }
</script>
