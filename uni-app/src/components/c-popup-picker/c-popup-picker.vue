<template>
  <uni-popup ref="popup" type="bottom">
    <view class="c-popup-picker">
      <view class="c-popup-picker-title">{{ title }}</view>
      <picker-view class="c-popup-picker-view" :indicator-style="indicatorStyle" :value="value" immediate-change @change="handleChange">
        <picker-view-column v-for="(col, index) in columns" :key="index">
          <view class="c-popup-picker-item" v-for="item in col" :key="item[valueKey]">{{ item[labelKey] }}</view>
        </picker-view-column>
      </picker-view>
      <view class="c-popup-picker-actions">
        <button class="cmi-btn" type="primary" plain @click="handleCancel">{{ cancelText }}</button>
        <button class="cmi-btn" type="primary" @click="handleConfirm">{{ confirmText }}</button>
      </view>
    </view>
  </uni-popup>
</template>

<script>

export default {
  props: {
    title: {
      type: String,
      default: '请选择'
    },
    selectedIndexes: {
      type: Array,
      default: () => []
    },
    columns: {
      type: Array,
      default: () => []
    },
    valueKey: {
      type: String,
      default: 'value'
    },
    labelKey: {
      type: String,
      default: 'label'
    },
    cancelText: {
      type: String,
      default: '取消'
    },
    confirmText: {
      type: String,
      default: '确定'
    }
  },

  data () {
    return {
      indicatorStyle: 'height: 56px; line-height: 56px; text-align: center;',
      value: []
    }
  },

  computed: {
    result() {
      return { 
        value: this.value, 
        selectedItems: this.value.map((itemIndex, colIndex) => this.columns[colIndex][itemIndex]) 
      }
    }
  },

  watch: {
    selectedIndexes: {
      handler(selectedIndexes) {
        if (selectedIndexes.length !== 0) {
          this.value = selectedIndexes
        } else {
          this.value = new Array(columns.length).fill(0)
        }
      },
      immediate: true
    }
  },


  methods: {
    handleChange (e) {
      this.value = e.detail.value
    },

    handleCancel () {
      this.$emit('cancel')
      this.$refs.popup.close()
    },

    handleConfirm () {
      this.$emit('confirm', this.result)
      this.$refs.popup.close()
    },

    pick() {
      const { promise, resolve } = this.$util.createDefferred()
      // 监听自身的confirm事件
      this.$once('confirm', () => {
        resolve(this.result)
      })
      this.$refs.popup.open()
      return promise
    }
  }
}
</script>

<style lang="scss" scoped>
  .c-popup-picker {
    background-color: white;
    border-radius: 24rpx 24rpx 0 0;

    &-title {
      padding: 44rpx 46rpx;
      font-size: 30rpx;
      color: rgba(0, 0, 0, 0.9);
    }

    &-view {
      height: 336rpx;
    }

    &-item {
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 34rpx;
      color: rgba(0, 0, 0, 0.9);
    }

    &-actions {
      padding: 44rpx 40rpx;
      display: flex;
      gap: 40rpx;

      button {
        flex: auto;
      }
    }
  }
</style>