<template>
  <view class="cmi-picker-container">
    <view @click="handleClick">
      <input class="cmi-input" :placeholder="placeholder" :value="selectedItem ? selectedItem[labelKey] : ''" />
      <text class="cmi-picker-arrow iconfont icon-jiantou" />
    </view>

    <c-popup-picker ref="popupPicker" :title="popupTitle" :columns="pickerColumns" :selected-indexes="selectedIndexes" :value-key="valueKey" :label-key="labelKey" v-if="pickerColumns" />
  </view>
</template>

<script>
  export default {
    model: {
      prop: 'value',
      event: 'input'
    },

    props: {
      value: {
        type: [String, Number],
        default: ''
      },
      options: {
        type: Array,
        default: () => []
      },
      labelKey: {
        type: String,
        default: 'label'
      },
      valueKey: {
        type: String,
        default: 'value'
      },
      placeholder: {
        type: String,
        default: '请选择'
      },
      popupTitle: {
        type: String,
        default: '请选择'
      }
    },

    data() {
      return {
        selectedIndexes: [0],
        selectedItem: null
      }
    },

    computed: {
      pickerColumns() {
        return [this.options]
      }
    },

    watch: {
      value: {
        handler() {
          this.selectedIndexes = [this.options.findIndex(item => item[this.valueKey] === this.value)]
        },
        immedated: true
      }
    },

    methods: {
      async handleClick() {
        const res = await this.$refs.popupPicker.pick()
        this.selectedItem = res.selectedItems[0]
        this.$emit('input', this.selectedItem ? this.selectedItem[this.valueKey] : '')
      }
    }
  }
</script>