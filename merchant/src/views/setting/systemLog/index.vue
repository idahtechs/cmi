<template>
  <div class="divBox">
    <el-card class="box-card">
      <div class="filter-container">
        <div class="demo-input-suffix">
          管理员ID：
          <el-input v-model="tableFrom.admin_id" placeholder="请输入" style="width: 200px;" @keyup.enter.native="handleFilter" />
          <el-select v-model="tableFrom.method" placeholder="请选择" clearable style="width: 200px" >
            <el-option v-for="item in importanceOptions" :key="item" :label="item" :value="item" />
          </el-select>
          <el-date-picker
            v-model="timeVal"
            value-format="yyyy/MM/dd"
            format="yyyy/MM/dd"
            size="small"
            type="daterange"
            placement="bottom-end"
            placeholder="自定义时间"
            style="width: 250px;"
            @change="onchangeTime"
          />
          <el-button type="primary" size="small" @click="getList(1)">搜索</el-button>
        </div>
      </div>
      <el-table
        v-loading="listLoading"
        :data="tableData.data"
        style="width: 100%"
        size="small"
        highlight-current-row
      >
        <el-table-column
          prop="log_id"
          label="ID"
          min-width="60"
        />
        <el-table-column
          prop="admin_name"
          label="管理员名称"
          min-width="120"
        />
        <el-table-column
          prop="route"
          label="请求"
          min-width="200"
        />
        <el-table-column
          prop="method"
          label="请求方式"
          min-width="100"
        />
        <el-table-column
          prop="url"
          label="链接"
          min-width="250"
        />
        <el-table-column
          prop="ip"
          label="IP"
          min-width="150"
        />
        <el-table-column
          prop="create_time"
          label="创建时间"
          min-width="150"
        />
      </el-table>
      <div class="block">
        <el-pagination :page-sizes="[20, 40, 60, 80]" :page-size="tableFrom.limit" :current-page="tableFrom.page" layout="total, sizes, prev, pager, next, jumper" :total="tableData.total" @size-change="handleSizeChange" @current-change="pageChange" />
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
import { adminLogApi } from '@/api/setting'
export default {
  name: 'SystemLog',
  data() {
    return {
      isChecked: false,
      listLoading: true,
      timeVal: [],
      tableData: {
        data: [],
        total: 0
      },
      section_time: [],
      importanceOptions: ['POST', 'DELETE'],
      tableFrom: {
        page: 1,
        limit: 20,
        admin_id: '',
        method: '',
        date: ''
      }
    }
  },
  mounted() {
    this.getList('')
  },
  methods: {
    // 列表
    getList(num) {
      this.listLoading = true
      this.tableFrom.page = num ? num : this.tableFrom.page;
      adminLogApi(this.tableFrom).then(res => {
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
      this.getList('')
    },
    handleSizeChange(val) {
      this.tableFrom.limit = val
      this.getList(1)
    },
    // 具体日期
    onchangeTime(e) {
      this.timeVal = e;
      this.tableFrom.date = e ? this.timeVal.join("-") : "";
      this.tableFrom.page = 1;
      this.getList('');
    },

  }
}
</script>

<style scoped lang="stylus">
</style>
