<template>
  <el-form
    ref="formValidate"
    size="small"
    :rules="ruleValidate"
    :model="formValidate"
    label-width="120px"
    @submit.native.prevent
    >
    <el-tabs v-loading="loading" type="border-card" v-model="name">
      <el-tab-pane label="基本信息" name="basic">
        <div class="section">
          <div class="title">店铺类型详情</div>
          <el-row class="mt20">
            <el-col :span="12">
              <el-form-item label="店铺类型名称：" prop="type_name">
                <el-input
                  v-model="formValidate.type_name"
                  placeholder="请填写店铺类型名称"
                  class="selWidth"
                />
              </el-form-item>
            </el-col>
            <el-col :span="12">
              <el-form-item label="店铺保证金：">
                <el-radio-group v-model="formValidate.is_margin">
                  <el-radio :label="0" class="radio">无</el-radio>
                  <el-radio :label="1">有</el-radio>
                </el-radio-group>
                <span v-if="formValidate.is_margin == 1">
                  <el-input
                    v-model="formValidate.margin"
                    placeholder="请填写保证金"
                    style="width:120px;margin-left: 8px;"
                  /> 元
                </span>  
              </el-form-item>
            </el-col>
          </el-row>
          <el-row>
            <el-col :span="12">
              <el-form-item label="店铺类型要求：">
                <el-input
                v-model="formValidate.type_info"
                type="textarea"
                :rows="3"
                placeholder="请填写店铺类型要求"
                class="selWidth"
              />
              </el-form-item>
            </el-col>
            <el-col :span="12">
              <el-form-item label="其他说明：">
                <el-input
                v-model="formValidate.description"
                type="textarea"
                :rows="3"
                placeholder="请填写其他说明"
                class="selWidth"
              />
              </el-form-item>
            </el-col>
          </el-row>
          <el-row>
            <el-col :span="12">
              <el-form-item label="备注：">
                <el-input
                v-model="formValidate.mark"
                type="textarea"
                :rows="3"
                placeholder="请填写备注"
                class="selWidth"
              />
              </el-form-item>
            </el-col>
          </el-row>
          <el-row>
            <el-col v-if="!create" :span="24">
              <el-form-item label="已有店铺数量：">
               <span>{{formValidate.merchant_count}}</span>
              </el-form-item>
            </el-col>
          </el-row>
          <el-row>
            <el-col v-if="!create" :span="24">
              <el-form-item label="创建时间：">
               <span>{{formValidate.create_time}}</span>
              </el-form-item>
            </el-col>
          </el-row>
          <el-row>
            <el-col v-if="!create" :span="24">
              <el-form-item label="最新修改时间：">
               <span>{{formValidate.update_time}}</span>
              </el-form-item>
            </el-col>
          </el-row>
        </div>
      </el-tab-pane>
      <el-tab-pane label="权限信息" name="permission">
      <div class="section">
        <div class="title">权限管理</div>
         <el-form-item prop="auth">
          <el-tree
              ref="tree"
              :data="permissions"
              show-checkbox
              node-key="value"
              :default-checked-keys="formValidate.auth || []"
              @hook:mounted="$refs.tree.setCheckedKeys(formValidate.auth || [])"
              @check="
                [
                  (formValidate.auth = $refs.tree.getCheckedKeys()),
                  $refs.formValidate.validateField('auth'),
                ]
              "
            ></el-tree>
          </el-form-item>
      </div>
    </el-tab-pane>  
  </el-tabs>   
</el-form>
  
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
import { storeTypeUpdateApi, storeTypeCreateApi  } from "@/api/merchant";
export default {
  props: {
    formValidate: {
      type: Object,
      default: {},
    },
    permissions: {
      type: Array,
      default: [false],
    },
    activeName: {
      type: String,
      default: "basic",
    },
    isCreate: {
      type: Boolean,
      default: false
    }
  }, 
  data() {
    return {
      loading: false,
      merId: '',
      direction: 'rtl',
      name: 'basic',
      create: this.isCreate,
      ruleValidate: {
        type_name: [
          { required: true, message: '请输入店铺类型', trigger: 'blur' }
        ],
        auth: [
          { required: true, message: "请选择店铺权限", trigger: "change" },
        ],
      },
    };
  },
 
  filters: {
  },
  mounted() {
  },
  methods: {
    /*提交信息*/
    onSubmit(id){
      this.$refs['formValidate'].validate(valid => {
        if (valid) {
          this.loading = true;
          storeTypeUpdateApi(id, this.formValidate)
            .then(async (res) => {
              this.$message.success(res.message);
              this.loading = false;
              this.$emit('success');
            })
            .catch((res) => {
              this.$message.error(res.message);
              this.loading = false;
            });
        } 
      });
    },
    /**重置表单验证 */
    resetForm(){
      this.$refs.formValidate.resetFields();
    },
    /**添加类型初始化 */
    addType(){
      this.loading = false;
    },
    /**编辑初始化 */
    editInit() {
      this.name = 'basic'
      this.setChecked(); 
    },
    /**根据返回数据选中权限 */
    setChecked(){
      this.$refs.tree && this.$refs.tree.setCheckedKeys(this.formValidate.auth);
    },
    handleCreateType(name){
      this.$refs[name].validate((valid) => {
        if (valid) {
          storeTypeCreateApi(this.formValidate)
            .then(async (res) => {
              this.$message.success(res.message);
              this.$emit('success');
            })
            .catch((res) => {
              this.$message.error(res.message);
            });
        } else {
          
          return false;
        }
      });
    }
  },
};
</script>
<style lang="scss" scoped>
.selWidth{
  width: 100%;
}
.el-tabs--border-card {
  box-shadow: none;
  border-bottom: none;
}
.section {
  padding: 20px 0 5px;
  border-bottom: 1px dashed #eeeeee;
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
.info-red{
  color: red;
  font-size: 12px;
}
/deep/.el-input-number.is-controls-right .el-input__inner{
 padding: 0 40px 0 10px;
}
/deep/.el-form-item__label{
  font-weight: normal;
  color: #282828;
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
.gary {
  color: #aaa;
}

</style>
 