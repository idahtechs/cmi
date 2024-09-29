<template>
  <div>
    <div class="picture-count">
      <div class="Nav">
        <!-- <div class="input">
          <el-input v-model="filterText" placeholder="选择分类" prefix-icon="el-icon-search" style="width: 100%;" clearable />
        </div> -->
        <div class="trees-coadd">
          <div class="scollhide">
            <div class="trees">
              <el-tree ref="tree" 
                :data="treeData2" 
                :props="defaultProps"
                node-key="attachment_category_id"
                :highlight-current="true"
                :expand-on-click-node="false"
                @node-click="appendBtn"
                :current-node-key="treeId">
                <div slot-scope="{ node, data}" class="custom-tree-node">
                  <div>
                    <span>{{ node.label }}</span>
                  </div>
                  <span>
                    <el-dropdown @command="(command) => clickMenu(data, command)">
                      <span class="add el-icon-more" />
                      <template slot="dropdown">
                        <el-dropdown-menu>
                          <el-dropdown-item command="1">新增分类</el-dropdown-item>
                          <el-dropdown-item v-if="data.attachment_category_id" command="2">编辑分类</el-dropdown-item>
                          <el-dropdown-item v-if="data.attachment_category_id" command="3">删除</el-dropdown-item>
                        </el-dropdown-menu>
                      </template>
                    </el-dropdown>
                  </span>
                </div>
              </el-tree>
            </div>
          </div>
        </div>
      </div>
      <div class="colLeft">
        <div v-loading="loading" class="conter">
          <div class="bnt acea-row row-middle">
            <div>
              <el-button v-if="params !== '/merchant/config/picture'" size="mini" type="primary" class="mb10" @click="checkPics(true)">使用选中图片</el-button>
              <el-button size="mini" class="upload-demo" type="primary" @click="uploadModal">上传图片</el-button>
              <el-button type="success" size="mini" @click.stop="onAdd(0)">添加分类</el-button>
              <el-button type="error" size="mini" :disabled="ids.length===0" @click.stop="deletePicList('图片')">删除图片</el-button>
            
              <el-cascader
                v-model="pids"
                placeholder="图片移动至"
                style="width: 150px"
                class="treeSel"
                :options="treeData2"
                :props="{ checkStrictly: true, emitPath: false, label: 'attachment_category_name', value: 'attachment_category_id' }"
                clearable
                size="small"
                @visible-change="handleSelClick"
            ></el-cascader>
            </div>
            <div v-if="isPage">
              <el-input
                v-model="tableData.attachment_name"
                @keyup.enter.native="getFileList(1)"
                placeholder="请输入图片名称搜索"
                style="width: 230px;"
                size="small"
              >
                <el-button slot="append" icon="el-icon-search" size="small" class="el-button-solt" @click="getFileList(1)" />
              </el-input>
              <el-radio-group v-model="lietStyle" size="mini" @change="radioChange">
                <el-radio-button label="list">
                  <i class="iconfont icongongge"></i>
                </el-radio-button>
                <el-radio-button label="table">
                  <i class="iconfont iconliebiao"></i>
                </el-radio-button>
              </el-radio-group>
            </div>
          </div>
          
          <div class="pictrueList acea-row" :class="{ 'is-modal': !isPage }">
            <div v-if="lietStyle == 'list'" style="width: 100%">
              <div v-show="isShowPic" class="imagesNo">
                <i class="el-icon-picture" style="font-size: 60px;color: rgb(219, 219, 219);" />
                <span class="imagesNo_sp">图片库为空</span>
              </div>
              <div class="conters">
                <div v-for="(item, index) in pictrueList.list" :key="item.attachment_id" class="gridPic" :class="{gridSmall : isDialog}">
                  <p class="number" v-if="item.num>0">
                    <el-badge class="item" :value="item.num">
                      <a href="#" class="demo-badge"></a>
                    </el-badge>
                  </p>
                  <img v-lazy="item.attachment_src" :class="item.isSelect ? 'on': '' " @click="changImage(item, index, pictrueList.list)" />
                  <!-- <div style="display: flex;align-items: center;justify-content: space-between">
                    <el-input v-model="item.attachment_name" v-if="editId === item.attachment_id"></el-input>
                    <p class="name" style="width: 80%" v-else>{{item.attachment_name}}</p>
                    <i class="el-icon-edit" @click="handleEdit(item.attachment_id,item.attachment_name)" />
                  </div> -->
                   <div style="operate-item " @mouseenter="enterLeave(item)" @mouseleave="enterLeave(item)">
                    <p v-if="!item.isEdit">
                      {{ item.attachment_name }}
                    </p>
                    <el-input size="small" type="text" v-model="item.attachment_name" v-else @blur="handleEdit(item)" />
                    <div class="operate-item operate-height">
                      <span class="operate mr10" @click="deletePic(item)" v-if="item.isShowEdit">删除</span>
                      <span class="operate mr10" @click="item.isEdit = !item.isEdit" v-if="item.isShowEdit">重命名</span>
                      <span class="operate" @click="lookImg(item)" v-if="item.isShowEdit">查看</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <el-table
              v-if="lietStyle == 'table'"
              ref="table"
              size="small"
              :data="pictrueList.list"
              v-loading="loading"
              highlight-row
              :row-key="getRowKey"
              @selection-change="handleSelectRow"
              class="ones"
              no-data-text="暂无数据"
              no-filtered-data-text="暂无筛选结果"
            >
              <el-table-column type="selection" width="60" :reserve-selection="true"> </el-table-column>
              <el-table-column label="图片名称" min-width="190">
                <template slot-scope="scope">
                  <div class="df-aic">
                    <div class="tabBox_img mr10" v-viewer>
                      <img v-lazy="scope.row.attachment_src" />
                    </div>
                    <span v-if="!scope.row.isEdit" class="line2 real-name">{{ scope.row.attachment_name }}</span>
                    <el-input
                      size="small"
                      type="text"
                      style="width: 90%"
                      v-model="scope.row.attachment_name"
                      v-else
                      @blur="handleEdit(scope.row)"
                    />
                   
                  </div>
                </template>
              </el-table-column>
              <el-table-column label="上传时间" min-width="100">
                <template slot-scope="scope">
                  <span>{{ scope.row.create_time }}</span>
                </template>
              </el-table-column>
              <el-table-column label="操作" fixed="right" width="170">
                <template slot-scope="scope">
                  <el-button type="text" size="small" @click="deletePic(scope.row)">删除</el-button>
                  <el-button type="text" size="small"  @click="scope.row.isEdit = !scope.row.isEdit">{{ scope.row.isEdit ? '确定' : '重名命' }}</el-button>
                  <el-button type="text" size="small" @click="lookImg(scope.row)">查看</el-button>
                </template>
              </el-table-column>
            </el-table>
          </div>
          <div class="block" style="text-align: right;">
            <el-pagination :page-sizes="[30, 60, 90]" :page-size="tableData.limit" :current-page="tableData.page" layout="total, sizes, prev, pager, next" :total="pictrueList.total" @size-change="handleSizeChange" @current-change="pageChange" />
          </div>
        </div>
      </div>
    </div>
     <uploadImg
      ref="upload"
      :isPage="isPage"
      :isIframe="isIframe"
      :categoryId="treeId"
      :categoryList="treeData"
      @uploadSuccess="uploadSuccess"
    ></uploadImg>
    <div class="images" v-show="false" v-viewer="{ movable: false }">
      <img v-for="src in pictrueList.list" :src="src.attachment_src" :key="src.attachment_id" />
    </div>
  </div>
