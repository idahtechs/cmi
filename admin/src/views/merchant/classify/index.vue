<template>
  <div class="divBox">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <el-button size="small" type="primary" @click="onAdd">添加商户分类</el-button>
      </div>
      <el-table
        v-loading="listLoading"
        :data="tableData.data"
        style="width: 100%"
        size="small"
        highlight-current-row
      >
        <el-table-column
          prop="merchant_category_id"
          label="ID"
          min-width="60"
        />
        <el-table-column
          prop="category_name"
          label="分类名称"
          min-width="150"
        />
        <el-table-column
          prop="commission_rate"
          label="手续费"
          min-width="130"
        />
        <el-table-column
          prop="create_time"
          label="创建时间"
          min-width="150"
        />
        <el-table-column label="操作" min-width="100" fixed="right" align="center">
          <template slot-scope="scope">
            <el-button type="text" size="small" @click="onEdit(scope.row.merchant_category_id)">编辑</el-button>
            <el-button type="text" size="small" @click="handleDelete(scope.row.merchant_category_id, scope.$index)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>
      <div class="block">
        <el-pagination
          :page-sizes="[20, 40, 60, 80]"
          :page-size="tableFrom.limit"
          :current-page="tableFrom.page"
          layout="total, sizes, prev, pager, next, jumper"
          :total="tableData.total"
          @size-change="handleSizeChange"
          @current-change="pageChange"
        />
      </div>
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
import {
  categoryListApi,
  categoryCreateApi,
  categoryUpdateApi,
  categoryDeleteApi
} from '@/api/merchant'
export default {
  name: 'MerchantClassify',
  data() {
    return {
      tableFrom: {
        page: 1,
        limit: 20
      },
      tableData: {
        data: [],
        total: 0
      },
      listLoading: true
    }
  },
  mounted() {
    this.getList()
  },
  methods: {
    // 列表
    getList() {
      this.listLoading = true
      categoryListApi(this.tableFrom).then(res => {
        this.tableData.data = res.data.list
        this.tableData.total = res.data.count
        this.listLoading = false
      }).catch(res => {
        this.listLoading = false
        this.$message.error(res.message)
      })
    },
    pageChange(page) {
      this.tableFrom.page = page
      this.getList()
    },
    handleSizeChange(val) {
      this.tableFrom.limit = val
      this.getList()
    },
    // 添加
    onAdd() {
      this.$modalForm(categoryCreateApi()).then(() => this.getList())
    },
    // 编辑
    onEdit(id) {
      this.$modalForm(categoryUpdateApi(id)).then(() => this.getList())
    },
    // 删除
    handleDelete(id, idx) {
      this.$modalSure().then(() => {
        categoryDeleteApi(id).then(({ message }) => {
          this.$message.success(message)
          this.tableData.data.splice(idx, 1)
        }).catch(({ message }) => {
          this.$message.error(message)
        })
      })
    }
  }
}
</script>

<style scoped lang="scss">

</style>
