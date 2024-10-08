<template>
	<view>
		<view class='sign-record'>
		   <view class='list' v-for="(item,index) in growthList" :key="index">
		      <view class='item'>
		         <view class='data'>{{item.month}}</view>
		         <view class='listn'>
		            <view class='itemn acea-row row-between-wrapper' v-for="(itemn,indexn) in item.list" :key="indexn">
		               <view>
		                  <view class='name line1'>{{itemn.title}}</view>
		                  <view>{{itemn.create_time}}</view>
		               </view>
		               <view class='num font-color'>+{{itemn.number}}</view>
		            </view>
		         </view>
		      </view>
		   </view>
		    <view class='loadingicon acea-row row-center-wrapper'>
		      <text class='loading iconfont icon-jiazai' :hidden='loading==false'></text>{{loadtitle}}
		    </view>
		</view>
	</view>
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
	import { growthValueRecord } from '@/api/user.js';
	import { mapGetters } from "vuex";
	import { toLogin } from '@/libs/login.js';
	export default {
		components: {},
		data() {
			return {
				loading:false,
				loadend:false,
				loadtitle:'加载更多',
				page:1,
				limit:8,
				growthList:[],
			};
		},
		computed: mapGetters(['isLogin']),
		onLoad(){
			if(this.isLogin){
				this.getGrowthListList();
			}else{
				toLogin()
			}
		},
		onReachBottom: function () {
			this.getGrowthListList();
		},
		methods: {
		/**
			 * 获取签到记录列表
			*/
			getGrowthListList:function(){
				let that=this;
				if(that.loading) return;
				if(that.loadend) return;
				that.loading = true;
				that.loadtitle = "";
				growthValueRecord({ page: that.page, limit: that.limit }).then(res=>{
					let list = res.data;
					let loadend = list.length < that.limit;
					that.growthList = that.$util.SplitArray(list, that.growthList);
					that.$set(that,'growthList',that.growthList);
					that.loadend = loadend;
					that.loading = false;
					that.loadtitle = loadend ? "哼😕~我也是有底线的~" : "加载更多"
				}).catch(err=>{
					that.loading = false;
					that.loadtitle = '加载更多';
				});
			},
		}
	}
</script>

<style>
</style>
