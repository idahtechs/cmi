<template>
  <div>
    <el-drawer
      :with-header="false"
      :size="1000"
      :visible.sync="drawer"
      :direction="direction"
      :before-close="handleClose" 
    >
      <div v-loading="loading">
        <div v-if="create" class="head" style="padding: 20px 10px 15px;">
          <div class="full">           
            <div class="text">
              <div class="title">
                <span class="bold">添加店铺类型</span>
              </div>
            </div>        
          </div>
        </div>
        <div v-else class="head">
          <div class="full">
            <img class="order_icon" :src="orderImg" alt="" />
            <div class="text">
              <div class="title">
                <span class="bold">{{ typeData.type_name }}</span>
              </div>
              <div>
                <span class="mr20">保证金：{{ typeData.is_margin ? typeData.margin+'元' : '无' }}</span>
              </div>
            </div>
             <div>
              <el-button
                v-if="isEdit"
                size="small"
                @click="cancel"
                >取消</el-button
              >
              <el-button 
                v-if="!isEdit && !create"
                type="primary"
                size="small"
                @click="typeEdit"
                >编辑</el-button
              >
              <el-button
                v-if="isEdit && !create"
                type="success"
                size="small"
                @click="saveInfo"
                >完成</el-button
              >
            </div>
          </div>
        </div>
         <!--编辑-->
        <typeEditForm ref="editForm" :typeId="typeId" :isCreate="create" :activeName="activeName" :permissions="permissions" :formValidate="formValidate" @success="saveSuccess" @handleClose="handleClose" v-if="isEdit || create"></typeEditForm>
         <!--详情-->
        <type-info ref="typeInfo" :typeData="typeData" :activeName="activeName" v-else></type-info>  
      </div>
      <div v-if="create" class="footer">
        <el-button type="primary" size="small" @click="submitInfo">提交</el-button>
      </div> 
    </el-drawer> 
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
  merchantTypeDetailApi,
} from '@/api/merchant';
import typeInfo from './typeInfo';
import typeEditForm from './typeEditForm';
export default {
  props: {
    drawer: {
      type: Boolean,
      default: false,
    },
    isCreate: {
      type: Boolean,
      default: false,
    },
    permissions: {
      type: Array,
      default: [false],
    }
  },
  components: {typeInfo, typeEditForm},
  data() {
    return {
      loading: true,
      typeId: '',
      direction: 'rtl',
      activeName: "basic",
      isEdit: false,
      typeData: {},
      create: this.isCreate,
      orderImg: require('@/assets/images/type_icon.png'),
      formValidate: {
        type_name: "",
        type_info: "",
        is_margin: 1,
        margin: 0,
        auth: [],
        description: "",
        update_time: "",
        create_time: "",
      },
    };
  },
  filters: {
  },
  methods: {
    handleClose() {
      this.$emit('closeDrawer');
    },
    saveSuccess(){
      this.activeName = 'basic';
      this.$emit('closeDrawer');
      setTimeout(() =>{
        this.$emit('getList')
      },100)
    },
    getInfo(id,edit) { 
      this.typeId = id 
      this.isEdit = edit;
      this.create = false; 
      this.loading = true;
      merchantTypeDetailApi(id)
        .then((res) => {
          this.loading = false;
          if(this.isEdit)this.$refs.editForm.resetForm();
          this.typeData = res.data;
          let info = res.data;
          this.formValidate = {
            type_name: info.type_name,
            type_info: info.type_info,
            mark: info.mark,
            is_margin: info.is_margin || 0,
            margin: info.margin || 0,
            auth: info.auth_ids,
            description: info.description,
            update_time: info.update_time,
            create_time: info.create_time,
            merchant_count: info.merchant_count || 0
          };
          this.$nextTick(()=>{
            this.$refs.editForm.name = 'basic';
            this.$refs.editForm.setChecked();
          }) 
         
        })
        .catch((res) => {
          this.loading = false;
          this.$message.error(res.message);
        });
    },
    //编辑
    typeEdit(){
      this.isEdit = true;
      this.$refs.editForm.editInit()
    },
    /**添加类型初始化 */
    addType(){
      this.loading = false;
      this.create = true;
      // this.formValidate = {
      //   type_name: "",
      //   type_info: "",
      //   is_margin: 0,
      //   margin: 0,
      //   auth: [],
      //   description: "",
      //   update_time: "",
      //   create_time: "",
      // };
      this.$nextTick(()=>{
        this.$refs.editForm.name = 'basic';
        this.$refs.editForm.resetForm();
        this.$refs.editForm.editInit()
      }) 
    },
    // 取消
    cancel(){
      if(this.create){
        this.drawer = false;
      }else{
        this.isEdit = false;
      }
    },
    // 保存
    saveInfo(){
      this.$refs.editForm.onSubmit(this.typeId);
    },
    submitInfo(){
      this.$refs.editForm.handleCreateType('formValidate');
    }
  },
};
</script>
<style lang="scss" scoped>
.head {
  padding: 30px 20px;
  .full {
    display: flex;
    align-items: center;
    .order_icon {
      width: 60px;
      height: 60px;
    }
    .text {
      align-self: center;
      flex: 1;
      min-width: 0;
      padding-left: 12px;
      font-size: 13px;
      color: #606266;
      .title {
        margin-bottom: 10px;
        font-weight: 500;
        font-size: 16px;
        line-height: 16px;
        color: #282828;
      }
    }
  }
  .bold{
    font-weight: bold;
  }
  .list {
    display: flex;
    margin-top: 20px;
    overflow: hidden;
    list-style: none;
    padding: 0;
    .item {
      flex: none;
      width: 200px;
      font-size: 14px;
      line-height: 14px;
      color: rgba(0, 0, 0, 0.85);
      .title {
        margin-bottom: 12px;
        font-size: 13px;
        line-height: 13px;
        color: #666666;
      }
    }
  }
}
.el-tabs--border-card {
  box-shadow: none;
  border-bottom: none;
}
.section {
  padding: 20px 0 5px;

  .title {
    padding-left: 10px;
    border-left: 3px solid #437FFD;
    font-size: 15px;
    line-height: 15px;
    color: #303133;
  }
  .list {
    display: flex;
    flex-wrap: wrap;
    list-style: none;
    padding: 0;
  }
  .item {
    flex: 0 0 calc(100% / 2);
    display: flex;
    margin-top: 16px;
    font-size: 13px;
    color: #606266;

    &:nth-child(2n + 1) {
      padding-right: 20px;
      padding-left: 20px;
    }
    &:nth-child(2n) {
     padding-right: 20px;
    }
  }
  .value {
    flex: 1;
    image {
      display: inline-block;
      width: 40px;
      height: 40px;
      margin: 0 12px 12px 0;
      vertical-align: middle;
    }
  }
}
.tab {
  display: flex;
  align-items: center;
  .el-image {
    width: 36px;
    height: 36px;
    margin-right: 10px;
  }
}
/deep/.el-drawer__body {
  overflow: auto;
}
.footer{
  width: 100%;
  text-align: center;
  position: absolute;
  bottom: 17px;
  padding-top: 17px;
  border-top: 1px dashed #eeeeee;
}
</style>
