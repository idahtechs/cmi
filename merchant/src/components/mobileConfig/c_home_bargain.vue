<template>
    <div class="mobile-config">
        <div  v-for="(item,key) in rCom" :key="key">
            <component :is="item.components.name" :configObj="configObj" ref="childData" @getConfig="getConfig" :key="key" :configNme="item.configNme"></component>
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
import toolCom from '@/components/mobileConfigRight/index.js'
import rightBtn from '@/components/rightBtn/index.vue';
import { mapMutations } from 'vuex'
export default {
    name: 'c_home_bargain',
    componentsName: 'home_bargain',
    components: {
        ...toolCom,
        rightBtn
    },
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
    data () {
        return {
            configObj: {},
            rCom: [
                {
                    components: toolCom.c_set_up,
                    configNme: 'setUp'
                }
            ],
            oneStyle: [
                {
                    components: toolCom.c_is_show,
                    configNme: 'priceShow'
                },
                {
                    components: toolCom.c_is_show,
                    configNme: 'bntShow'
                },
                {
                    components: toolCom.c_is_show,
                    configNme: 'titleShow'
                },
                {
                    components: toolCom.c_is_show,
                    configNme: 'barginShow'
                    },
                {
                    components: toolCom.c_is_show,
                    configNme: 'joinShow'
                }
            ],
            twoStyle: [
                {
                    components: toolCom.c_is_show,
                    configNme: 'priceShow'
                },
                {
                    components: toolCom.c_is_show,
                    configNme: 'bntShow'
                },
                {
                    components: toolCom.c_is_show,
                    configNme: 'titleShow'
                }
            ],
            type: 0
        }
    },
    watch: {
        num (nVal) {
            this.configObj = this.$store.state.mobildConfig.defaultArray[nVal]
        },
        configObj: {
            handler (nVal, oVal) {
                this.$store.commit('mobildConfig/UPDATEARR', { num: this.num, val: nVal });
            },
            deep: true
        },
        'configObj.setUp.tabVal': {
            handler (nVal, oVal) {
                var arr = [this.rCom[0]]
                if (nVal == 0) {
                    if(this.type == 2){
                        this.rCom = arr.concat(this.twoStyle)
                    }else {
                        this.rCom = arr.concat(this.oneStyle)
                    }
                } else {
                    let tempArr = [
                        {
                            components: toolCom.c_tab,
                            configNme: 'tabConfig'
                        },
                        {
                            components: toolCom.c_bg_color,
                            configNme: 'bgColor'
                        },
                        {
                            components: toolCom.c_bg_color,
                            configNme: 'themeColor'
                        },
                        {
                            components: toolCom.c_bg_color,
                            configNme: 'priceColor'
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
                            configNme: 'mbCongfig'
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
                    if (nVal == 2) {
                        this.rCom = arr.concat(this.twoStyle)
                    } else {
                        this.rCom = arr.concat(this.oneStyle)
                    }
                }
            },
            deep: true
        }
    },
    mounted () {
        this.$nextTick(() => {
            let value = JSON.parse(JSON.stringify(this.$store.state.mobildConfig.defaultArray[this.num]))
            this.configObj = value;
        })
    },
    methods: {
        // 获取组件参数
        getConfig (data) {
        },
        handleSubmit (name) {
            let obj = {}
            obj.activeIndex = this.activeIndex
            obj.data = this.configObj
            this.add(obj);
        },
        ...mapMutations({
            add: 'mobildConfig/UPDATEARR'
        })
    }
}
</script>

<style scoped>

</style>
