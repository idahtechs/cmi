<template>
    <div class="slider-box">
        <div class="c_row-item">
            <el-col class="label" :span="4" v-if="configData.title">
                {{configData.title}}
            </el-col>
            <el-col :span="19" class="slider-box">
                <Select v-model="configData.activeValue" @change="sliderChange">
                    <Option v-for="(item,index) in configData.list" :value="item.activeValue" :key="index">{{ item.title }}</Option>
                </Select>
            </el-col>
        </div>
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
export default {
    name: 'c_select',
    props: {
        configObj: {
            type: Object
        },
        configNme: {
            type: String
        },
        number: {
            type: null
        },
    },
    data () {
        return {
            defaults: {},
            configData: {},
            timeStamp: ''
        }
    },
    mounted () {
        this.$nextTick(() => {
            this.defaults = this.configObj
            this.configData = this.configObj[this.configNme]
        })
    },
    watch: {
        configObj: {
            handler (nVal, oVal) {
                this.defaults = nVal
                this.configData = nVal[this.configNme]
            },
            deep: true
        },
        number (nVal) {
            this.timeStamp = nVal;
        },
    },
    methods: {
        sliderChange (e) {
            let storage = window.localStorage;
            this.configData.activeValue = e?e:storage.getItem(this.timeStamp);
            this.$emit('getConfig', { name: 'select', values: e })
        }
    }
}
</script>

<style scoped lang="scss">
    .c_row-item{
        margin-top: 20px;
        margin-bottom: 20px;
    }
</style>
