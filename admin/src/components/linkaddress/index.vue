<template>
  <div class="divBox">
    <el-dialog v-if="modals" :visible.sync="modals" title="选择链接" width="860px" :before-close="cancel">
			<div class="table_box">
				<div class="left_box">
					<el-tree 
						:data="categoryData" 
						default-expand-all 
						node-key="id"
						:highlight-current="true" 
						:expand-on-click-node="false"
						:current-node-key="treeId"
						@node-click="handleCheckChange">
					</el-tree>
				</div>
				<div class="right_box" v-if="currenType=='link'">
					<div v-for="(item,index) in currentList" :key="index">
						<div class="cont">{{item.name}}</div>
						<div class="Box">
							<div class="cont_box" :class="currenId==itemn.id?'on':''" v-for="(itemn,indexn) in item.pageLink" :key="indexn" @click="getUrl(itemn)">{{itemn.name}}</div>
						</div>
					</div>
				</div>
				<div class="right_box" v-if="currenType=='special' || currenType=='product_category' || currenType=='points_cate' || currenType=='product' || currenType=='seckill' ||currenType=='presell' || currenType == 'bargain' || currenType == 'combination' || currenType == 'news' || currenType == 'active' || currenType == 'merchant' || currenType == 'micro'">
					<el-form
						ref="formValidate"
						:model="formValidate"
						class="tabform"
						label-width="80px"
						v-if="currenType=='product'"
					>
						<el-row type="flex">
							<el-col>
								<el-form-item label="商品分类：">
									<el-cascader
										v-model="formValidate.cate_id"
										class="selWidth"
										:options="merCateList"
										:props="props"
										clearable
										size="small"
										@change="userSearchs()"
									/>  
								</el-form-item>
							</el-col>
							<el-col>
								<el-form-item label="搜索：">
									<el-input placeholder="请输入商品名称,关键字,编号" size="small" class="selWidth" v-model="formValidate.store_name" @keyup.enter.native="userSearchs"/>
								</el-form-item>
							</el-col>
						</el-row>
					</el-form>
					<el-table
						ref="table"
						size="mini"
						:data="tableList"
						v-loading="loading"
						:row-key="(row) => { return row.id }"
						:default-expand-all="false"
						:tree-props="{children: 'children', hasChildren: 'hasChildren'}"				
					>
						<el-table-column key="1" label="选择" min-width="60">
							<template scope="scope">
								<el-radio v-model="radio" :label="scope.$index"  @change.native="getCurrentRow(scope.row)" />
							</template>
						</el-table-column>
						<el-table-column key="2" prop="id" label="ID" min-width="60">
						<template slot-scope="scope">
							<span>{{scope.row.id || scope.row.product_id || scope.row.group_data_id || scope.row.mer_id || scope.row.article_id || scope.row.store_category_id}}</span>
						</template>
						</el-table-column>
						<el-table-column v-if="currenType=='special' || currenType=='micro'" key="3" :prop="currenType=='special' ? 'title' : 'name'" label="页面名称" min-width="100" />
						<el-table-column v-if="currenType=='special'" key="4"  prop="url" label="页面链接" min-width="150" />
						<el-table-column v-if="currenType=='product_category' || currenType=='points_cate'" key="5"  prop="cate_name" label="分类名称" min-width="60" />
						<el-table-column v-if="currenType=='product_category'" key="6" label="分类图标" min-width="150">
							<template slot-scope="scope">
								<div class="demo-image__preview">
									<el-image style="width: 36px; height: 36px" :src="scope.row.pic" :preview-src-list="[scope.row.pic]" />
								</div>
						</template>
						</el-table-column>
						<el-table-column v-if="currenType=='bargain'||currenType=='combination'||currenType=='presell'||currenType=='seckill'||currenType=='product'" key="7" label="商品图片" min-width="80">
							<template slot-scope="scope">
								<div class="demo-image__preview">
									<el-image style="width: 36px; height: 36px" :src="scope.row.image || (scope.row.product&&scope.row.product.image)" :preview-src-list="[scope.row.image || (scope.row.product&&scope.row.product.image)]" />
								</div>
							</template>
						</el-table-column>
						<el-table-column v-if="currenType=='bargain' || currenType=='combination'||currenType=='presell'||currenType=='seckill'||currenType=='product'" key="8"  prop="store_name" label="商品名称" min-width="150" />
						<el-table-column v-if="currenType=='news'" key="9" label="文章图片" min-width="150">
							<template slot-scope="scope">
								<div class="demo-image__preview">
									<el-image style="width: 36px; height: 36px" :src="scope.row.image_input" :preview-src-list="[scope.row.image_input]" />
								</div>
							</template>
						</el-table-column>
						<el-table-column v-if="currenType=='active'" key="10" label="专场图片" min-width="150">
							<template slot-scope="scope">
								<div class="demo-image__preview">
									<el-image style="width: 36px; height: 36px" :src="scope.row.value && scope.row.value.image" :preview-src-list="[scope.row.value && scope.row.value.image]" />
								</div>
							</template>
						</el-table-column>
						<el-table-column v-if="currenType=='merchant'" key="11" label="商户头像" min-width="150">
							<template slot-scope="scope">
								<div class="demo-image__preview">
									<el-image style="width: 36px; height: 36px" :src="scope.row.mer_avatar" :preview-src-list="[scope.row.mer_avatar]" />
								</div>
							</template>
						</el-table-column>
						<el-table-column v-if="currenType=='news'" key="12"  prop="title" label="文章名称" min-width="60" />
						<el-table-column v-if="currenType=='active'" key="13"  prop="value.name" label="专场名称" min-width="150" />
						<el-table-column v-if="currenType=='merchant'" key="14"  prop="mer_name" label="商户名称" min-width="150" />
						<el-table-column v-if="currenType !=='merchant' && currenType !=='active' && currenType !=='news' && currenType !=='bargain' && currenType !=='combination' && currenType !=='product_category' && currenType!=='points_cate' && currenType !=='special' && currenType !=='micro'"  key="15" label="商品图片" min-width="80">
							<template slot-scope="scope">
								<div class="demo-image__preview">
									<el-image style="width: 36px; height: 36px" :src="scope.row.image" :preview-src-list="[scope.row.image]" />
								</div>
							</template>
						</el-table-column>
						<el-table-column v-if="currenType=='micro'" key="16"  prop="add_time" label="创建时间" min-width="150" />
						<el-table-column v-if="currenType=='micro'" key="17"  prop="update_time" label="更新时间" min-width="150" />
					</el-table>
					<div class="block" v-if="currenType=='product'||currenType=='seckill'||currenType=='micro'||currenType=='presell'||currenType == 'bargain'||currenType == 'combination'||currenType == 'news'||currenType == 'active'||currenType == 'merchant'">
						<el-pagination
						:page-sizes="[15, 30, 45, 60]"
						:page-size="formValidate.limit"
						:current-page="formValidate.page"
						layout="total, sizes, prev, pager, next, jumper"
						:total="total"
						@size-change="handleSizeChange"
						@current-change="pageChange"
						/>
        </div>
				</div>
				<div class="right_box" v-if="currenType=='custom'">
					<div style="width: 360px;margin: 150px 100px 0 120px">
						<el-form ref="customdate" :model="customdate" :rules="ruleValidate" label-width="120px">	
							<el-form-item label-width="0">
								<el-radio-group
									v-model="customdate.status"
								>
									<el-radio :label="1" class="radio">普通链接</el-radio>
									<el-radio :label="2">跳转其他小程序</el-radio>
								</el-radio-group>
							</el-form-item>
							<el-form-item v-if="customdate.status == 1" label="跳转路径：" prop="url">
								<el-input v-model="customdate.url" size="small" placeholder="请输入正确跳转路径"></el-input>
							</el-form-item>
							<div v-if="customdate.status == 2">
								<el-form-item label="APPID：" prop="appid">
									<el-input v-model="customdate.appid" size="small" placeholder="请输入正确APPID"></el-input>
								</el-form-item>
								<el-form-item label="小程序路径：" prop="mpUrl">
									<el-input v-model="customdate.mpUrl" size="small" placeholder="请输入正确小程序路径"></el-input>
								</el-form-item>
								<el-form-item label-width="0">
									<div>路径示例：merchant/devise/diy/index?id=184</div>
								</el-form-item>
								<div style="color:#ff4949;">小程序跳转外链链接，域名需在白名单范围内的合法域名</div>
							</div>
						</el-form>
					</div>	
				</div>
			</div>
			<div slot="footer">
				<el-button @click="cancel">取消</el-button>
				<el-button type="primary" @click="handleSubmit('customdate')" v-if="currenType=='custom'">确定</el-button>
				<el-button type="primary" @click="ok" v-else>确定</el-button>
			</div>
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
	import { pageCategory, pageLink, getPageLinks, getProduct } from "@/api/diy";
	import { categoryListApi } from "@/api/product";
	import { articleLstApi } from '@/api/cms';
    export default {
      name: 'linkaddress',
      data () {
      return {
        modals: false,
				categoryData:[],
				currenType:'link',
				type: this.$store.state.settings.storeDiy,
				radio: '',
				props: {
					emitPath: false
				},
				merCateList: [],
				formValidate: {
					page: 1,
					limit: 15,
					cate_id: "",
					store_name: ""
				},
				total: 0,
				currentList:[],
				currenId:'',
				currenUrl:'',
				loading:false,
				tableList:[],
				presentId:0,
				categoryId:'',//左侧分类id
				customdate: {
					name:'',
					mpUrl:'',
					url: '',					
					status: 1
				},
				customNum:1,
				treeId: 0,
				ruleValidate:{
					name: [{ required: true, message: '请输入链接名称', trigger: 'blur' }],
					mpUrl: [{ required: true, message: '请输入跳转路径', trigger: 'blur' }],
        	appid: [{ required: true, message: '请输入APPID', trigger: 'blur' }]
				}
      }
    },
		computed: {},
		created(){
	
      this.getSort();
			this.goodsCategory();
		},
    methods: {
			getCurrentRow(row) { // 获取选中数据
				row.checked = true
				this.presentId = row.id;
				this.currenUrl = row.url;
			 },
			getCustomList(){
				pageLink(this.categoryId,{type:this.type}).then(res=>{
					if(!res.data.list || !res.data.list.length){
						this.customNum = 2
					}
					this.tableList = res.data.list
				}).catch(err=>{
					this.$message.error(err.message)
				})
			},
			handleSubmit (name) {
				this.$refs[name].validate((valid) => {
					if (valid) {
						let url = this.customdate.url;
						if (this.customdate.status == 1) {
							url = this.customdate.url;
						} else {
							url = this.customdate.mpUrl + '@APPID=' + this.customdate.appid;
						}
						this.$emit('linkUrl', url);
						this.modals = false;
						this.reset();
					} else {
						this.$message.error('请填写信息');
					}
				})
			},
			pageChange(index) {
				this.formValidate.page = index;
				this.getList();
			},
			handleSizeChange(val) {
				this.formValidate.limit = val
				this.getList()
			},
			// 商品分类；
			goodsCategory() {
				categoryListApi()
					.then((res) => {
						this.merCateList = res.data;
					})
					.catch((res) => {
						this.$message.error(res.message);
					});
			},
			// 表格搜索
			userSearchs() {
				this.formValidate.page = 1;
				this.getList();
			},
      reset(){
				this.currenUrl = "";
				this.presentId = 0;
				this.currenId = "";
				this.radio = "";
				this.customdate.status == 1;
				this.customdate.url="";
				this.customdate.mpUrl="";
			},
			getUrl(item){
				this.currenId = item.id;
				this.currenUrl = item.url;
			},
      getSort(){
				pageCategory({type: this.type}).then(res=>{
				if(res.data[0].children[0]){
					res.data[0].children[0].selected = true;
					this.treeId = res.data[0]['children'][0]['id']
					this.handleCheckChange(res.data[0].children[0]);
				}else{
					this.handleCheckChange(res.data[0]);
					this.treeId = res.data[0]&&res.data[0]['id']
				}
				this.categoryData = res.data;
				}).catch(err=>{
					this.$message.error(err.message)
				})
			},
			getList(){
				this.loading = true;
				if(this.currenType == 'product'){
					let formValidate = this.formValidate
					formValidate.product_type = 0;
					getProduct(formValidate)
							.then(async (res) => {
								let data = res.data;
								data.list.forEach((e)=>{
									e.url = `/pages/goods_details/index?id=${e.product_id}`
								});
								this.tableList = data.list;
								this.total = res.data.count;
								this.loading = false;
							})
							.catch((res) => {
								this.loading = false;
								this.$message.error(res.message);
							});
				}else if(this.currenType == 'seckill'){
					let formValidate = this.formValidate
					formValidate.product_type = 1;
					getProduct(formValidate)
							.then(async (res) => {
								let data = res.data;
								data.list.forEach((e)=>{
									e.url = `/pages/activity/goods_seckill_details/index?id=${e.product_id}`
								});
								this.tableList = data.list;
								this.total = res.data.count;
								this.loading = false;
							})
							.catch((res) => {
								this.loading = false;
								this.$message.error(res.message);
							});
				}else if(this.currenType == 'bargain'){
					let formValidate = this.formValidate
					formValidate.product_type = 3;
					getProduct(formValidate)
							.then(async (res) => {
								let data = res.data;
								data.list.forEach((e)=>{
									e.url = `/pages/activity/assist_details/index?id=${e.activity_id}`
								});
								this.tableList = data.list;
								this.total = res.data.count;
								this.loading = false;
							})
							.catch((res) => {
								this.loading = false;
								this.$message.error(res.message);
							});
				}else if(this.currenType == 'combination'){
					let formValidate = this.formValidate
					formValidate.product_type = 4;
					getProduct(formValidate)
							.then(async (res) => {
								let data = res.data;
								data.list.forEach((e)=>{
									e.url = `/pages/activity/combination_details/index?id=${e.activity_id}`
								});
								this.tableList = data.list;
								this.total = res.data.count;
								this.loading = false;
							})
							.catch((res) => {
								this.loading = false;
								this.$message.error(res.message);
							});
				}else if(this.currenType == 'presell'){
					let formValidate = this.formValidate
					formValidate.product_type = 2;
					getProduct(formValidate)
							.then(async (res) => {
								let data = res.data;
								data.list.forEach((e)=>{
									e.url = `/pages/activity/presell_details/index?id=${e.activity_id}`
								});
								this.tableList = data.list;
								this.total = res.data.count;
								this.loading = false;
							})
							.catch((res) => {
								this.loading = false;
								this.$message.error(res.message);
							});
				}else if(this.currenType == 'news'){
					articleLstApi(this.formValidate).then(async res => {
						let data = res.data
						data.list.forEach((e)=>{
						  e.url=`/pages/news_details/index?id=${e.id}`
						});
						this.tableList = data.list;
						this.total = data.count;
						this.loading = false;
					}).catch(res => {
						this.loading = false;
						this.$message.error(res.message);
					})
				} else if (this.currenType == 'active' || this.currenType == 'merchant') {
					pageLink(this.categoryId, {
						page: this.formValidate.page,
						limit: this.formValidate.limit,
						type: this.type
					}).then(res=>{
						this.loading = false
						let data = res.data;
						this.currentList = data;
						if(this.currenType == 'special'){
							let list = []
							data.forEach((e)=>{
								e.url = `/pages/news_details/index?id=${e.article_id}`
								list.push(e)
							});
							this.tableList = list
						}else if(this.currenType == 'product_category'){
							data.forEach((e)=>{
								if(e.hasOwnProperty('children')){
									e.children.forEach((j)=>{
										j.url = `/pages/columnGoods/goods_list/index?id=${j.store_category_id}&title=${j.cate_name}`
										if(j.hasOwnProperty('children')){
											j.children.forEach((h)=>{
												h.url = `/pages/columnGoods/goods_list/index?id=${h.store_category_id}&title=${h.cate_name}`
											})
										}
									})
								}
								e.url = `/pages/columnGoods/goods_list/index?id=${e.store_category_id}&title=默认`
							});
							this.tableList = data
						}else if(this.currenType == 'active'){
							data.list.forEach(item => {
								item.id = item.group_data_id
								item.url = `/pages/activity/topic_detail/index?id=${item.group_data_id}`
							})
							this.tableList = data.list
							this.total = res.data.count
						}else if(this.currenType == 'merchant'){
							data.list.forEach(item => {
								item.id = item.mer_id
								item.url = `/pages/store/home/index?id=${item.mer_id}`
							})
							this.tableList = data.list
							this.total = res.data.count
						}
					}).catch(err=>{
						this.loading = false
						this.$message.error(err.message)
					});
				}
			},
			handleCheckChange(data){
				this.reset();
        let id = ''
				this.currenType = data.type
				if(data.pid){
					id = data.id
					this.categoryId = data.id
				}else {
					return false
				}
				this.loading = true
				this.currenType = data.type
				this.formValidate.page = 1
				if(this.currenType == 'product' || this.currenType == 'seckill' || this.currenType == 'presell' || this.currenType == 'bargain' || this.currenType == 'combination' || this.currenType == 'news'){
					this.getList();
				} else if(this.currenType=='custom'){
					this.getCustomList();
				}else {
					let obj = {
						type: this.type
					};
					if (this.currenType == 'active' || this.currenType == 'merchant') {
						obj.page = this.formValidate.page
						obj.limit = this.formValidate.limit
						
					}
					pageLink(id, obj).then(res=>{
						this.loading = false
						let data = res.data;
						this.currentList = data;
						if(this.currenType == 'special'){
							let list = []
							data.list.forEach((e)=>{
								e.url = `/pages/news_details/index?id=${e.article_id}`
								list.push(e)
							});
							this.tableList = list
						}else if(this.currenType == 'product_category' || this.currenType == 'points_cate'){
							data.forEach((e)=>{
								e.id = e.store_category_id
								if(e.hasOwnProperty('children')){
									e.children.forEach((j)=>{
										j.id = j.store_category_id
										j.url = `/pages/columnGoods/goods_list/index?id=${j.store_category_id}&title=${j.cate_name}`
										if(j.hasOwnProperty('children')){
											j.children.forEach((h)=>{
												h.id = h.store_category_id
												h.url = `/pages/columnGoods/goods_list/index?id=${h.store_category_id}&title=${h.cate_name}`
											})
										}
									})
								}
								if(this.currenType == 'product_category'){
									
									e.url = `/pages/columnGoods/goods_list/index?id=${e.store_category_id}&title=默认`
								}else{
									e.url = `/pages/points_mall/integral_goods_list?cate_id=${e.store_category_id}`
								}
								
							});
							this.tableList = data
						}else if(this.currenType == 'active'){
							data.list.forEach(item => {
								item.id = item.group_data_id
								item.url = `/pages/activity/topic_detail/index?id=${item.group_data_id}`
							})
							this.tableList = data.list
							this.total = res.data.count
						}else if(this.currenType == 'merchant'){
							data.list.forEach(item => {
								item.id = item.mer_id
								item.url = `/pages/store/home/index?id=${item.mer_id}`
							})
							this.tableList = data.list
							this.total = res.data.count
						}
						else if(this.currenType == 'micro'){
							data.list.forEach(item => {
								item.id = item.id
								item.url = `/pages/small_page/index?id=${item.id}`
							})
							this.tableList = data.list
							this.total = res.data.count
						}
					}).catch(err=>{
						this.loading = false
						this.$message.error(err.message)
					});
				}
			},
			ok(){
				if(this.currenUrl==""){
					return this.$message.warning("请选择链接");
				}else {
					this.$emit("linkUrl",this.currenUrl);
					this.modals = false
					this.reset();
				}
			},
			cancel(){
				this.modals = false
				this.reset();
			}
    }
  }