</template>

<script>
// +----------------------------------------------------------------------
// | CRMEB [ CRMEB赋能开发者，助力企业发展 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2021 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEB并不是自由软件，未经许可不能去掉CRMEB相关版权
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------
import {
  formatLstApi,
  attachmentCreateApi,
  attachmentUpdateApi,
  picNameEditApi,
  attachmentDeleteApi,
  attachmentListApi,
  picDeleteApi,
  categoryApi
} from "@/api/system";
import { getToken } from "@/utils/auth";
import SettingMer from "@/libs/settingMer";
import uploadImg from '@/components/uploadImg';
export default {
  name: "Upload",
  components: { uploadImg },
  props: {
    isMore: {
      type: String,
      default: "1"
    },
    setModel: {
      type: String
    },
     isIframe: {
      type: Boolean,
      default: false,
    },
    isPage: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      loading: false,
      params: "",
      pids: "",
      treeId: 0,
      sleOptions: {
        attachment_category_name: "",
        attachment_category_id: ""
      },
      list: [],
      filterText: "",
      treeData: [],
      treeData2: [],
      defaultProps: {
        children: "children",
        label: "attachment_category_name"
      },
      classifyId: 0,
      myHeaders: { "X-Token": getToken() },
      tableData: {
        page: 1,
        limit: 30,
        attachment_category_id: 0,
        order: "",
        attachment_name: ""
      },
      pictrueList: {
        list: [],
        total: 0
      },
      isShowPic: false,
      checkPicList: [],
      ids: [],
      checkedMore: [],
      checkedAll: [],
      multipleSelection: [],
      selectItem: [],
      editId: "", // 记录id
      editName: "",// 记录保存前名字
      lietStyle: 'list',
      isDialog: false
    };
  },
  computed: {
    fileUrl() {
      return (
        SettingMer.https +
        `/upload/image/${this.tableData.attachment_category_id}/file`
      );
    }
  },
  watch: {
    filterText(val) {
      this.$refs.tree.filter(val);
    }
  },
  mounted() {
    let that = this;
    this.params = this.$route && this.$route.path ? this.$route.path : "";
    if(this.$route && this.$route.query.field === "dialog")
    import("../../../public/UEditor/dialogs/internal");
    this.getList();
    this.getFileList("");
    if ((that.$route && that.$route.query.field) || !that.$route)that.isDialog = true
    if (that.$route && that.$route.query.field === "dialog") {
      that.isDialog = true
      form_create_helper.onOk(function(){
        that.checkPics()
      })
     }
  },
  methods: {
    radioChange() {
      this.initData();
    },
    initData() {
      // this.pids = 0;
      this.checkPicList = [];
      this.selectItem = [];
      this.ids = [];
      this.multipleSelection = [];
      if(this.lietStyle == 'table')this.$refs.table.clearSelection(); 
      this.pictrueList.list.map((el, i) => {
        el.isSelect = false
        el.num = 0;  
      });
    },
    getRowKey(row) {
      return row.attachment_id;
    },
     //  选中某一行
    handleSelectRow(selection) {
      if(selection.length){
        let arr = this.unique(selection);
        console.log(selection, arr);
        const uniqueArr = [];
        for (let i = 0; i < arr.length; i++) {
          const item = arr[i];
          if(!this.ids.includes(item.attachment_id))this.ids.push(item.attachment_id)
        }
        this.multipleSelection = uniqueArr;
      }else{
        this.ids = []
        this.multipleSelection = []
      }
    },
    //对象数组去重；
    unique(arr) {
      let result = arr.reduce((acc, curr) => {
        const x = acc.find((item) => item.attachment_id === curr.attachment_id);
        if (!x) {
          return acc.concat([curr]);
        } else {
          return acc;
        }
      }, []);
      return result;
    },
    lookImg(item) {
      this.imageUrl = item.attachment_src;
      const viewer = this.$el.querySelector('.images').$viewer;
      viewer.show();
      this.$nextTick(() => {
        let i = this.pictrueList.list.findIndex((e) => e.attachment_src === item.attachment_src);
        viewer.update().view(i);
      });
    },
    // 搜索分类
    filterNode(value, data) {
      if(!value) return true;
      return data.attachment_category_name.indexOf(value) !== -1;
    },
    // 所有分类
    getList() {
      const data = {
        attachment_category_name: "全部图片",
        attachment_category_id: 0
      };
      formatLstApi()
        .then(res => {
          this.treeData = res.data;
          this.treeData.unshift(data);
          this.treeData2 = [...this.treeData];
        })
        .catch(res => {
          this.$message.error(res.message);
        });
    },
    // 编辑图片名称
    handleEdit(item) {
      if (!item.attachment_name.trim()) {
        this.$message.warning("请先输入图片名称");
        return;
      }
      picNameEditApi(item.attachment_id, {
        attachment_name: item.attachment_name
      }).then((res) =>{
        item.isEdit = false;
         this.$message.success(res.message);
      }).catch((error) => {
        this.$message.error(error.message);
      });
      // if(id === this.editId) {
      //   if(this.editName !== name) {
      //     if(!name.trim()) {
      //       this.$message.warning("请先输入图片名称");
      //       return;
      //     }
      //     picNameEditApi(id, {
      //       attachment_name: name
      //     }).then(() => this.getFileList(""));
      //     this.editId = "";
      //   } else {
      //     this.editId = "";
      //     this.editName = "";
      //   }
      // } else {
      //   this.editId = id;
      //   this.editName = name;
      // }
    },
    // 添加分类
    onAdd(id) {
      const config = {};
      if(Number(id) > 0) config.formData = { pid: id };
      this.$modalForm(attachmentCreateApi(), config).then(({ message }) => {
        // this.$message.success(message)
        this.getList();
      });
    },
    // 编辑
    onEdit(id) {
      this.$modalForm(attachmentUpdateApi(id)).then(() => this.getList());
    },
    // 删除
    handleDelete(id) {
      this.$modalSure().then(() => {
        attachmentDeleteApi(id)
          .then(({ message }) => {
            this.$message.success(message);
            this.getList();
          })
          .catch(({ message }) => {
            this.$message.error(message);
          });
      });
    },
    // 点击树
    appendBtn(data) {
      this.treeId = data.attachment_category_id;
      this.tableData.attachment_category_id = data.attachment_category_id
      this.selectItem = [];
      this.checkPicList = [];
      this.tableData.page = 1;
      this.getFileList();
    },
    handleNodeClick(data) {
      this.treeId = tableData.attachment_category_id
      this.tableData.attachment_category_id = data.attachment_category_id;
      this.selectItem = [];
      this.checkPicList = [];
      this.getFileList("");
    },
    // 点击上传
    uploadModal() {
      this.$refs.upload.uploadModal = true;
    },
    uploadSuccess() {
      this.tableData.page = 1;
      this.getFileList("");
    },
    enterMouse(item) {
      item.realName = !item.realName;
    },
    enterLeave(item) {
      item.isShowEdit = !item.isShowEdit;
    },
    // 点击菜单
    clickMenu(data, name) {
      if (name == 1) {
        this.onAdd(data.attachment_category_id);
      } else if (name == 2) {
        this.onEdit(data.attachment_category_id);
      } else if (name == 3) {
        this.handleDelete(data.attachment_category_id);
      }
    },
    // 上传成功
    handleSuccess(response) {
      if(response.status === 200) {
        this.$message.success("上传成功");
        this.getFileList("");
      } else {
        this.$message.error(response.message);
      }
    },
    // 文件列表
    getFileList(num) {
      this.loading = true;
      this.tableData.page = num ? num : this.tableData.page;
      attachmentListApi(this.tableData)
        .then(async res => {
          res.data.list.forEach((el) => {
            el.isSelect = false;
            el.isEdit = false;
            el.isShowEdit = false;
            el.realName = false;
            el.num = 0;
          });
          this.pictrueList.list = res.data.list;
          console.log(this.pictrueList.list);
          if(this.pictrueList.list.length) {
            this.isShowPic = false;
          } else {
            this.isShowPic = true;
          }
          if(
            this.$route &&
            this.$route.query.field &&
            this.$route.query.field !== "dialog"
          )
            this.checkedMore =
              window.form_create_helper.get(this.$route.query.field) || [];
          this.pictrueList.total = res.data.count;
          this.loading = false;
        })
        .catch(res => {
          this.$message.error(res.message);
          this.loading = false;
        });
    },
    pageChange(page) {
      this.tableData.page = page;
      this.selectItem = [];
      this.checkPicList = [];
      this.getFileList("");
    },
    handleSizeChange(val) {
      this.tableData.limit = val;
      this.getFileList("");
    },
    // 选中图片
    changImage(item, index, row) {
      if (!item.isSelect) {
        item.isSelect = true;
        this.selectItem.push(item);
        this.checkPicList.push(item.attachment_src);
        this.ids.push(item.attachment_id);
      } else {
        item.isSelect = false;
        var index = this.ids.indexOf(item.attachment_id);
        if (index > -1) this.ids.splice(index, 1);
        this.selectItem.forEach((o, i) => {
          if (o.attachment_id == item.attachment_id) {
            this.selectItem.splice(i, 1);
          }
        });
        this.checkPicList.map((el, index) => {
          if (el == item.attachment_src) {
            this.checkPicList.splice(index, 1);
          }
        });
      }
      // if (
      //   (this.$route &&
      //     this.$route.fullPath &&
      //     this.$route.fullPath !== "/admin/config/picture") ||
      //   !this.$route
      // ) {
      this.pictrueList.list.map((el, i) => {
        if (el.isSelect) {
          this.selectItem.filter((el2, j) => {
            if (el.attachment_id == el2.attachment_id) {
              el.num = j + 1;
            }
          });
        } else {
          el.num = 0;
        }
      });
      // }
    },
    // 点击使用选中图片
    checkPics(isButton) {
      if(this.checkPicList.length) {
        if(this.$route) {
          if(this.$route.query.type === "1") {
            if(this.checkPicList.length > 1)
              return this.$message.warning("最多只能选一张图片");
            /* eslint-disable */
            form_create_helper.set(
              this.$route.query.field,
              this.checkPicList[0]
            );
            if(isButton)form_create_helper.close(this.$route.query.field);
          }
          if(this.$route.query.type === "2") {
            this.checkedAll = [...this.checkedMore, ...this.checkPicList];
            form_create_helper.set(
              this.$route.query.field,
              Array.from(new Set(this.checkedAll))
            );
            form_create_helper.close(this.$route.query.field);
          }
          if(this.$route.query.field === "dialog") {
            let str = "";
            for(let i = 0; i < this.checkPicList.length; i++) {
              str += '<img src="' + this.checkPicList[i] + '">';
            }
            /* eslint-disable */
            nowEditor.editor.execCommand("insertHtml", str);
            nowEditor.dialog.close(true);
          }
        } else {
          console.log(this.isMore, this.checkPicList.length);
          if(this.isMore === "1" && this.checkPicList.length > 1) {
            return this.$message.warning("最多只能选一张图片");
          }
          console.log(this.checkPicList);
          this.$emit("getImage", this.checkPicList);
        }
      } else {
        this.$message.warning("请先选择图片");
      }
    },
    // 单个删除
    deletePic(item){
      this.ids = [item.attachment_id]
      this.deletePicList()
    },
    // 删除图片
    deletePicList(tit) {
      const ids = {
        ids: this.ids
      };
      this.$confirm('确定删除图片?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        picDeleteApi(ids)
          .then(({ message }) => {
            this.$message.success(message);
            this.getFileList("");
            this.spliceDeleteList();
            this.initData();
          })
          .catch(({ message }) => {
            this.$message.error(message);
          });
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消'
        }); 
        this.initData();         
      });
    },
     // 去除选中数组中删除掉的元素
    spliceDeleteList(){
      this.selectItem.map((el, i) => {
        this.ids.filter((el2, j) => {
          if (el.attachment_id == el2) {
           this.selectItem.splice(i, 1);
          }
        });
      });
    },
    // 移动分类点击
    handleSelClick(status) {
      if (!status) {
        this.getMove();
      } else {
        if (!this.ids.toString()) {
          this.$message.warning('请先选择图片');
          return;
        }    
      }
    },
    getMove() {
      let data = {
        pid: this.pids,
        images: this.ids.toString(),
      };
      if (!data.images) return;
      if (this.pids === "") {
        this.$message.warning('请选择分类');
        return;
      }
        categoryApi(this.ids, this.pids)
          .then(async res => {
            this.$message.success(res.message);
            this.pids = "";
            this.initData();
            this.getFileList("");
          })
          .catch(res => {
            this.pids = "";
            this.initData();
            this.$message.error(res.message);
          });
      },
    }
};
</script>

