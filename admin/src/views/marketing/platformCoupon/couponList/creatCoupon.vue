<template>
  <div class="divBox">
    <el-card class="box-card">
      <form-create
        v-if="FormData"
        ref="fc"
        v-loading="loading"
        :option="option"
        :rule="FormData.rule"
        class="formBox"
        handle-icon="false"
        @submit="onSubmit"
      />
    </el-card>
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
import formCreate from "@form-create/element-ui";
import {
  couponIssuePushApi,
  couponCloneApi,
  platCloneApi,
} from "@/api/marketing";
import request from "@/api/request";
import { roterPre } from "@/settings";

export default {
  name: "CreatCoupon",
  data() {
    return {
      option: {
        form: {
          labelWidth: "150px",
        },
        global: {
          upload: {
            props: {
              onSuccess(rep, file) {
                if (rep.status === 200) {
                  file.url = rep.data.src;
                }
              },
            },
          },
        },
      },
      FormData: null,
      loading: false,
    };
  },
  components: {
    formCreate: formCreate.$form(),
  },
  mounted() {
    this.getFrom();
    sessionStorage.setItem("singleChoice", 0);
    /* eslint-disable */
  },
  methods: {
    getFrom() {
      this.loading = true;
      this.$route.params.id
        ? platCloneApi(this.$route.params.id)
            .then(async (res) => {
              this.FormData = res.data;
              this.loading = false;
            })
            .catch((res) => {
              this.$message.error(res.message);
              this.loading = false;
            })
        : couponIssuePushApi()
            .then(async (res) => {
              this.FormData = res.data;
              this.loading = false;
            })
            .catch((res) => {
              this.$message.error(res.message);
              this.loading = false;
            });
    },
    onSubmit(formData) {
      request[this.FormData.method.toLowerCase()](
        this.FormData.api,
        formData
      )
        .then((res) => {
          this.$message.success(res.message || "提交成功");
          this.$router.push({
            path: `${roterPre}/marketing/platform_coupon/list`,
          });
        })
        .catch((err) => {
          this.$message.error(err.message || "提交失败");
        });
    },
  },
};
</script>

<style scoped>
</style>
