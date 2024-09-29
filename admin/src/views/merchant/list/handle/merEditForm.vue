<template>
  <el-form
    ref="merDataField"
    size="small"
    :rules="ruleValidate"
    :model="merData"
    label-width="130px"
    @submit.native.prevent
    >
    <el-tabs v-loading="loading" type="border-card" v-model="activeName">
      <el-tab-pane label="基本信息" name="detail">
        <div class="section">
          <div class="title">基础信息</div>
          <el-row :gutter="24" class="mt20">
            <el-col :span="12">
              <el-form-item label="商户名称：" prop="mer_name">
                <el-input
                  v-model="merData.mer_name"
                  placeholder="请填写商户名称"
                  class="selWidth"
                />
              </el-form-item>
            </el-col>
            <el-col :span="12">
              <el-form-item label="商户地址：" prop="mer_address">
                <el-input
                  v-model="merData.mer_address"
                  placeholder="请填写详细地址"
                  class="selWidth"
                />
              </el-form-item>
            </el-col>
          </el-row>
          <el-row>
            <el-col :span="12">
              <el-form-item label="商户分类：" prop="category_id">
               <el-select
                  v-model="merData.category_id"
                  placeholder="请选择"
                  class="selWidth"
                >
                  <el-option
                    v-for="item in merCateList"
                    :key="item.merchant_category_id"
                    :label="item.category_name"
                    :value="item.merchant_category_id"
                  />
                </el-select>
              </el-form-item>
            </el-col>
            <el-col :span="12">
              <el-form-item label="商户类型：" prop="is_trader">
                <el-radio-group
                  v-model="merData.is_trader"
                >
                  <el-radio :label="1" class="radio">自营</el-radio>
                  <el-radio :label="0">非自营</el-radio>
                </el-radio-group>
              </el-form-item>
            </el-col>
          </el-row>
          <el-row>
            <el-col :span="12">
              <el-form-item label="店铺类型：" prop="type_id">
                <el-select
                  v-model="merData.type_id"
                  placeholder="请选择"
                  class="selWidth"
                >
                  <el-option
                    v-for="item in storeType"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value"
                  />
                </el-select>
              </el-form-item>
            </el-col>
            <el-col :span="12">
              <el-form-item label="推荐商户：" prop="is_best">
                <el-switch
                  v-model="merData.is_best"
                  :active-value="1"
                  :inactive-value="0"
                  :width="50"
                  active-text="是"
                  inactive-text="否"
                />
              </el-form-item>
            </el-col>
          </el-row>
          <el-row>
            <el-col :span="12">
              <el-form-item label="排序：" prop="sort">
                <el-input-number
                  v-model="merData.sort"
                  controls-position="right"
                  placeholder="请输入排序"
                />
              </el-form-item>
            </el-col>
            <el-col :span="12">
              <el-form-item label="商户状态：" prop="status">
                <el-switch
                  v-model="merData.status"
                  :active-value="1"
                  :inactive-value="0"
                  :width="55"
                  active-text="开启"
                  inactive-text="关闭"
                />
              </el-form-item>
            </el-col>
          </el-row>
          <el-row>
            <el-col :span="12">
              <el-form-item label="备注：" prop="mark">
                <el-input
                type="textarea"
                  v-model="merData.mark"
                  placeholder="请填写备注"
                  class="selWidth"
                />
              </el-form-item>
            </el-col>
          </el-row>
        </div>
      </el-tab-pane>
      <el-tab-pane label="经营信息" name="operate">
        <div class="section">
          <div class="title">费用信息</div>
          <el-row :gutter="24" class="mt20">
            <el-col v-if="!isAdd" :span="24">
              <el-form-item label="店铺保证金：" prop="ot_margin">
                <span>{{merData.is_margin == 0 ? '无' : merData.ot_margin+'元'}}</span>
              </el-form-item>
            </el-col>
            <el-col v-if="!isAdd && merData.is_margin != 0" :span="24">
              <el-form-item label="保证金支付状态：">
                <span>{{merData.is_margin == 1 ? '待缴' : merData.is_margin == 0 ? '无' : '已缴' }}</span>
              </el-form-item>
            </el-col>
            <el-col v-if="!isAdd && merData.is_margin != 0" :span="24">
              <el-form-item label="保证金余额：">
                <span>{{merData.margin}}</span>
              </el-form-item>
            </el-col>
            <el-col :span="24">
              <el-form-item label="商户手续费单独设置：" label-width="150px" prop="commission_rate">
                <el-switch
                  v-model="merData.commission_switch"
                  :active-value="1"
                  :inactive-value="0"
                  :width="55"
                  active-text="开启"
                  inactive-text="关闭"
                />
                <span class="info info-red">(注：此处如未设置手续费，系统会自动读取商户分类下对应手续费；此处已设置，则优先以此处设置为准)</span>
              </el-form-item>
            </el-col>
            <el-col v-if="merData.commission_switch" :span="24">
              <el-form-item label="" prop="commission_rate">
                <el-input-number
                 :min="0"
                  v-model="merData.commission_rate"
                  controls-position="right"
                  placeholder="请输入排序"
                />%
              </el-form-item>
            </el-col>
           
          </el-row>
        </div>
        <div class="section">
          <div class="title">审核信息</div>
           <el-row :gutter="24" class="mt20">
            <el-col :span="8">
              <el-form-item label="商品审核：" prop="is_audit">
                <el-switch
                  v-model="merData.is_audit"
                  :active-value="1"
                  :inactive-value="0"
                  :width="50"
                  active-text="是"
                  inactive-text="否"
                />
              </el-form-item>
            </el-col>
            <el-col :span="8">
              <el-form-item label="直播间审核：" prop="is_bro_room">
                <el-switch
                  v-model="merData.is_bro_room"
                  :active-value="1"
                  :inactive-value="0"
                  :width="50"
                  active-text="是"
                  inactive-text="否"
                />
              </el-form-item>
            </el-col>
            <el-col :span="8">
              <el-form-item label="直播间商品审核：" prop="is_bro_goods">
                <el-switch
                  v-model="merData.is_bro_goods"
                  :active-value="1"
                  :inactive-value="0"
                  :width="50"
                  active-text="是"
                  inactive-text="否"
                />
              </el-form-item>
            </el-col>
           </el-row>
        </div>
        <div class="section">
          <div class="title">其他信息</div>
          <div class="mt20">
            <el-row :gutter="24">
              <el-col :span="12">
                <el-form-item label="商户关键字：" prop="mer_keyword">
                  <el-input
                    v-model="merData.mer_keyword"
                    placeholder="请填写商户关键字"
                    class="selWidth"
                  />
                </el-form-item>
              </el-col>
           </el-row>
           <el-row v-if="!isAdd" :gutter="24">
            <el-col :span="12" style="position: relative;">
              <el-form-item label="商品采集数：">
                <el-input v-model="merData.copy_product_num" disabled ></el-input>
                <el-button type="text" @click="modifyCopy" style="margin-left: 10px;position:absolute;right: -30px;">修改</el-button>
              </el-form-item>
            </el-col>
           </el-row>
          </div> 
        </div>
      </el-tab-pane>
      <el-tab-pane label="账号信息" name="account">
        <div class="section">
          <div class="title">登录账号</div>
           <el-row :gutter="24" class="mt20">
            <el-col :span="12">
              <el-form-item label="商户账号：" prop="mer_account">
                <el-input
                  type="text"
                  v-model="merData.mer_account"
                  :disabled="!isAdd"
                  class="selWidth"
                />
              </el-form-item>
            </el-col>
            <el-col :span="12">
              <el-form-item label="登陆密码：" prop="mer_password">
                <el-input
                  type="password"
                  v-model="merData.mer_password"
                  :disabled="!isAdd"
                  class="selWidth"
                />
              </el-form-item>
            </el-col>
            <el-col v-if="!isAdd" :span="12">
              <el-form-item label="联系人：" prop="real_name">
                <el-input
                  type="text"
                  placeholder="请填写联系人"
                  v-model="merData.real_name"
                  class="selWidth"
                />
              </el-form-item>
            </el-col>
            <el-col :span="12">
              <el-form-item label="联系电话：" prop="mer_phone">
                <el-input
                  placeholder="请填写联系电话"
                  v-model="merData.mer_phone"
                  class="selWidth"
                />
              </el-form-item>
            </el-col>
           </el-row>
        </div>
        <div class="section">
          <div class="title">财务帐号</div>
           <el-row :gutter="24" class="mt20">
            <el-col :span="12">
              <el-form-item label="分账商户号：" prop="sub_mchid">
                <el-input
                  placeholder="请填写分账商户号"
                  v-model="merData.sub_mchid"
                  class="selWidth"
                />
              </el-form-item>
            </el-col>
            <el-col :span="24">
              <div class="info-red" style="margin-left: 120px;">当开启自动分账时，每个子商户在微信后台的分账商户号，即特约子商户号</div>
            </el-col>
           </el-row>
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
import { merCategoryListApi } from '@/api/product'
import { getstoreTypeApi, merchantUpdate, merchantCreate } from "@/api/merchant";
export default {
  props: {
    merData: {
      type: Object,
      default: {},
    },
    isAdd: {
      type: Boolean,
      default: false,
    }
  }, 
  data() {
    const validatePhone = (rule, value, callback) => {
      if (!value) {
        return callback(new Error('请填写联系方式'))
      } else if (!/^1[3456789]\d{9}$/.test(value)) {
        callback(new Error('格式不正确!'))
      } else {
        callback()
      }
    }
    return {
      loading: false,
      merId: '',
      direction: 'rtl',
      activeName: 'detail',
      merCateList: [],
      storeType: [],
      ruleValidate: {
        mer_name: [
          { required: true, message: '请输入商户名称', trigger: 'blur' }
        ],
        mer_account: [
          { required: true, message: '请输入商户账号', trigger: 'blur' }
        ],
        category_id: [
          { required: true, message: '请选择商户分类', trigger: 'change' }
        ],
        type_id: [
          { required: true, message: '请选择店铺类型', trigger: 'change' }
        ],
        mer_phone: [{ required: true, validator: validatePhone, trigger: 'blur' }],
      },
    };
  },
  filters: {
  },
  mounted() {  
    this.getCategorySelect();
    this.getStoreType()
  },
  methods: {
    // 商户分类；
    getCategorySelect() {
      merCategoryListApi().then(res => {
        this.merCateList = res.data.list
      }).catch(res => {
        this.$message.error(res.message)
      })
    },
    /**获取店铺类型 */
    getStoreType(){
      getstoreTypeApi().then(res => {
        this.storeType = res.data
      }).catch(res => {
        this.$message.error(res.message)
      })
    },
    /**修改采集次数 */
    modifyCopy() {
      this.$emit('modifyCopy')
    },
    /**重置表单数据 */
    resetData(){
      this.$refs.merDataField.resetFields();
    },
    /*提交信息*/
    onSubmit(id){
      this.$refs['merDataField'].validate(valid => {
        if (valid) {
          this.loading = true;
          merchantUpdate(id,this.merData)
          .then(async res => {
            this.$message.success(res.message);
            this.loading = false;
            this.$emit('success');
          })
          .catch(res => {
            this.loading = false;
            this.$message.error(res.message);
          });
        } 
      });
    },
    /**创建商户 */
    handleCreate(){
      this.$refs['merDataField'].validate((valid) => {
        if (valid) {
          merchantCreate(this.merData)
            .then(async (res) => {
              this.$message.success(res.message);
              this.$emit('success');
            })
            .catch((res) => {
              this.$message.error(res.message);
            });
        } else {
          if(!this.merData.mer_name)return this.$message.error('请填写基本信息-商户名称');
          if(!this.merData.category_id)return this.$message.error('请选择基本信息-商户分类');
          if(!this.merData.type_id)return this.$message.error('请选择基本信息-店铺类型');
          if(!this.merData.mer_account)return this.$message.error('请填写账号信息-商户账号');
          if(!this.merData.mer_phone)return this.$message.error('请填写账号信息-联系电话');
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
 