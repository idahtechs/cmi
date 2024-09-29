// +----------------------------------------------------------------------
// | CRMEB [ CRMEB赋能开发者，助力企业发展 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEB并不是自由软件，未经许可不能去掉CRMEB相关版权
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------
import E from 'wangeditor' // npm 安装
// const E = window.wangEditor // CDN 引入的方式
import util from '../../utils/bus'

// 获取必要的变量，这些在下文中都会用到
const { $, BtnMenu, DropListMenu, PanelMenu, DropList, Panel, Tooltip } = E
var _this = null
export default class AlertMenu extends BtnMenu {
    constructor(editor) {
        _this = editor
        // data-title属性表示当鼠标悬停在该按钮上时提示该按钮的功能简述
        const $elem = E.$(
            `<div class="w-e-menu" data-title="视频">
                <div class="iconfont iconshipin"></div>
            </div>`
        )
        super($elem, editor)
    }
    // 菜单点击事件
    clickHandler() {
        util.$emit('Video')
        // getvideoint()
    }
    tryChangeActive() {
        this.active()
    }
}