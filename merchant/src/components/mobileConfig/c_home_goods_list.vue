<template>
    <div class="mobile-config pro">
        <div  v-for="(item,key) in rCom" :key="key">
            <component :is="item.components.name" :configObj="configObj" ref="childData" :configNme="item.configNme" :key="key" @getConfig="getConfig" :index="activeIndex" :number="num" :num="item.num"></component>
        </div>
        <rightBtn :activeIndex="activeIndex" :configObj="configObj"></rightBtn>
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
import { getProduct } from '@/api/diy'
import { categorySelectApi } from '@/api/product'
import toolCom from '@/components/mobileConfigRight/index.js'
import rightBtn from '@/components/rightBtn/index.vue';
export default {
    name: 'c_home_goods_list',
    componentsName: 'home_goods_list',
    cname: '商品列表',
    props: {
        activeIndex: {
            type: null
        },
        num: {
            type: null
        },
        index: {
            type: null
        }
    },
    components: {
        ...toolCom,
        rightBtn
    },
    data () {
        return {
            configObj: {},
            rCom: [
                {
                    components: toolCom.c_set_up,
                    configNme: 'setUp'
                }
            ],
            automatic: [
                {
                    components: toolCom.c_tab,
                    configNme: 'tabConfig'
                },
                {
                    components: toolCom.c_cascader,
                    configNme: 'selectConfig'
                },
                {
                    components: toolCom.c_txt_tab,
                    configNme: 'goodsSort'
                },
                {
                    components: toolCom.c_input_number,
                    configNme: 'numConfig'
                },
                {
                    components: toolCom.c_is_show,
                    configNme: 'titleShow'
                },
                // {
                //     components: toolCom.c_is_show,
                //     configNme: 'opriceShow'
                // },
                {
                    components: toolCom.c_is_show,
                    configNme: 'priceShow'
                },
                {
                    components: toolCom.c_is_show,
                    configNme: 'couponShow'
                }
            ],
            manual: [
                {
                    components: toolCom.c_tab,
                    configNme: 'tabConfig'
                },
                {
                    components: toolCom.c_goods,
                    configNme: 'goodsList'
                },
                {
                    components: toolCom.c_is_show,
                    configNme: 'titleShow'
                },
                // {
                //     components: toolCom.c_is_show,
                //     configNme: 'opriceShow'
                // },
                {
                    components: toolCom.c_is_show,
                    configNme: 'priceShow'
                },
                {
                    components: toolCom.c_is_show,
                    configNme: 'couponShow'
                }
            ],
            setUp: 0,
            type: 0,
            lockStatus: false
        }
    },
    watch: {
        num (nVal) {
            let value = JSON.parse(JSON.stringify(this.$store.state.mobildConfig.defaultArray[nVal]))
            this.configObj = value;
            if(!value.selectConfig.list || !value.selectConfig.list[0].value){
                this.getCategory();
            }
        },
        configObj: {
            handler (nVal, oVal) {
                this.$store.commit('mobildConfig/UPDATEARR', { num: this.num, val: nVal });
            },
            deep: true
        },
        'configObj.setUp.tabVal': {
            handler (nVal, oVal) {
                this.setUp = nVal;
                var arr = [this.rCom[0]]
                if (nVal == 0) {
                    if(this.type){
                        this.rCom = arr.concat(this.manual)
                    }else {
                        this.rCom = arr.concat(this.automatic)
                    }
                } else {
                    let tempArr = [
                        {
                            components: toolCom.c_bg_color,
                            configNme: 'themeColor'
                        },
                        {
                            components: toolCom.c_bg_color,
                            configNme: 'fontColor'
                        },
                        {
                            components: toolCom.c_bg_color,
                            configNme: 'labelColor'
                        },
                        {
                            components: toolCom.c_txt_tab,
                            configNme: 'itemStyle'
                        },
                        {
                            components: toolCom.c_txt_tab,
                            configNme: 'bgStyle'
                        },
                        {
                            components: toolCom.c_txt_tab,
                            configNme: 'conStyle'
                        },
                        {
                            components: toolCom.c_slider,
                            configNme: 'mbConfig'
                        }
                    ]
                    this.rCom = arr.concat(tempArr)
                }
            },
            deep: true
        },
        'configObj.tabConfig.tabVal': {
            handler (nVal, oVal) {
                this.type = nVal;
                var arr = [this.rCom[0]];
                if(this.setUp == 0){
                    if (nVal == 0) {
                        this.rCom = arr.concat(this.automatic)
                    } else {
                        this.rCom = arr.concat(this.manual)
                    }
                }
            },
            deep: true
        },
        // 'configObj.itemStyle.type': {
        //     handler (nVal, oVal) {
        //         this.type = nVal;
        //         var arr = [this.rCom[0]];
        //         if(this.setUp == 0){
        //             if (nVal == 0) {
        //                 this.rCom = arr.concat(this.automatic)
        //             } else {
        //                 this.rCom = arr.concat(this.manual)
        //             }
        //         }
        //     },
        //     deep: true
        // }
    },
    mounted () {
        this.$nextTick(() => {
            let value = JSON.parse(JSON.stringify(this.$store.state.mobildConfig.defaultArray[this.num]))
            this.configObj = value;
            this.getCategory();
        })
    },
    methods: {
        getConfig (data) {
            if (!data.name && data == 1) {
                this.configObj.goodsList.list = []
                return
            }
            if (!data.name && data == 0 && !this.configObj.selectConfig.activeValue.length) {
                this.configObj.goodsList.list = []
                return
            }
            let activeValue = this.configObj.selectConfig.activeValue
            getProduct({
                // cate_pid: activeValue[activeValue.length-1],
                mer_cate_id: activeValue,
                page: 1,
                limit: this.configObj.numConfig.val,
                order: this.configObj.goodsSort.type == 2 ? 'price_asc' : this.configObj.goodsSort.type == 1 ? 'sales' : '',
            }).then(res => {
                this.configObj.productList.list = res.data.list;
            })
        },
        getCategory () {
            categorySelectApi().then(res => {
                this.$set(this.configObj.selectConfig,'list',res.data)
            })
        }
    }
}
</script>

<style scoped lang="scss">
    .pro{
        padding: 15px 15px 0;
        .tips{
            height: 50px;
            line-height: 50px;
            color: #999;
            font-size: 12px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
           
        .btn-box{
            padding-bottom: 20px;
        }
    }
        
        
        
</style>