<style scoped lang="scss">
.Nav{
  max-width: 220px;
  min-width: 220px;
  border-right: 1px solid #eee;
}
.picture-count{
  height: 100%;
  display: flex;
  flex-wrap: nowrap;
}
.colLeft{
  margin-left: 20px;
  width: 100%;
}
/deep/.ones th{
  background: #F0F5FF;
}
.selectTreeClass {
  background: #d5e8fc;
}
.treeBox {
  width: 100%;
  height: 100%;
}
.upload-demo {
  display: inline-block !important;
}
.is-modal .pictrueList_pic {
  width: 100px;
  margin-right: 10px !important;
  img {
    width: 100%;
    height: 100px;
  }
}
.tabBox_img {
  display: flex;
  align-items: center;
  width: 36px;
  height: 36px;
  border-radius: 4px;
  cursor: pointer;
  img{
    width: 100%;
  }
}
.real-name {
  flex: 1;
}
.df-aic {
  display: flex;
  align-items: center;
  
}
.tree_w {
  padding: 20px 30px;
}
.custom-tree-node {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-size: 13px;
  padding-right: 17px;
  height: 36px;
  color: rgba(0,0,0,.6);
  .add{
    font-size: 10px;
    color: rgba(0,0,0,.6);
  }
}
.is-current .custom-tree-node,.is-current .el-icon-more {
  color: #437FFD;
}
/deep/.el-tree-node{
  position: relative;
}
/deep/.is-current::after{
  content: "";
  display: block;
  width: 2px;
  height: 40px;
  background: #437FFD;
  position: absolute;
  right: 0;
  top: 0;
}
/deep/.el-tree--highlight-current .el-tree-node.is-current>.el-tree-node__content{
  background: rgba(67,127,253,.04);
}
.el-ic {
  display: none;
  i,
  span {
    font-size: 18px;
    font-weight: 600;
  }
  .svg-icon {
    color: #437FFD;
  }
}
.el-tree-node__expand-icon {
  color: #437FFD;
}
/deep/.el-tree-node__content{
  height: 40px;
}
.el-tree-node__content:hover .el-ic {
  color: #428bca !important;
  display: inline-block;
}
.el-dialog__body {
  .upload-container .image-preview .image-preview-wrapper img {
    height: 100px;
  }
  .el-dialog .el-collapse-item__wrap {
    padding-top: 0px;
  }
  .spatial_img {
    .el-collapse-item__wrap {
      margin-bottom: 0;
      padding-top: 0px;
    }
  }
  .upload-container .image-preview .image-preview-wrapper {
    width: 120px;
  }
  .upload-container .image-preview .image-preview-action {
    line-height: 100px;
    height: 100px;
  }
}
.trees-coadd {
  width: 100%;
  border-radius: 4px;
  overflow: hidden;
  position: relative;
  .scollhide {
    overflow-x: hidden;
    overflow-y: scroll;
    padding: 10px 0 10px 0;
    box-sizing: border-box;
    .trees {
      width: 100%;
      max-height: 400px;
    }
  }
  .scollhide::-webkit-scrollbar {
    display: none;
  }
}
.conters {
  display: flex;
  flex-wrap: wrap;
}
.gridPic {
  margin-right: 17px;
  margin-bottom: 10px;
  width: 146px;
  cursor: pointer;
  position: relative;
  p{
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    height: 20px;
    font-size: 12px;
    color: #515a6d;
    text-align: center;
    margin: 10px 0 0;
  }
  img {
    width: 146px;
    height: 146px;
    object-fit: contain;
  }
  &.gridSmall{
    width: 100px;
    img {
      width: 100px;
      height: 100px;
      object-fit: contain;
    }
  }
  .name {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    color: #515a6e;
    font-size: 12px;
  }
  .number {
    position: absolute;
    top: 0;
    right: 0;
    margin: 0;
  }
  .demo-badge {
    width: 42px;
    height: 42px;
    background: transparent;
    border-radius: 6px;
    display: inline-block;
  }
  /deep/ .el-badge__content {
    position: absolute;
    transform: translateX(50%);
    top: 0;
    right: 10px;
    height: 20px;
    border-radius: 10px;
    min-width: 20px;
  }
}
.conter {
  height: 100%;
  .bnt {
    width: 100%;
    padding: 0 13px 20px 15px;
    box-sizing: border-box;
    justify-content: space-between;
  }
  .pictrueList {
    padding-left: 15px;
    width: 100%;
    el-image {
      width: 100%;
      border: 2px solid #fff;
    }
    .on {
      border: 2px solid #5fb878;
    }
  }
  .el-image {
    width: 110px;
    height: 110px;
    cursor: pointer;
  }
  .imagesNo {
    display: flex;
    justify-content: center;
    flex-direction: column;
    align-items: center;
    margin: 65px auto;
    .imagesNo_sp {
      font-size: 13px;
      color: #dbdbdb;
      line-height: 3;
    }
  }
}
.operate-item {
  display: flex;
  align-items: center;
  justify-content: center;
}
.operate-height {
  height: 16px;
}
.operate {
  color: #437FFD;
  font-size: 12px;
  white-space: nowrap;
}
</style>
