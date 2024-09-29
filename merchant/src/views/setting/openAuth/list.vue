<template>
  <div class="divBox">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <div class="container">
          <el-form size="small" label-width="60px" :inline="true">
            <el-form-item label="状态：">
              <el-select v-model="tableFrom.status" placeholder="请选择" class="filter-item selWidth" clearable @change="getList(1)">
                <el-option label="开启" value="1" />
                <el-option label="关闭" value="0" />
              </el-select>
            </el-form-item>
            <el-form-item label="搜索：">
              <el-input v-model="tableFrom.keyword" placeholder="请输入账号或者描述" class="selWidth" @keyup.enter.native="getList(1)" />
            </el-form-item>
            <el-button size="small" type="primary" @click="getList(1)">查询</el-button>
          </el-form>
          <el-button size="small" type="primary" @click="add">添加账号</el-button>
        </div>
      </div>
      <el-table v-loading="listLoading" :data="tableData.data" style="width: 100%" size="mini">
        <el-table-column label="ID" prop="id" min-width="50" />
        <el-table-column prop="title" label="标题" min-width="120" />
        <el-table-column prop="access_key" label="KEY" min-width="150" />
        <el-table-column prop="mark" label="备注" min-width="120" />
        <el-table-column prop="create_time" label="添加时间" min-width="120" />
        <el-table-column prop="last_time" label="最后登录时间" min-width="120" />
        <el-table-column label="状态" min-width="100">
          <template slot-scope="scope">
            <el-switch
              v-model="scope.row.status"
              :active-value="1"
              :inactive-value="0"
              active-text="开启"
              inactive-text="关闭"
              @click.native="onchangeIsShow(scope.row)"
            />
          </template>
        </el-table-column>  
        <el-table-column label="操作" min-width="150" fixed="right" align="center">
          <template slot-scope="scope">
            <el-button type="text" size="small" @click="onEdit(scope.row.id)">编辑</el-button>
            <el-button type="text" size="small" @click="onDetails(scope.row.id)">查看</el-button>
            <el-button type="text" size="small" @click="reset(scope.row.id)">重置</el-button>
            <el-button type="text" size="small" @click="onDelete(scope.row.id,scope.$index)">删除</el-button>
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
   
    <!--详情-->
    <el-dialog
      v-if="dialogVisible"
      title="查看"
      :visible.sync="dialogVisible"
      width="500px"
    >
      <div v-loading="loading" style="height: 80px;margin-top: 20px;">
        <div class="description">
          <div class="description-term"><span class="title">secretKey：</span>{{secret_key}}</div>
          <el-button size="small" class="copy copy-data" :data-clipboard-text="secret_key" type="text">复制</el-button>
        </div>
      </div>
    </el-dialog>
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
import ClipboardJS from "clipboard";
import { openAuthList, addOpenAuth, updateOpenAuth, openAuthStatus, openAuthDelete, getSecretKey, resetSccretKey } from '@/api/setting'
export default {
  data() {
    return {
      dialogVisible: false,
      secret_key: {},
      tableData: {
        data: [],
        total: 0
      },
      listLoading: true,
      loading: true,
      id: "",
      tableFrom: {
        keyword: '',
        status: '',
        page: 1,
        limit: 20
      },

    }
  },
  mounted() {
    this.$nextTick(function() {
      const clipboard = new ClipboardJS('.copy-data');
      clipboard.on("success", () => {
          this.$message.success('复制成功');
      });
    });
    this.getList(1)
  },
  methods: { 
    // 添加门店
    add() {
      this.$modalForm(addOpenAuth()).then(() => this.getList(''))
    },
    // 编辑门店
    onEdit(id) {  
      this.$modalForm(updateOpenAuth(id)).then(() => this.getList(''))
    },
    // 列表
    getList(num) {
      this.listLoading = true
      this.tableFrom.page = num || this.tableFrom.page
      openAuthList(this.tableFrom)
        .then(res => {
          this.tableData.data = res.data.list
          this.tableData.total = res.data.count
          this.listLoading = false
        })
        .catch(res => {
          this.$message.error(res.message)
          this.listLoading = false
        })
    },
    pageChange(page) {
      this.tableFrom.page = page
      this.getList('')
    },
    handleSizeChange(val) {
      this.tableFrom.limit = val
      this.getList('')
    },
    // 详情
    onDetails(id) {
      this.id = id
      this.dialogVisible = true
      getSecretKey(id)
        .then(res => {
          this.secret_key = res.data.secret_key
          this.loading = false
        })
        .catch(res => {
          this.$message.error(res.message)
          this.loading = false
        })
    },
    // 重置
    reset(id) {
      resetSccretKey(id)
        .then(res => {
          this.dialogVisible = false
          this.$message.success(res.message)
          this.getList('')
        })
        .catch(res => {
          this.$message.error(res.message)
          this.loading = false
        })
    },
    // 删除
    onDelete(id,idx) {
      this.$modalSureDelete('确定删除该账户').then(
        () => {
          openAuthDelete(id)
            .then(({ message }) => {
              this.$message.success(message)
              this.tableData.data.splice(idx, 1)
            })
            .catch(({ message }) => {
              this.$message.error(message)
            })
        }
      )
    },
    // 是否开通
    onchangeIsShow(row) {
      openAuthStatus(row.id, { status: row.status })
        .then(({ message }) => {
          this.$message.success(message)
          this.getList()
        })
        .catch(({ message }) => {
          this.$message.error(message)
        })
    }
  }
}
</script>

<style lang="scss" scoped>
.description{
  font-size: 12px;
  display: flex;
  align-items: center;
  .copy{
    margin-left: 15px;
  }
  .title{
    font-weight: bold;
    font-size: 14px;
  }
}
</style>
