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
        <div v-if="!isAdd" class="head">
          <div class="full">
            <img class="order_icon" :src="orderImg" alt="" />
            <div class="text">
              <div class="title">
                <span class="bold">{{ merData.mer_name }}</span>
                <el-tag v-if="merData.is_trader" type="danger" class="tags_name" effect="dark" size="mini">自营</el-tag>
                <el-tag v-if="merData.merchantType" type="warning" class="tags_name" effect="dark" size="mini">{{merData.merchantType.type_name}}</el-tag>
              </div>
              <div>
                <span class="mr20">{{ merData.mer_address }}</span>
              </div>
            </div>
            <div>
              <el-button
                v-if="isEdit"
                size="small"
                @click="cancelEdit"
                >取消</el-button
              >
              <el-button 
                v-if="!isEdit"
                type="primary"
                size="small"
                @click="merEdit"
                >编辑</el-button
              >
              <el-button
                v-if="isEdit"
                type="success"
                size="small"
                @click="saveInfo"
                >完成</el-button
              >
              <el-dropdown @command="handleCommand">
                <el-button icon="el-icon-more" size="small"></el-button>
                <el-dropdown-menu slot="dropdown">
                  <el-dropdown-item command="password">修改管理员密码</el-dropdown-item> 
                </el-dropdown-menu>
              </el-dropdown>
            </div>
          </div>
          <ul class="list">
            <li class="item">
              <div class="title">联系人</div>
              <div>{{merData.real_name}}</div>
            </li>
            <li class="item">
              <div class="title">联系电话</div>
              <div>{{ merData.mer_phone }}</div>
            </li>
            <li class="item">
              <div class="title">状态</div>
              <div>{{ merData.status==1 ? '开启' : '关闭' }}</div>
            </li>
            <li class="item">
              <div class="title">入驻时间</div>
              <div>{{ merData.create_time }}</div>
            </li>
          </ul>
        </div>
        <div v-else class="head">
          <div class="text">
            <div class="title">
              <span class="bold">添加商户</span>
            </div>
          </div>
        </div>
        <!--详情-->
        <merEditForm ref="editForm" :merId="merId" :isAdd="isAdd" :merData="merData" @modifyCopy="modifyCopy" @success="editSuccess" v-if="isEdit || isAdd"></merEditForm>
        <mer-info ref="merInfo" :merData="merData" v-else-if="!isEdit && !isAdd"></mer-info>
      </div>
      <div v-if="isAdd" class="footer">
        <el-button size="small" @click="handleClose">取消</el-button>
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
  merchantDetail,
} from '@/api/merchant';
import merInfo from './merInfo';
import merEditForm from './merEditForm';
export default {
  props: {
    drawer: {
      type: Boolean,
      default: false,
    },
  },
  components: { merInfo, merEditForm },
  data() {
    return {
      loading: true,
      merId: '',
      isEdit: false,
      isAdd: false,
      direction: 'rtl',
      activeName: 'detail',
      merData: {},
      orderImg: require('@/assets/images/store_icon.png'),

    };
  },
  filters: {
  },
  methods: {
    handleClose() {
      if(this.isEdit || this.isAdd) {
        this.$refs.editForm.resetData();
        this.$refs.editForm.activeName = 'detail';
      }else{
        this.$refs.merInfo.activeName = 'detail';
      }
      this.$emit('closeDrawer');
    },
    getInfo(id) { 
      this.merId = id
      this.isAdd = false;
      merchantDetail(id)
        .then((res) => {
          this.loading = false;
          this.drawer = true;
          this.merData = res.data;
        })
        .catch((res) => {
          this.$message.error(res.message);
        });
    },
    initData(){
      this.merData = {
        is_trader:0
      }
      this.isEdit = false;
      this.isAdd = true;
      this.loading = false;
    },
    merEdit(){
      this.isEdit = true;
      this.$nextTick(()=>{
        this.$refs.editForm.getCategorySelect();
        this.getInfo(this.merId);
      })
    },
    cancelEdit() {
      this.isEdit = false
    },
    // 编辑成功回调
    editSuccess(){
      if(this.isAdd){
        this.handleClose();
      }else{
        this.isEdit = false;
      }
      this.$emit('getList')
    },
    // 修改采集商品次数
    modifyCopy(){
      this.$emit('handleTimes',this.merId);
    },
     //下拉
    handleCommand() {
      this.$emit('onPassword',this.merId);
    },
    saveInfo(){
      this.$refs.editForm.onSubmit(this.merId);
      setTimeout(()=>{
        this.getInfo(this.merId);
      },500)
    },
    submitInfo(){
      this.$refs.editForm.handleCreate();
    }
  },
};
</script>
<style lang="scss" scoped>
.head {
  padding: 20px 20px 10px;
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
.footer{
  width: 100%;
  text-align: center;
  position: absolute;
  bottom: 17px;
  padding-top: 17px;
  border-top: 1px dashed #eeeeee;
}
</style>
