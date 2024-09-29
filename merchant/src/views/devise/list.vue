<template>
  <div class="divBox">
    <el-row class="ivu-mt box-wrapper">
      <el-col class="right-wrapper">
        <el-card v-if="cardShow==0">
          <el-row v-if="cardShow==0">
            <el-col style="width: 310px;height:550px;margin-right: 30px;position: relative" v-if="isDiy">
              <iframe class="iframe-box" :src="imgUrl" frameborder="0" ref="iframe"></iframe>
              <!-- <div class="mask"></div> -->
            </el-col>
            <el-col :class="isDiy?'table':''">
              <div class="acea-row row-between-wrapper">
                <el-row type="flex">
                  <el-col>
                    <div class="acea-row row-between-wrapper">
                      <div class="button acea-row row-middle">
                        <el-button type="primary" size="small" @click="add"><i class="el-icon-plus" style="margin-right: 4px;"/>添加</el-button>
                        <el-button type="success" size="small" @click="getTemplate">使用模板</el-button>
                      </div>
                      <div style="color:rgb(246, 44, 44);font-size: 13px;">&nbsp;&nbsp;注：初次进入该页面，可点击[添加]创建店铺首页模板，也可点击[使用模板]，选择使用平台创建的店铺模板。</div>
                    </div>
                  </el-col>
                </el-row>
              </div>
              <el-table
                class="tables"
                :data="list"
                ref="table"
                highlight-current-row
                size="mini"
                v-loading="loading" 
                border  
              >
                <el-table-column prop="id" label="页面ID" min-width="80" />
                <el-table-column prop="name" label="模板名称" min-width="100" />
                <el-table-column prop="add_time" label="添加时间" min-width="100" />
                <el-table-column prop="update_time" label="更新时间" min-width="100" />
                <el-table-column label="操作" min-width="150">
                  <template slot-scope="scope">
                    <el-button type="text" size="small" v-if="(scope.row.status || scope.row.is_diy) && scope.row.is_default == 0" @click="edit(scope.row)">编辑</el-button>
                    <el-button type="text" size="small" v-if="scope.row.id != 1 && scope.row.is_diy && scope.row.is_default == 0" @click="del(scope.row.id, scope.$index)">删除</el-button>
                    <el-button type="text" size="small" v-if="scope.row.status != 1" @click="setStatus(scope.row, scope.$index)">设为首页</el-button>
                    <el-button type="text" size="small" v-if="scope.row.status || scope.row.is_diy" class="copy-data" @click="preview(scope.row)">预览</el-button>
                    <div style="display: inline-block" v-if="!scope.row.is_diy">
                      <el-button type="text" size="small" @click="recovery(scope.row, scope.$index)">恢复初始设置</el-button>
                      <el-button type="text" size="small" @click="del(scope.row, scope.$index)">删除</el-button>
                    </div>
                    <el-button type="text" size="small" v-if="scope.row.status || scope.row.is_diy" @click="onDiyCopy(scope.row)">复制</el-button>
                  </template>
                </el-table-column>
              </el-table>
               <div class="block">
                <el-pagination
                  :page-sizes="[20, 40, 60, 80]"
                  :page-size="diyFrom.limit"
                  :current-page="diyFrom.page"
                  layout="total, sizes, prev, pager, next, jumper"
                  :total="total"
                  @size-change="handleSizeChange"
                  @current-change="pageChange"
                />
                </div>
            </el-col>
          </el-row>
        </el-card>
      </el-col>
    </el-row>
    <!--平台diy模板-->
    <el-dialog :visible.sync="modal" title="店铺装修模板(平台创建)">
      <div>
        <el-table
          class="tables"
          :data="sysList"
          ref="sysTable"
          highlight-current-row
          size="mini"
          v-loading="sysLoading" 
        >
          <el-table-column prop="id" label="页面ID" min-width="80" />
          <el-table-column prop="name" label="模板名称" min-width="120" />
          <el-table-column prop="add_time" label="添加时间" min-width="120" />
          <el-table-column prop="update_time" label="更新时间" min-width="120" />
          <el-table-column label="操作" min-width="120">
            <template slot-scope="scope">
              <el-button type="text" size="small" @click="onDiyCopy(scope.row, scope.$index)">选择模板</el-button>
              <el-button type="text" size="small" @click="onPreview(scope.row)">预览</el-button>
            </template>
          </el-table-column>
        </el-table>
        <div class="block">
          <el-pagination
            :page-sizes="[20, 40, 60, 80]"
            :page-size="sysFrom.limit"
            :current-page="sysFrom.page"
            layout="total, sizes, prev, pager, next, jumper"
            :total="sysTotal"
            @size-change="sysSizeChange"
            @current-change="sysPageChange"
          />
        </div>
      </div>
    </el-dialog>
    <!--预览平台模板-->
    <el-dialog :visible.sync="previewModal" width="520px" custom-class="customClass" title="预览">
      <div>
        <div v-viewer class="acea-row row-around code">
          <div class="acea-row row-column-around row-between-wrapper">
            <div class="QRpic" ref="qrCodeUrl"></div>
            <span class="mt10">公众号二维码</span>
          </div>
          <div class="acea-row row-column-around row-between-wrapper">
            <div class="QRpic">
              <img v-lazy="qrcodeImg" />
            </div>
            <span class="mt10">小程序二维码</span>
          </div>
        </div>
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
import SettingMer from '@/libs/settingMer'
import { roterPre } from '@/settings'
import { diyList, sysDiyList, diyDel, setStatus, recovery, diyCopy, getRoutineCode } from "@/api/diy";
import QRCode from 'qrcodejs2'
import { getConfigApi } from '@/api/systemForm'
import { mapState } from "vuex";

