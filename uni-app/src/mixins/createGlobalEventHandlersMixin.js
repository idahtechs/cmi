/**
 * 用于为页面注册全局事件处理器（用uni.$emit触发的全局事件）的mixin创建器，无需手动解绑事件处理器。事件处理器会延迟到页面onShow中触发
 * @param {{ [key: string]: (...args: any[]) => void }} handlers 事件处理器集合，以事件名为键，事件处理为值的对象，注意，事件处理器必须是函数，不能为箭头函数
 * @returns 
 * @example
 * // 注册全局事件处理器的页面
 * import { createGlobalEventHandlersMixin } from '@/mixins/createGlobalEventHandlersMixin'
 *
 * export default {
 *   mixins: [
 *    createGlobalEventHandlersMixin({
 *      // 事件名：事件处理（必须是函数，不能为箭头函数）
 *      'ai_records_updated': function() { this.reload() }
 *    })
 *   ]
 * }
 * 
 * // 页面外的地方触发事件，上述页面将在页面onShow中调用相应的事件处理器
 * uni.$emit('ai_records_updated')
 */
export default function createGlobalEventHandlersMixin(handlers = {}) {
  const eventNames = Object.keys(handlers)

  if (eventNames.length === 0) {  
    return {}
  }

  return {
    onLoad: function () {
      this._mixin_geh_event_queue = []
      this._mixin_geh_event_queue_inner_handlers = {}
      eventNames.forEach(eventName => {
        this._mixin_geh_event_queue_inner_handlers[eventName] = (...args) => {
          this._mixin_geh_event_queue.push(() => handlers[eventName]?.call(this, ...args))
        }
        uni.$on(eventName, this._mixin_geh_event_queue_inner_handlers[eventName])
      })
    },
    onShow: function () {
      this._mixin_geh_event_queue.forEach(fn => fn())
      this._mixin_geh_event_queue = []
    },
    onUnload: function () {
      eventNames.forEach(eventName => {
        uni.$off(eventName, this._mixin_geh_event_queue_inner_handlers[eventName])
      })
    }
  }
}