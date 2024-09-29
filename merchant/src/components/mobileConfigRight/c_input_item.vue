<template>
    <div class="box" v-if="configData">
        <div class="c_row-item">
            <el-col class="label" :span="4">
                {{configData.title}}
            </el-col>
            <el-col :span="19" class="slider-box">
                <div>
                    <el-input :icon="configData.title=='链接'?'ios-arrow-forward':''" :readonly="configData.title=='链接'?true:false" v-model="configData.value" :placeholder="configData.place" :maxlength="configData.max">
                        <el-button v-if="configData.title=='链接'" slot="append" icon="el-icon-arrow-right" @click="getLink(configData.title)"></el-button>
                    </el-input>
                </div>
            </el-col>
        </div>
        <linkaddress ref="linkaddres" @linkUrl="linkUrl"></linkaddress>
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
import linkaddress from '@/components/linkaddress';
export default {
    name: 'c_input_item',
    props: {
        configObj: {
            type: Object
        },
        configNme: {
            type: String
        }
    },
    components: {
        linkaddress
    },
    data () {
        return {
            value: '',
            defaults: {},
            configData: {}
        }
    },
    created () {
        this.defaults = this.configObj
        this.configData = this.configObj[this.configNme]
    },
    watch: {
        configObj: {
            handler (nVal, oVal) {
                this.defaults = nVal
                this.configData = nVal[this.configNme]
            },
            immediate: true,
            deep: true
        }
    },
    methods: {
        linkUrl(e){
            this.configData.value = e
        },
        getLink (title){
            if(title!='链接'){
                return
            }
            // this.$refs.linkaddres.modals = true
            this.$refs.linkaddres.init();
        }
    }
}
</script>

<style scoped lang="scss">
/deep/.ivu-input{
    font-size: 13px!important;
}   
.c_row-item{
    margin-bottom: 13px;
}
    
</style>
