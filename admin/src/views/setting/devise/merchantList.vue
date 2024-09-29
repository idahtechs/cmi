<template>
  <div class="divBox">
    <el-row class="ivu-mt box-wrapper">
      <el-col class="right-wrapper">
        <el-card>
          <el-row>
            <el-col style="width: 310px;height:550px;margin-right: 30px;position: relative">
              <iframe class="iframe-box" :src="imgUrl" frameborder="0" ref="iframe"></iframe>
              <!-- <div class="mask"></div> -->
            </el-col>
            <el-col class="table">
              <div class="acea-row row-between-wrapper">
                <el-row type="flex">
                  <el-col>
                    <div class="acea-row row-between-wrapper">
                      <div class="button acea-row row-middle">
                        <el-button type="primary" size="small" @click="add" style="font-size: 12px;"><i class="el-icon-plus" style="margin-right: 4px;"/>添加</el-button>
                      </div>
                      <div style="color:rgb(246, 44, 44);font-size: 13px;">&nbsp;&nbsp;注：初次进入该页面，可点击[添加]创建店铺首页模板，点击[适用范围] 可将该模板指定给相应商家使用。</div>
                    </div>
                  </el-col>
                </el-row>
              </div>
              <el-table
                class="tables"
                :data="list"
                ref="table"
                highlight-current-row
                size="mini"
                v-loading="loading" 
                border  
              >
                <el-table-column prop="id" label="页面ID" min-width="80" />
                <el-table-column prop="name" label="模板名称" min-width="100" />
                <el-table-column prop="add_time" label="添加时间" min-width="100" />
                <el-table-column prop="update_time" label="更新时间" min-width="100" />
                <el-table-column label="操作" min-width="150">
                  <template slot-scope="scope">
                    <el-button type="text" size="small" @click="edit(scope.row)">编辑</el-button>
                    <el-button type="text" size="small" @click="del(scope.row.id, scope.$index)">删除</el-button>
                    <el-button type="text" size="small" @click="preview(scope.row)">预览</el-button>
                    <el-button type="text" size="small" @click="onDiyCopy(scope.row)">复制</el-button>
                    <el-button type="text" size="small" @click="setRange(scope.row.id)">适用范围</el-button>
                  </template>
                </el-table-column>
              </el-table>
               <div class="block">
                <el-pagination
                  :page-sizes="[20, 40, 60, 80]"
                  :page-size="diyFrom.limit"
                  :current-page="diyFrom.page"
                  layout="total, sizes, prev, pager, next, jumper"
                  :total="total"
                  @size-change="handleSizeChange"
                  @current-change="pageChange"
                />
                </div>
            </el-col>
          </el-row>
        </el-card>
      </el-col>
    </el-row>
    <!--设置使用范围-->
     <el-dialog
        v-if="dialogVisible"
        :visible.sync="dialogVisible"
        width="900px"
        title="适用范围"
        custom-class="dialog-scustom"
        class="addDia"
      >
      <el-form
        ref="formValidate"
        v-loading="rangeLoading"
        :model="formValidate"
        :rules="ruleValidate"
        label-width="100px"
        class="formValidate mt20"
        @submit.native.prevent
      >
        <el-form-item label-width="0">
          <el-radio-group v-model="formValidate.scope_type" @change="getChange()">
            <el-radio :label="4">全部店铺使用</el-radio>
            <el-radio :label="0">指定店铺使用</el-radio>
            <el-radio :label="1">指定商户分类使用</el-radio>
            <el-radio :label="2">指定店铺类型使用</el-radio>
            <el-radio :label="3">指定商户类别使用</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item v-if="formValidate.scope_type == 0" label="店铺名称："  prop="scope_value">
          <el-select v-model="formValidate.scope_value" clearable filterable multiple placeholder="请选择" class="selWidth">
            <el-option v-for="item in merSelect" :key="item.mer_id" :label="item.mer_name" :value="item.mer_id" />
          </el-select>
        </el-form-item>
        <el-form-item v-if="formValidate.scope_type == 1" label="商户分类："  prop="scope_value">
           <el-select
            v-model="formValidate.scope_value"
            multiple
            filterable
            placeholder="请选择"
            class="filter-item selWidth mr20"
            clearable
          >
            <el-option
              v-for="item in merCateList"
              :key="item.merchant_category_id"
              :label="item.category_name"
              :value="item.merchant_category_id"
            />
          </el-select>
        </el-form-item>
        <el-form-item v-if="formValidate.scope_type == 2" label="店铺类型：" prop="scope_value">
          <el-select v-model="formValidate.scope_value" clearable filterable multiple placeholder="请选择" class="selWidth">
            <el-option v-for="item in storeType" :key="item.value" :label="item.label" :value="item.value"/>
          </el-select>
        </el-form-item>
        <el-form-item v-if="formValidate.scope_type == 3" label="商户类别：" prop="scope_value">
          <el-select v-model="formValidate.scope_value" clearable filterable multiple placeholder="请选择" class="selWidth">
            <el-option v-for="item in traderList" :key="item.value" :label="item.label" :value="item.value"/>
          </el-select>
        </el-form-item>
      </el-form>
       <span slot="footer" class="dialog-footer">
        <el-button type="primary" @click="submitForm">提交</el-button>
      </span>
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
import SettingMer from '@/libs/settingMer'
import { roterPre } from '@/settings'
import { merchantDiyList, merchantDiyDel, merchantDiyCopy, getMerchantScope, setMerchantScope } from "@/api/diy";
import { merCategoryListApi, merSelectApi } from "@/api/product";
import { getstoreTypeApi } from "@/api/merchant";
import { mapState } from "vuex";
export default {
  name: "devise_list",
  computed: {
    ...mapState('layout', [
      'menuCollapse'
    ])
  },
  components: {},
  data() {
    return {
      loading: false,
      dialogVisible: false,
      roterPre: roterPre,
      list: [],
      imgUrl:'',
      modal: false,
      BaseURL: SettingMer.httpUrl || 'http://localhost:8080',
      rangeLoading: false,
      diyFrom: {
        page: 1,
        limit: 20
      },
      total: 0,
      mer_id: '',
      did: 0,
      scope_id: "",
      isPreview: false,
      merSelect: [],
      merCateList: [],
      storeType: [],
      traderList: [
        {label: "自营", value: 1},
        {label: "非自营", value: 0}
      ],
      formValidate: {
        scope_type: 4,
        scope_value: []
      },
      ruleValidate: {
        scope_value: [
          { required: true, message: '请选择', trigger: 'change' }
        ],
      }
    };
  },
  created() {
    this.getList();
    this.getMerSelect();
    this.getCategorySelect();
    this.getStoreType()

  },
  mounted: function() {

  },
  methods: {
    // 预览
    preview(row){
      this.isPreview = true
      let time = new Date().getTime() * 1000
      // let imgUrl = `${this.BaseURL}/pages/admin/storeDiy/index?id=${this.mer_id}&diy_id=${row.id}&inner_frame=1&time=${time}`;
      let imgUrl = `${this.BaseURL}/pages/admin/storeDiy/index?id=0&diy_id=${row.id}&inner_frame=1&time=${time}`;
      this.imgUrl = imgUrl;
    },
    // 使用范围详情
    setRange(id){
      this.dialogVisible = true;
      getMerchantScope(id).then(res => {
        this.scope_id = id
        this.formValidate = res.data
      }).catch(res => {
        this.$message.error(res.message)
      })
    },
    // 设置适用范围
    submitForm(){
      this.$refs['formValidate'].validate(valid => {
        if (valid) {
          setMerchantScope(this.scope_id,this.formValidate).then(res => { 
            this.$message.success(res.message)
            setTimeout(() => {
              this.dialogVisible = false;
            }, 300);
          }).catch(res => {
            this.$message.error(res.message)
          })
        }else{
          return false;
        }
      })
    },
    // 切换使用范围
    getChange(){
      this.formValidate.scope_value = []
    },
    // 商户列表；
    getMerSelect() {
      merSelectApi().then(res => {
        this.merSelect = res.data
      }).catch(res => {
        this.$message.error(res.message)
      })
    },
    // 商户分类；
    getCategorySelect() {
      merCategoryListApi().then(res => {
        this.merCateList = res.data.list
      }).catch(res => {
        this.$message.error(res.message)
      })
    },
    // 获取店铺类型
    getStoreType(){
      getstoreTypeApi().then(res => {
        this.storeType = res.data
      }).catch(res => {
        this.$message.error(res.message)
      })
    },
    // 获取列表
    getList() {
      this.loading = true;
      merchantDiyList(this.diyFrom).then((res) => {
        this.loading = false;
        let data = res.data;
        this.list = data.list;
        this.total = data.count;
        this.did = data.list[0]['id'];
        let storage = window.localStorage;
        this.imgUrl = storage.getItem('imgUrl');
        let time = new Date().getTime() * 1000
        let imgUrl = `${this.BaseURL}/pages/admin/storeDiy/index?id=0&diy_id=${this.did}&inner_frame=1&time=${time}`;
        storage.setItem('imgUrl',imgUrl)
        this.imgUrl = imgUrl;
      });
    },
    pageChange(status) {
      this.diyFrom.page = status;
      this.getList();
    },
    handleSizeChange(val) {
      this.diyFrom.limit = val
      this.getList()
    },   
    // 编辑
    edit(row) {
      this.$router.push({
        path: `${roterPre}/setting/merchant/diy`,
        query: { id: row.id, name: row.template_name || "moren", types: 2, store: 1 },
      });
    },
    // 添加
    add() {
      this.$router.push({
        path: `${roterPre}/setting/merchant/diy`,
        query: { id: 0, name: "首页", types: 2, store: 1 },
      });
    },
    // 删除
    del(id,idx) {
      this.$modalSure('删除模板吗').then(() => {
        merchantDiyDel(id).then(({ message }) => {
          this.$message.success(message)
          this.getList()
        }).catch(({ message }) => {
          this.$message.error(message)
        })
      })
    }, 
    onDiyCopy(row) {
      merchantDiyCopy(row.id).then(() => {
        this.getList()
      }).catch(res => {
        this.$message.error(res.message);
      })
    }
  },
};
</script>

