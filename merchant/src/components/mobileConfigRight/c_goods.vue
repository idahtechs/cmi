<template>
    <div class="goods-box" v-if="configObj.goodsList">
        <div class="wrapper">
            <draggable
                class="dragArea list-group"
                :list="configObj.goodsList.list"
                group="peoples"
            >
            <div class="item" v-for="(goods,index) in configObj.goodsList.list" :key="index" v-if="configObj.goodsList.list.length">
                <img :src="goods.image" alt="">
                <span class="iconfont-diy icondel_1" @click.stop="bindDelete(index)"></span>
            </div>
                <div class="add-item item" @click="modals = true"><span class="iconfont-diy iconaddto"></span></div>
            </draggable>
        </div>
        <el-dialog :visible.sync="modals" title="商品列表" class="paymentFooter" scrollable width="900px" :before-close="cancel">
            <goods-list ref="goodslist" :ischeckbox="true" :isdiy="true"  @getProductId="getProductId" v-if="modals"></goods-list>
        </el-dialog>
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
import vuedraggable from 'vuedraggable'
import goodsList from '@/components/goodsList'
export default {
    name: 'c_goods',
    props: {
        configObj: {
            type: Object
        }
    },
    components: {
        goodsList,
        draggable: vuedraggable
    },
    watch: {
        configObj: {
            handler (nVal, oVal) {
                this.configObj = nVal
            },
            immediate: true,
            deep: true
        }
    },
    data () {
        return {
            modals: false,
            goodsList: [],
            tempGoods: {},
            defaults:{}
        }
    },
    created () {
        this.defaults = this.configObj
    },
    methods: {
        //对象数组去重；
        unique(arr) {
            const res = new Map();
            return arr.filter((arr) => !res.has(arr.product_id) && res.set(arr.product_id, 1))
        },
        getProductId (data) {   
            this.modals = false;
            let list = this.configObj.goodsList.list.concat(data);
            this.configObj.goodsList.list = this.unique(list);
            this.configObj.goodsList.ids = this.configObj.goodsList.list.map(item => {
                return item.product_id
            })
        },
        cancel () {
            this.modals = false;
            // this.tempGoods = {}
        },
        ok () {
            this.configObj.goodsList.list.push(this.tempGoods)
        },
        bindDelete (index) {
            this.configObj.goodsList.list.splice(index, 1)
        }
    }
}
</script>

<style scoped lang="scss">
    .goods-box{
        padding: 16px 0;
        margin-bottom: 16px;
        border-top: 1px solid rgba(0,0,0,0.05);
        .wrapper,.list-group{
            display: flex;
            flex-wrap: wrap;
        }      
        .add-item{
            display: flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            margin-bottom: 10px;
            background: #F7F7F7;
            .iconfont-diy{
                font-size: 20px;
                color: #D8D8D8;
            }        
        }
        .item{
            position: relative;
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
            margin-right: 12px;
            img{
                width: 100%;
                height: 100%;
            }      
            .icondel_1{
                position: absolute;
                right: -10px;
                top: -16px;
                color: #999999;
                font-size: 28px;
                cursor: pointer;
            }
                
        }
    }
        
            
</style>
