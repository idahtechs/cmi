<template>
    <div class="slider-box">
        <div class="c_row-item">
            <el-col class="label" :span="4" v-if="configData.title">
                {{configData.title}}
            </el-col>
            <el-col :span="18" class="slider-box">
                <el-slider v-model="configData.val" show-input @change="sliderChange($event)" :min="configData.min"></el-slider>
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
    name: 'c_slider',
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
            sliderWidth: 0,
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
        color: #999;
    }    
}    
</style>