export default {
  name: "devise_list",
  computed: {
    ...mapState('layout', [
      'menuCollapse'
    ])
  },
  components: {},
  data() {
    return {
      grid: {
        sm: 10,
        md: 12,
        lg: 19,
      },
      loading: false,
      sysLoading: false,
      theme3: "light",
      roterPre: roterPre,
      list: [],
      sysList: [],
      imgUrl:'',
      modal: false,
      previewModal: false,
      BaseURL: SettingMer.httpUrl || 'http://localhost:8080',
      cardShow: 0,
      loadingExist: false,
      isDiy: 1,
      qrcodeImg: '',
      diyFrom: {
        page: 1,
        limit: 20
      },
      sysFrom: {
        page: 1,
        limit: 20
      },
      total: 0,
      sysTotal: 0,
      mer_id: '',
      isPreview: false,
    };
  },
  created() {
    this.getMerId();
    this.getList();
    
  },
  mounted: function() {
  },
  methods: {
    getChildData(e){
      this.loadingExist = e
    },
    // 获取商户ID
    getMerId() {
      let storage = window.localStorage;
      this.imgUrl = storage.getItem('imgUrl');
      getConfigApi().then(res => {
        this.mer_id = res.data.mer_id
        let time = new Date().getTime() * 1000
        let imgUrl = `${this.BaseURL}/pages/store/home/index?id=${this.mer_id}&inner_frame=1&time=${time}`;
        storage.setItem('imgUrl',imgUrl)
        this.imgUrl = imgUrl;
      })
      .catch(res => {
        this.$message.error(res.message)
      })
    },
    onCopy() {
      this.$message.success("复制预览链接成功");
    },
    onError() {
      this.$mssage.error("复制预览链接失败");
    },
    //预览平台模板
    onPreview(row){
      this.previewModal = true;
      this.$nextTick(()=>{
        this.creatQrCode(row.id,row.status);
        this.routineCode(row.id);
      })
     
    },
     //生成二维码
    creatQrCode(id,status) {
      this.$refs.qrCodeUrl.innerHTML = ''
			let url = '';
			if(status){
				url = `${this.BaseURL}/pages/admin/storeDiy/index?inner_frame=1&time=${time}&id=${this.mer_id}&diy_id=${id}`;
			}else{
        let time = new Date().getTime() * 1000
				url= `${this.BaseURL}/pages/admin/storeDiy/index?inner_frame=1&time=${time}&id=${this.mer_id}&diy_id=${id}`;
			}
      var qrcode = new QRCode(this.$refs.qrCodeUrl, {
        text: url, // 需要转换为二维码的内容
        width: 160,
        height: 160,
        colorDark: '#000000',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.H
      })
    },
    //小程序二维码
    routineCode(id){
      getRoutineCode(id).then(res=>{
        this.qrcodeImg = res.data.image;
      }).catch(err=>{
        this.$message.error(err);
      })
    },
    preview(row){
      this.isPreview = true
      let time = new Date().getTime() * 1000
      let imgUrl = `${this.BaseURL}/pages/admin/storeDiy/index?id=${this.mer_id}&diy_id=${row.id}&inner_frame=1&time=${time}`;
      // let imgUrl = `http://192.168.31.69:8080/pages/admin/storeDiy/index?id=${this.mer_id}&diy_id=${row.id}&inner_frame=1&time=${time}`;
      this.imgUrl = imgUrl;
    },
    // 获取列表
    getList() {
      this.loading = true;
      diyList(this.diyFrom).then((res) => {
        this.loading = false;
        let data = res.data;
        this.list = data.list;
        this.total = data.count;
        
      });
    },
    getTemplate(){
      this.modal = true;
      this.diyFrom.page = 1;
      this.getSysList();
    },
    getSysList() {
      this.sysLoading = true;
      sysDiyList(this.sysFrom).then((res) => {
        this.sysLoading = false;
        let data = res.data;
        this.sysList = data.list;
        this.sysTotal = data.count;
      }).catch((err) => {
        this.$message.error(err)
        this.sysLoading = false;
      });
    },
    pageChange(status) {
      this.diyFrom.page = status;
      this.getList();
    },
    handleSizeChange(val) {
      this.diyFrom.limit = val
      this.getList()
    }, 
    sysSizeChange(val) {
      this.sysFrom.limit = val
      this.getSysList()
    },
    sysPageChange(status) {
      this.sysFrom.page = status;
      this.getSysList();
    },  
    // 编辑
    edit(row) {
      this.$router.push({
        path: `${roterPre}/devise/diy/index`,
        query: { id: row.id, name: row.template_name || "moren" },
      });
    },
    // 添加
    add() {
      this.$router.push({
        path: `${roterPre}/devise/diy/index`,
        query: { id: 0, name: "首页", types: 1 },
      });
    },
    // 删除
    del(id,idx) {
      this.$modalSure('删除模板吗').then(() => {
        diyDel(id).then(({ message }) => {
          this.$message.success(message)
          this.getList()
        }).catch(({ message }) => {
          this.$message.error(message)
        })
      })
    },
    // 使用模板
    async setStatus(row) {
      let that = this
      that.$modalSure("把该模板设为首页").then(() => {
        setStatus(row.id).then((res) => {
          that.$message.success(res.message);
          let time = new Date().getTime() * 1000
          let imgUrl = `${this.BaseURL}/pages/admin/storeDiy/index?id=${this.mer_id}&diy_id=${row.id}&inner_frame=1&time=${time}`;
          that.imgUrl = imgUrl;
          that.getList();
        }).catch((res) => {
          that.$message.error(res.message);
        });
      })
    },
    recovery(row) {
      recovery(row.id).then((res) => {
        this.$message.success(res.message);
        this.getList();
      });
    },
    onDiyCopy(row) {
      diyCopy(row.id).then((res) => {
        this.modal = false
        this.$message.success(res.message);
        this.getList()
      }).catch(res => {
        this.$message.error(res.message);
      })
    }
  },
};
</script>