<style scoped lang="scss">
  /* 用来设置当前页面element全局table 选中某行时的背景色*/
  .el-table__body tr.current-row>td{
    background-color: #69A8EA !important;
  }
  .diy-title{
    text-align: center;
    font-weight: bold;
    color:rgb(246, 44, 44);
  }
  .product_tabs{
    padding: 16px 32px;
    background: #fff;
    border-bottom: 1px solid #e8eaec;
    text-align: right;
  }
  .el-menu-item{
    height: 47px;
  }
  .el-menu-item.is-active::after{
    content: "";
    display: block;
    width: 2px;
    position: absolute;
    top: 0;
    bottom: 0;
    right: 0;
    background: #2d8cf0;
  }
  .tables{
    margin-top: 20px;
  }
  .ivu-mt{
    background-color: #fff;
    padding-bottom: 50px;
  }
  .bnt{
    width: 80px!important;
  }
  .iframe-box{
    width: 100%;
    height: 100%;
    border-radius: 10px;
    border: 1px solid #eee;
  }
  .mask{
    position: absolute;
    left:0;
    width: 100%;
    top:0;
    height: 100%;
    background-color: rgba(0,0,0,0);
  }
  @media (max-width: 2600px){
    .table {
      display: block;
      flex: 0 0 70%;
      max-width: 70%;
    }
  }
  @media (max-width: 2175px){
    .table {
      display: block;
      flex: 0 0 75%;
      max-width: 75%;
    }
  }
  @media (max-width: 2010px){
    .table {
      display: block;
      flex: 0 0 75%;
      max-width: 73%;
    }
  }
  @media (max-width: 1860px){
    .table {
      display: block;
      flex: 0 0 70%;
      max-width: 70%;
    }
  }
  @media (max-width: 1597px){
    .table {
      display: block;
      flex: 0 0 65%;
      max-width: 65%;
    }
  }
  @media (max-width: 1413px){
    .table {
      display: block;
      flex: 0 0 60%;
      max-width: 60%;
    }
  }
  @media (max-width: 1275px){
    .table {
      display: block;
      flex: 0 0 55%;
      max-width: 55%;
    }
  }
  @media (max-width: 1168px){
    .table {
      display: block;
      flex: 0 0 48%;
      max-width: 48%;
    }
  }
  .code{
    position: relative;
  }
  .QRpic {
    width: 160px;
    height: 160px;

    img {
      width: 100%;
      height: 100%;
    }
  }
  .left-wrapper {
    background: #fff;
    border-right: 1px solid #dcdee2;
  }
  .picCon{
    width: 280px;
    height: 510px;
    background: #FFFFFF;
    border: 1px solid #EEEEEE;
    border-radius: 25px;
    .pictrue{
      width: 250px;
      height: 417px;
      border: 1px solid #EEEEEE;
      opacity: 1;
      border-radius: 10px;
      margin: 30px auto 0 auto;
      img{
        width: 100%;
        height: 100%;
        border-radius: 10px;
      }
    }
    .circle{
      width: 36px;
      height: 36px;
      background: #FFFFFF;
      border: 1px solid #EEEEEE;
      border-radius: 50%;
      margin: 13px auto 0 auto;
    }
  }
</style>
