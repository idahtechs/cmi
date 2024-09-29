<template>
	<view class="main">
		<block v-if="uploading">
			<view class="image-list">
				<view class="input_photo acea-row row-middle">
					<view class="pictrue" v-if="index<=19" v-for="(item, index) in imgList" :key="index">
						<easy-loadimage mode="widthFix" :image-src="item.src"></easy-loadimage>
						<view class="close_btn" @click="DelPic(index)"><text class="iconfont icon-guanbi4"></text></view>
					</view>
				</view>
				<view v-if="!imgList.length" class="acea-row row-center-wrapper row-column add" @click="add">
					<view><text class='iconfont icon-a-guanzhu'></text></view>
					<view class="text">点击选择图片</view>	
				</view>
			</view>
			<view class='footer acea-row row-between-wrapper'>
				<view v-if="imgList.length">
					共{{imgList.length}}/{{ limit }}张，{{ (allSize).toFixed(2) }} M
				</view>
				<view v-else></view>
				<view class="upload-btn acea-row row-between-wrapper">
					<button v-if="imgList.length < limit" class="btn" @click="add"> {{ imgList.length ? '继续选择' : '选择图片' }}</button>
					<button class="btn upload" :class="{ 'no-pic': !imgList.length }" @click="submitUpload">确认上传</button>
				</view>
			</view>
		</block>
		<block v-else>
			<view class="upload-success">
				<view class="success">
					<img class="image" :src="domain+'/static/images/success.jpg'" alt="" />
				</view>
				<view class="text">图片上传成功</view>
				<view class="again" @click="again">继续上传</view>
			</view>
		</block>
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
	import easyLoadimage from '@/components/easy-loadimage/easy-loadimage.vue';
	import {TOKENNAME,HTTP_REQUEST_URL} from '@/config/app.js';
	import {scanUpload} from '@/api/admin.js'
	import store from '@/store';
	export default {
		components: {
			easyLoadimage
		},
		data() {
			return {
				uploading: true,
				domain: HTTP_REQUEST_URL,
				imgList: [],
				fileList: [],
				token: "",
				images: [],
				mer_id: 0,
				allSize: 0,
				limit: 20,
			}
		},
		onLoad(options) {
			this.token = options.token
			this.mer_id = options.mer_id || 0
		},
		onShow() {
			
		},
		methods: {
			add() {
				let that = this
				uni.chooseImage({
					count: 100, //最多可以选择的图片总数  
					sizeType: ['compressed'], // 可以指定是原图还是压缩图，默认二者都有  
					sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有  
					success: (res)=> {
						let imageList = [];
						for (let i = 0; i < res.tempFiles.length; i++) {
							uni.getImageInfo({
								src:res.tempFilePaths[i],  
								success: function(image) {   
									let canvasWidth = image.width //图片原始长宽
									let canvasHeight = image.height;
									let base = canvasWidth/canvasHeight;
									if(canvasWidth>400){
										canvasWidth = 400;
										canvasHeight = Math.floor(canvasWidth/base);
									}	
									let img = document.createElement('img');
									img.src = res.tempFilePaths[i]; // 要压缩的图片  
									let canvas = document.createElement('canvas');
									let ctx = canvas.getContext('2d');
									canvas.width = canvasWidth;
									canvas.height = canvasHeight;
									//  将图片画到canvas上面   使用Canvas压缩 
									ctx.drawImage(img, 0, 0, canvasWidth, canvasHeight);
									canvas.toBlob(function (blob) {
										let file = new File([blob], res.tempFiles[i].name, {type: blob.type})
										let imgSrc = window.URL.createObjectURL(blob);//原生JS生成文件路径
										that.images.push({img:imgSrc,name: res.tempFiles[i].name }) 
										console.log('压缩完图片路径',imgSrc)
										const sizeInBytes = blob.size //获取Blob对象的大小（以字节为单位）
										const sizeInKB = sizeInBytes / 1024 / 1024  //将大小转换为KB
										if(that.imgList.length<20){
											that.imgList.push({src:imgSrc,size:sizeInKB})
											that.allSize += sizeInKB
										}
										
									}, 'image/jpeg', 0.8);		
								},fail(err) {  
									console.log("读取图片失败：", err)  
								}  
							}); 
						} 
					}
				})
			},
			async submitUpload(){
				let that = this;
				for (let i = 0; i < that.images.length; i++) {
					await that.uploadImages(that.images[i])
					if (i == that.imgList.length - 1) {
						that.uploading = false;
						that.loading = false;
					}
				}
			},
			uploadImages(data){
				let that = this
				return new Promise(async (resolve) => {
					//启动上传等待中...
					uni.showLoading({
						title: '上传中...',
					});
					uni.uploadFile({
						url: HTTP_REQUEST_URL + '/api/scan_upload/image/field/' + that.token,
						filePath: data.img,
						name: 'field',
						formData: {name: data.name, mer_id: that.mer_id},
						header: {
							[TOKENNAME]: 'Bearer ' + store.state.app.token
						},
						success: function(res) {
							uni.hideLoading();
							if (res.statusCode == 403) {
								that.$util.Tips({
									title: res.message
								});
							} else {
								let data = res.data ? JSON.parse(res.data) : {};
								if (data.status == 200) {
									resolve(data);
								} else {
									that.$util.Tips({
										title: res.message
									});
								}
							}
						},
						fail: function(res) {
							uni.hideLoading();
							that.$util.Tips({
								title: res
							});
						}
					})
				})	
			},
			/**删除图片*/
			DelPic: function(index) {
				let that = this
				that.imgList.splice(index, 1);
				that.images.splice(index, 1);
				that.getImageSize();
			},
			/*计算总的图片大小*/
			getImageSize(){
				let s = 0;
				if (this.imgList.length) {
					this.imgList.map((e) => {
						s += e.size;
					});
					this.allSize = s;
				} else {
					this.allSize = 0;
				}
			},
			/*继续上传*/
			again(){
				this.uploading = true;
				this.imgList = [];
				this.images = [];
				this.allSize = 0;
			}
		},
		onHide() {
			
		}
	}
