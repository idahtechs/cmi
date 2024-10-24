<template>
  <c-page login-required>
    <template #nav>
      <custom-nav bg-color="var(--page-bg)" />
    </template>

    <view class="flex justify-content-between gap-12 flex-wrap py-28 px-20">
      <navigator
        class="prompt-card prompt-add color-view-theme flex flex-column align-items-center justify-content-center fs-12 br-20"
        key="add"
        url="/pages/ai/user_prompt_templates/edit"
      >
        <text class="fs-20">+</text>
        创建自定义模版
      </navigator>

      <view
        class="prompt-card fs-12 p-20 br-20 bg-white"
        v-for="template in userPromptTemplateRecords"
        :key="template.prompt_template_id"
      >
        <view class="fs-17 font-bold mb-16 text-ellipsis">{{ template.title }}</view>
        <view class="mb-16 text-ellipsis-3">{{ template.prompt }}</view>
        <navigator class="cmi-btn cmi-btn-primary cmi-btn-xs" :url="`/pages/ai/user_prompt_templates/edit?id=${template.prompt_template_id}`">编辑模版</navigator>
      </view>
    </view>
  </c-page>
</template>

<script>
  import createGlobalEventHandlersMixin from '@/mixins/createGlobalEventHandlersMixin'
  import createPagingRecordsMixin from '@/mixins/createPagingRecordsMixin'
  import { getUserPromptTemplates } from '@/api/user'

  export default {
    mixins: [
      createGlobalEventHandlersMixin({
        'user_prompt_templates_updated': function() { this.userPromptTemplateReload() }
      }),
      createPagingRecordsMixin({
        resourceName: 'userPromptTemplate',
        pagingFetcher: getUserPromptTemplates,
        pageSize: 10,
        loginRequired: true
      })
    ],
    data() {
      return {
      }
    },

    methods: {

    }
  }
</script>

<style lang="scss" scoped>
.prompt-card {
  width: 320rpx;
  min-height: 280rpx;

  &.prompt-add {
    border: 1px dashed var(--view-theme);
  }
}
</style>
