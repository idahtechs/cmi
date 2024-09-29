<template>
    <div v-if="configData" class="slider-box">
        <div class="c_row-item">
            <el-col class="label" :span="24" v-if="configData.text">
                <div :style="'color:'+configData.color" class="text">{{configData.text}}</div>
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
    name: 'c_text',
    props: {
        configObj: {
            type: Object
        },
        configNme: {
            type: String
        }
    },
    data () {
        return {
            defaults: {},
            configData: {}
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
        }
    },
    methods: {
        sliderChange (e) {
        }
    }
}
</script>

<style scoped lang="scss">
.c_row-item{
    margin-bottom: 20px;
    .label{
       font-size: 13px;
    }    
}    
</style>