<style scoped lang="scss">
  /* 用来设置当前页面element全局table 选中某行时的背景色*/
  .el-table__body tr.current-row>td{
    background-color: #69A8EA !important;
  }
  .product_tabs{
    padding: 16px 32px;
    background: #fff;
    border-bottom: 1px solid #e8eaec;
    text-align: right;
  }
  .el-menu-item{
    height: 47px;
  }
  .el-menu-item.is-active::after{
    content: "";
    display: block;
    width: 2px;
    position: absolute;
    top: 0;
    bottom: 0;
    right: 0;
    background: #2d8cf0;
  }
  .tables{
    margin-top: 20px;
  }
  .ivu-mt{
    background-color: #fff;
    padding-bottom: 50px;
  }
  .bnt{
    width: 80px!important;
  }
  .iframe-box{
    width: 100%;
    height: 100%;
    border-radius: 10px;
    border: 1px solid #eee;
  }
  .mask{
    position: absolute;
    left:0;
    width: 100%;
    top:0;
    height: 100%;
    background-color: rgba(0,0,0,0);
  }
  @media (max-width: 2600px){
    .table {
      display: block;
      flex: 0 0 70%;
      max-width: 70%;
    }
  }
  @media (max-width: 2175px){
    .table {
      display: block;
      flex: 0 0 75%;
      max-width: 75%;
    }
  }
  @media (max-width: 2010px){
    .table {
      display: block;
      flex: 0 0 75%;
      max-width: 73%;
    }
  }
  @media (max-width: 1860px){
    .table {
      display: block;
      flex: 0 0 70%;
      max-width: 70%;
    }
  }
  @media (max-width: 1597px){
    .table {
      display: block;
      flex: 0 0 65%;
      max-width: 65%;
    }
  }
  @media (max-width: 1413px){
    .table {
      display: block;
      flex: 0 0 60%;
      max-width: 60%;
    }
  }
  @media (max-width: 1275px){
    .table {
      display: block;
      flex: 0 0 55%;
      max-width: 55%;
    }
  }
  @media (max-width: 1168px){
    .table {
      display: block;
      flex: 0 0 48%;
      max-width: 48%;
    }
  }
  /deep/.customClass{
    border-radius: 6px;
  }
  .code{
    position: relative;
    justify-content: space-around;
  }
  .row-column-around{
    flex-direction: column;
  }
  .mt10{
    margin-top: 10px;
  }
  .QRpic {
    width: 160px;
    height: 160px;

    img {
      width: 100%;
      height: 100%;
    }
  }
  .left-wrapper {
    background: #fff;
    border-right: 1px solid #dcdee2;
  }
  .picCon{
    width: 280px;
    height: 510px;
    background: #FFFFFF;
    border: 1px solid #EEEEEE;
    border-radius: 25px;
    .pictrue{
      width: 250px;
      height: 417px;
      border: 1px solid #EEEEEE;
      opacity: 1;
      border-radius: 10px;
      margin: 30px auto 0 auto;
      img{
        width: 100%;
        height: 100%;
        border-radius: 10px;
      }
    }
    .circle{
      width: 36px;
      height: 36px;
      background: #FFFFFF;
      border: 1px solid #EEEEEE;
      border-radius: 50%;
      margin: 13px auto 0 auto;
    }
  }
</style>
