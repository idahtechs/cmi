<template>
  <div class="divBox">
    <div>
      <el-card class="box-card">
        <div slot="header" class="clearfix">
          <el-button size="small" type="primary" @click="onAdd"
            >添加店铺类型
          </el-button>
        </div>
        <el-table
          v-loading="listLoading"
          :data="tableData.data"
          style="width: 100%"
          size="small"
          highlight-current-row
          class="switchTable"
        >
          <el-table-column prop="mer_type_id" label="ID" min-width="60" />
          <el-table-column
            prop="type_name"
            label="店铺类型名称"
            min-width="120"
          />
          <el-table-column
            label="店铺数量（个）"
            prop="merchant_count"
            min-width="120"
          />
          <el-table-column prop="type_name" label="店铺保证金" min-width="120">
            <template slot-scope="scope">
              <span class="spBlock">{{
                scope.row.is_margin ? scope.row.margin + "元" : "无"
              }}</span>
            </template>
          </el-table-column>
          <el-table-column
            prop="type_info"
            label="店铺类型要求"
            min-width="150"
          />
          <el-table-column
            prop="create_time"
            label="创建时间"
            min-width="150"
          />
          <el-table-column
            prop="update_time"
            label="最新修改时间"
            min-width="150"
          />
          <el-table-column
            label="操作"
            min-width="200"
            fixed="right"
            align="center"
          >
            <template slot-scope="scope">
              <el-button
                type="text"
                size="small"
                @click="handleDetail(scope.row,false)"
                >详情
              </el-button>
              <el-button type="text" size="small" @click="handleDetail(scope.row,true)"
                >编辑
              </el-button>
              <el-button
                type="text"
                size="small"
                @click="handleDelete(scope.row.mer_type_id, scope.$index)"
                >删除
              </el-button>
              <!-- <el-button
                type="text"
                size="small"
                @click="handleMark(scope.row.mer_type_id)"
                >备注
              </el-button> -->
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
    <!-- 详情抽屉弹窗 -->
    <type-detail
      ref="typeDetail"
      :typeId="typeId"
      :isCreate="isCreate"
      :permissions="permissions"
      @getList="getList"
      @closeDrawer="closeDrawer"
      @changeDrawer="changeDrawer"
      :drawer="drawer"
    ></type-detail>
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
  storeTypeLstApi,
  storeTypeDeleteApi,
  storeJurisdictionApi,
  merchantTypeMarkForm,
} from "@/api/merchant";
import typeDetail from './typeDetails.vue';
import { fromList } from "@/libs/constants.js";
import { roterPre } from "@/settings";

export default {
  name: "MerchantList",
  components: { typeDetail },
  data() {
    return {
      fromList: fromList,
      roterPre: roterPre,
      listLoading: true,
      append: true,
      permissions: [], //店铺类型
      tableData: {
        data: [],
        total: 0,
      },
      tableFrom: {
        page: 1,
        limit: 20,
      },
      formValidate: {
        type_name: "",
        type_info: "",
        is_margin: 1,
        margin: 0,
        auth: [],
        description: "",
      },
      isEdit: false,
      isCreate: false,
      typeId: "",
      drawer: false
    };
  },

  mounted() {
    this.getList("");
  },
  methods: {
    // 列表
    getList(num) {
      this.listLoading = true;
      this.tableFrom.page = num ? num : this.tableFrom.page;
      storeTypeLstApi(this.tableFrom)
        .then((res) => {
          this.tableData.data = res.data.list;
          this.tableData.total = res.data.count;
          this.listLoading = false;
          this.jurisdiction();
        })
        .catch((res) => {
          this.listLoading = false;
          this.$message.error(res.message);
        });
    },
    pageChange(page) {
      this.tableFrom.page = page;
      this.getList("");
    },
    handleSizeChange(val) {
      this.tableFrom.limit = val;
      this.getList(1);
    },
    // 添加
    onAdd() {
      this.drawer = true;
      this.$refs.typeDetail.isEdit = true;
      this.isCreate = true;
      this.$refs.typeDetail.addType();
      this.$refs.tree && this.$refs.tree.setCheckedKeys([]);
    
    },
    //获取权限
    jurisdiction() {
      storeJurisdictionApi().then((res) => {
        function loadData(lst) {
          lst.forEach((v) => {
            v.value = v.id;
            v.label = v.title;
            delete v.id;
            delete v.title;
            if (v.children) {
              if (!v.children.length) {
                delete v.children;
              } else {
                loadData(v.children);
              }
            }
          });
          return lst;
        }
        this.permissions = loadData(res.data);
      });
    },
    // 详情
    handleDetail(row,edit) {
      this.typeId = row.mer_type_id;
      this.drawer = true;
      this.isEdit = edit;
      this.isCreate = false;
      this.$refs.typeDetail.getInfo(this.typeId,edit)
    },
    changeDrawer(v) {
      this.drawer = v;
    },
    closeDrawer() {
      this.drawer = false;
    },
    // 删除
    handleDelete(id) {
      this.$modalSure("确定删除该店铺类型吗").then(() => {
        storeTypeDeleteApi(id)
          .then(({ message }) => {
            this.$message.success(message);
            this.getList("");
          })
          .catch(({ message }) => {
            this.$message.error(message);
          });
      });
    },
    // 备注
    handleMark(id) {
      this.$modalForm(merchantTypeMarkForm(id)).then(() => this.getList());
    },
  },
};
</script>

<style scoped lang="scss">
.input_inline {
  /deep/ .el-input {
    width: 200px;
    margin-right: 5px;
  }
}
</style>
