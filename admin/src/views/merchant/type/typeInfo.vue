<template>
  <el-tabs type="border-card" v-model="name">
    <el-tab-pane label="基本信息" name="basic">
      <div class="section">
        <div class="title">店铺类型详情</div>
        <ul class="list">
          <li class="item">
            <div>店铺类型名称：</div>
            <div class="value">
              {{typeData.type_name}}
            </div>
          </li>
          <li class="item">
            <div>店铺保证金：</div>
            <div class="value">{{typeData.is_margin ? typeData.margin+'元' : '无'}}</div>
          </li>
          <li class="item">
            <div>店铺类型要求：</div>
            <div class="value">{{typeData.type_info}}</div>
          </li>
          <li class="item">
            <div>已有店铺数量：</div>
            <div class="value">{{typeData.merchant_count}}</div>
          </li> 
          <li class="item">
            <div>其他说明：</div>
            <div class="value">{{typeData.description || "无"}}</div>
          </li>
          <li class="item">
            <div>创建时间：</div>
            <div class="value">{{typeData.create_time}}</div>
          </li>
          <li class="item">
            <div>最新修改时间：</div>
            <div class="value">{{typeData.update_time}}</div>
          </li>
        </ul>
      </div>
    </el-tab-pane>
    <el-tab-pane label="权限信息" name="permission">
      <div class="section">
        <div class="title">权限管理</div>
        <ul class="list">
          <li class="item">
            <div>权限范围：</div>
            <div class="value">
              <el-tree
                ref="treeDetail"
                :data="typeData.options"
                :props="{ label: 'title' }"
              ></el-tree>
            </div>
          </li>
        </ul>
      </div>
    </el-tab-pane>
        
  </el-tabs> 
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
export default {
  props: {
    typeData: {
      type: Object,
      default: {},
    },
    activeName: {
      type: String,
      default: "basic",
    }
  },
  data() {
    return {
      loading: true,
      typeId: '',
      direction: 'rtl',
      name: this.activeName
    };
  },
  filters: {
  },
  methods: {

  },
};
</script>
<style lang="scss" scoped>
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
    margin-top: 5px;
  }
  .item {
    flex: 0 0 calc(100% / 2);
    display: flex;
    margin-top: 16px;
    font-size: 13px;
    color: #606266;
    &.item100{
     flex: 0 0 calc(100% / 1);
     padding-right: 20px;
     padding-left: 20px;
    }
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
/deep/.el-drawer__body {
  overflow: auto;
}
</style>
