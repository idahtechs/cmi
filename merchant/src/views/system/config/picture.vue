<template>
  <div class="divBox">
    <el-card class="box-card">
      <upload-from :isPage="true" />
    </el-card>
  </div>
</template>

<script>
// +----------------------------------------------------------------------
// | CRMEB [ CRMEB赋能开发者，助力企业发展 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEB并不是自由软件，未经许可不能去掉CRMEB相关版权
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------
import uploadFrom from '@/components/uploadPicture/index.vue'
export default {
  name: 'Picture',
  components: { uploadFrom },
  data() {
    return {
    }
  },
  methods: {
  }

}
</script>

<style scoped>

</style>
