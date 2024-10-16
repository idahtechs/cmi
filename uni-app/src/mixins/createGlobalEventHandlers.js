/**
 * 用于为页面注册全局事件处理器（用uni.$emit触发的全局事件），无需手动解绑事件处理器。事件处理器会延迟到页面onShow中触发
 * @param {{ [key: string]: (...args: any[]) => void }} handlers 事件处理器集合，以事件名为键，事件处理为值的对象，注意，事件处理器必须是函数，不能为箭头函数
 * @returns 
 * @example
 * import { createGlobalEventHandlers } from '@/mixins/createGlobalEventHandlers'
 *
 * export default {
 *   mixins: [
 *    createGlobalEventHandlers({
 *      // 事件名：事件处理（必须是函数，不能为箭头函数）
 *      'ai_records_updated': function() { this.reload() }
 *    })
 *   ]
 * }
 */
export default function createGlobalEventHandlers(handlers = {}) {
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