<template>
  <c-page flex login-required @ready="onPageReady">
    <template #nav>
      <custom-nav bg-color="transparent" :title="navTitle" />
    </template>

    <view class="p-20 cmi-form">
      <view class="cmi-form-item required">
        <view class="cmi-form-label">模版名字</view>
        <input class="cmi-input" v-model="form.title" maxlength="-1" placeholder="请填写模版名字" />
      </view>
      <view class="cmi-form-item required">
        <view class="cmi-form-label">模版内容</view>
        <textarea class="cmi-input" style="min-height: 560rpx;" v-model="form.prompt" maxlength="-1" placeholder="请填写模版内容" auto-height />
      </view>

      <button class="cmi-btn" type="primary" @click="handleSubmit" :disabled="submitDisabled">{{ isNewRecord ? '创建模版' : '保存模版' }}</button>
      <button class="cmi-btn mt-12" type="warn" plain @click="handleDelete" v-if="!isNewRecord">删除模版</button>
    </view>
  </c-page>
</template>

<script>
  import {
    getUserPromptTemplate,
    createUserPromptTemplate,
    updateUserPromptTemplate,
    deleteUserPromptTemplate
  } from '@/api/user'

  export default {
    data() {
      return {
        id: '',
        form: {
          title: '',
          prompt: ''
        }
      }
    },

    computed: {
      isNewRecord() {
        return !this.id
      },

      submitDisabled() {
        return !this.form.title || !this.form.prompt
      },

      navTitle({ isNewRecord }) {
        return isNewRecord ? '自定义模版' : '编辑模版'

      }
    },

    onLoad({ id }) {
      this.id = id
    },

    methods: {

      async onPageReady() {
        if (!this.isNewRecord) {
          this.loadPromptTemplate()
        }
      },

      async loadPromptTemplate() {
        const [err, res] = await this.$util.ef(getUserPromptTemplate(this.id))

        if (err) {
          return uni.showToast({
            title: err.message,
            icon: 'none'
          })
        }

        this.form.title = res.data.title
        this.form.prompt = res.data.prompt
      },

      async handleSubmit() {
        uni.showLoading()
        const promise = this.isNewRecord ? createUserPromptTemplate(this.form) : updateUserPromptTemplate(this.id, this.form)
        const [err] = await this.$util.ef(promise)
        uni.hideLoading()

        if (err) {
          return uni.showToast({
            title: err.message,
            icon: 'none'
          })
        }

        uni.showToast({
          title: '保存成功',
          icon: 'none',
          success: () => {
            uni.$emit('user_prompt_templates_updated')
            setTimeout(() => {
              uni.navigateBack()
            }, 1000)
          }
        })
      },

      async handleDelete() {
        const [modalErr, modalRes] = await uni.showModal({
          title: '是否确认删除此模版',
          cancelText: '否',
          confirmText: '是'
        })

        if (modalErr || !modalRes.confirm) {
          return
        }

        uni.showLoading()
        const [err] = await this.$util.ef(deleteUserPromptTemplate(this.id))
        uni.hideLoading()

        if (err) {
          console.log(err)
          return uni.showToast({
            title: '删除失败，请重试',
            icon: 'none'
          })
        }

        uni.showToast({
          title: '已成功删除',
          icon: 'none',
          success: () => {
            uni.$emit('user_prompt_templates_updated')
            setTimeout(() => {
              uni.navigateBack()
            }, 1000)
          }
        })
      }
    }
  }
</script>
