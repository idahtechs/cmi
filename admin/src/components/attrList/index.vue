<template>
    <div class="label-wrapper">
				<div class="list-box">
					<div class="label-box" v-for="(item,index) in attrs" :key="index">
					    <div class="title">{{item.value}}</div>
					    <div class="list">
					      <div class="label-item" :class="label.select?'on':''" v-for="(label,j) in item.details" :key="j" @click="selectAttr(label,j)">{{label.name}}</div>
					    </div>
					</div>
				</div>
        <div class="footer">
			<el-button type="primary" plain size="mini" class="btns" @click="cancel">取消</el-button>
			<el-button type="primary" plain size="mini" class="btns" @click="reset">重置</el-button>
      <el-button type="primary" size="mini" class="btns" @click="subBtn">确定</el-button>
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
			name: "attrList",
			props:{
				attrs: {
					type: Array,
					default: function () {
						return [];
					}
				}
			},
			data(){
				return {
				}
			},
			mounted() {
			},
      methods:{
				selectAttr(label,index){
					label.select = !label.select;
					this.$emit('activeData',JSON.parse(JSON.stringify(this.attrs)))
				},
				cancel(){
					this.$emit('close')
				},
				reset(){
					let data = this.attrs;
					data.map(el=>{
						el.details.map(label=>{
							label.select = false
						})
					})
					this.attrs = data;
				},
				subBtn(){
					this.$emit('subAttrs')
				}
      }
    }
</script>
<style lang="scss" scoped>
.label-wrapper{
	 .list{
		 display: flex;
		 flex-wrap: wrap;
		 .label-item{
			 margin: 10px 8px 10px 0;
			 padding: 3px 14px;
			 background: #EEEEEE;
			 color: #333333;
			 border-radius: 2px;
			 cursor: pointer;
			 font-size: 12px;
			 border: 1px solid #EEEEEE;
			 &.on{
				 color: #1890FF;
				 border-color: #1890FF;
				 background-color: #fff;
			 }
		 }
	 }
	 .footer{
		 display: flex;
		 justify-content: flex-end;
		 margin-top: 40px;
		 button{
			  margin-left: 10px;
		 }
	 }
}
.btn{
	width: 60px;
	height: 24px;
}	
.title{
	font-size: 13px;
}
.list-box{
	overflow-y: auto;
	overflow-x: hidden;
	max-height: 240px;
}	
</style>