</script>

<style scoped lang="scss">
	/deep/.ivu-tree-title-selected, /deep/.ivu-tree-title-selected:hover,/deep/.ivu-tree-title:hover{
		background-color: unset;
		color: #1890FF;
	}
	/deep/.ivu-table-cell-tree{
		border:0;
		font-size: 15px;
		background-color: unset;
	}
	/deep/.ivu-table-cell-tree .ivu-icon-ios-add:before{
		content: "\F11F";
	}
	/deep/.ivu-table-cell-tree .ivu-icon-ios-remove:before{
		content: "\F116";
	}
	.selWidth{ width:200px; }
	.tabBox_img {
		width: 36px;
		height: 36px;
		border-radius: 4px;
		cursor: pointer;
		img {
			width: 100%;
			height: 100%;
		}
	}
	/*定义滑块 内阴影+圆角*/
	::-webkit-scrollbar-thumb{
		-webkit-box-shadow: inset 0 0 6px #ddd;
	}
	::-webkit-scrollbar {
		width: 4px!important; /*对垂直流动条有效*/
	}
.on{
	background-color: #2d8cf0!important;
	color: #fff!important;
}  
.table_box{
	margin-top: 14px;
	display: flex;
	position: relative;
	.left_box{
		width: 171px;
		height: 470px;
		border-right: 1px solid #EEEEEE;
		overflow-x: hidden;
		overflow-y: auto;
		.left_cont{
			margin-bottom: 12px;
			cursor: pointer;
		}
	}
	.right_box{
		margin-left: 23px;
		font-size: 13px;
		font-family: PingFang SC;
		width: 645px;
		height: 470px;
		overflow-x: hidden;
		overflow-y: auto;
		.cont{
			font-weight: 500;
			color: #000000;
			font-weight: bold;
		}
		.Box{
			margin-top: 19px;
			display: flex;
			flex-wrap: wrap;
			.cont_box{
				font-weight: 400;
				color: rgba(0, 0, 0, 0.85);
				background: #FAFAFA;
				border-radius: 3px;
				text-align: center;
				padding: 7px 30px;
				margin-right: 10px;
				margin-bottom: 18px;
				cursor: pointer;
				&:hover{
					background-color: #eee;
					color: #333;
				}
			}
			.item{
				position: relative;
				.iconfont{
					display: none;
				}
				&:hover{
					.iconfont{
						display: block;
					}
				}
			}
			.iconfont{
				position: absolute;
				right: 9px;
				top:-8px;
				font-size: 18px;
				color: #333;
			}
		}
	}	
}
/deep/.el-tree-node__label{
	font-size: 12px;
}
/deep/.el-table--mini .el-table__cell{
	padding: 10px 5px;
}
</style>
