<template>
  <editor class="rich-editor" :id="id" :read-only="readonly" :disabled="disabled" @ready="onEditorReady" @input="onEditorInput"></editor>
</template>

<script>
  let id = 0
  export default {
    model: {
      event: 'input',
      prop: 'value'
    },

    props: {
      value: {
        type: String,
        default: ''
      },

      disabled: {
        type: Boolean,
        default: false
      }
    },
    
    data() {
      return {
        id: `editor_${id++}`,
        content: '',
        readonly: false
      }
    },

    watch: {
      value(newVal) {
        if (newVal !== this.content) {
          this.$nextTick(() => {
            this.setEditorContent(newVal)
          })
        }
      },
    },

    methods: {
      onEditorReady() {
        if (this.value) {
          this.setEditorContent(this.value)
        }
      },

      onEditorInput(e) {
        this.content = e.detail.html
        this.$emit('input', this.content)
      },

      getEditor() {
        const {
          promise,
          resolve,
        } = this.$util.createDefferred()

        this.createSelectorQuery().select(`#${this.id}`).context((res) => {
          resolve(res.context)
        }).exec()

        return promise
      },

      async setEditorContent(html) {
        const editor = await this.getEditor()

        this.readonly = true
        editor.setContents({
          html
        })
        this.$nextTick(() => {
          this.readonly = false

          // editor会优化输入的html，设置时将html同步回去以保持统一
          editor.getContents({
            success: (res) => {
              this.content = res.html
              this.$emit('input', this.content)
            }
          })
        })
      },
    }
  }
</script>

<style scoped>
  .rich-editor {
    height: auto;
    min-height: 48rpx;
  }
</style>