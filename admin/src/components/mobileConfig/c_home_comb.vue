<template>
  <div class="mobile-config">
    <el-form ref="formInline">
      <div v-for="(item, key) in rCom" :key="key">
        <component
          :is="item.components.name"
          :configObj="configObj"
          ref="childData"
          :configNme="item.configNme"
          :key="key"
          :index="activeIndex"
          :num="item.num"
        ></component>
      </div>
      <rightBtn :activeIndex="activeIndex" :configObj="configObj"></rightBtn>
    </el-form>
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
import toolCom from '@/components/mobileConfigRight/index.js';
import rightBtn from '@/components/rightBtn/index.vue';
export default {
  name: 'c_home_comb',
  componentsName: 'home_comb',
  cname: '组合组件',
  props: {
    activeIndex: {
      type: null,
    },
    num: {
      type: null,
    },
    index: {
      type: null,
    },
  },
  components: {
    ...toolCom,
    rightBtn,
  },
  data() {
    return {
      hotIndex: 1,
      configObj: {}, // 配置对象
      rCom: [
        {
          components: toolCom.c_tab,
          configNme: 'tabConfig',
        },
        {
          components: toolCom.c_upload_img,
          configNme: 'logoConfig',
        },
        {
          components: toolCom.c_hot_word,
          configNme: 'hotWords',
        },
        {
          components: toolCom.c_input_item,
          configNme: 'titleConfig',
        },
      ], // 当前页面组件
    };
  },
  watch: {
    num(nVal) {
      let value = JSON.parse(JSON.stringify(this.$store.state.mobildConfig.defaultArray[nVal]));
      this.configObj = value;
    },
    configObj: {
      handler(nVal, oVal) {
        this.$store.commit('mobildConfig/UPDATEARR', { num: this.num, val: nVal });
      },
      deep: true,
    },
    'configObj.tabConfig.tabVal': {
      handler(nVal, oVal) {
        if (nVal == 0) {
          let tempArr = [
            {
              components: toolCom.c_tab,
              configNme: 'tabConfig',
            },
            {
              components: toolCom.c_upload_img,
              configNme: 'logoConfig',
            },
            {
              components: toolCom.c_hot_word,
              configNme: 'hotWords',
            },
            {
              components: toolCom.c_input_item,
              configNme: 'titleConfig',
            },
          ];
          this.rCom = tempArr;
        } else if (nVal == 1) {
          let tempArr = [
            {
              components: toolCom.c_tab,
              configNme: 'tabConfig',
            },
            {
              components: toolCom.c_menu_list,
              configNme: 'listConfig',
            },
          ];
          this.rCom = tempArr;
        } else if (nVal == 2) {
          let tempArr = [
            {
              components: toolCom.c_tab,
              configNme: 'tabConfig',
            },
            {
              components: toolCom.c_menu_list,
              configNme: 'swiperConfig',
            },
          ];
          this.rCom = tempArr;
        }
      },
      deep: true,
    },
  },
  mounted() {
    this.$nextTick(() => {
      let value = JSON.parse(JSON.stringify(this.$store.state.mobildConfig.defaultArray[this.num]));
      this.configObj = value;
    });
  },
  methods: {},
};
</script>

<style scoped lang="scss">
.title-tips {
  padding-bottom: 10px;
  color: #333;
  span {
    margin-right: 14px;
    color: #999;
  }
}
</style>