</script>

<style lang="scss" scoped>
	page{
		background: #ffffff;
	}
	.main {
		width: 100%;
		.image-list{
			padding: 20rpx;
			overflow: scroll;
			height: calc(100vh - 90rpx);
		}
		.add{
			height: 300rpx;
			background: #f9f9f9;
			border: 1px dashed #ebeef5;
			border-radius: 12rpx;
			color: #bbbbbb;
			.icon-a-guanzhu{
				font-size: 46rpx;
				font-weight: bold;
			}
			.text{
				font-size: 26rpx;
				color: #999;
				margin-top: 32rpx;
			}
		}
		/deep/.input_photo .easy-loadimage,/deep/.input_photo uni-image,/deep/.input_photo image{
			width: 230rpx;
			height: 230rpx;
			border-radius: 12rpx!important;
		}
		.pictrue{
			width: 220rpx;
			height: 220rpx;
			border-radius: 12rpx!important;
			margin: 0 15rpx 10rpx 0;
			position: relative;
			overflow: hidden;
			&:nth-child(3n){
				margin-right: 0;
			}
			.close_btn{
				width: 30rpx;
				height: 30rpx;
				background: rgba(0,0,0,.6);
				border-radius: 0 12rpx 0 12rpx;
				position: absolute;
				top: 0;
				right: 0;
				display: flex;
				align-items: center;
				justify-content: center;
				z-index: 10;
				.iconfont{
					color: #ffffff;
					font-size: 12rpx;
				}
			}
		}
	}
	.footer {
		padding: 0 20rpx 0 30rpx;
		position: fixed;
		bottom: 0;
		width: 100%;
		box-sizing: border-box;
		background-color: rgba(255, 255, 255, 0.85);
		backdrop-filter: blur(20rpx);
		z-index: 277;
		border-top: 2rpx solid #f0f0f0;
		height: 80rpx;
		height: calc(80rpx+ constant(safe-area-inset-bottom)); ///兼容 IOS<11.2/
		height: calc(80rpx + env(safe-area-inset-bottom)); ///兼容 IOS>11.2/
		display: flex;
		align-items: center;
		justify-content: space-between;
		color: #666666;
		font-size: 24rpx;
		.btn {
			border: 2rpx solid #cccccc;
			width: 170rpx;
			height: 60rpx;
			border-radius: 30rpx;
			color: #000;
			font-size: 26rpx;
			font-family: PingFang SC-Regular, PingFang SC;
			font-weight: 400;
			color: #666666;
			display: flex;
			align-items: center;
			justify-content: center;
		}
		.upload {
			background-color: #e93323;
			color: #fff;
			margin-left: 20rpx;
		}
		.upload.no-pic {
			background: #e93323;
			opacity: 0.3;
		}
	}
	.upload-success {
	  display: flex;
	  align-items: center;
	  justify-content: center;
	  flex-direction: column;
	  height: 80vh;
	  .success {
	    width: 100rpx;
	    height: 100rpx;
	    background: #4bbc12;
	    border-radius: 50%;
	    display: flex;
	    align-items: center;
	    justify-content: center;
	    margin-bottom: 40rpx;
	    .image {
	      width: 60%;
	    }
	  }
	  .text {
	    font-size: 32rpx;
	    font-family: PingFang SC-Medium, PingFang SC;
	    font-weight: 500;
	    color: #282828;
	    margin-bottom: 80rpx;
	  }
	  .again {
	    width: 300rpx;
	    height: 86rpx;
	    border-radius: 42rpx;
	    text-align: center;
	    line-height: 86rpx;
	    font-size: 30rpx;
	    font-weight: 400;
	    color: #333333;
	    border: 2rpx solid #cccccc;
	  }
	}
</style>
