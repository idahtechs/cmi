// +----------------------------------------------------------------------
// | CRMEB [ CRMEB赋能开发者，助力企业发展 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEB并不是自由软件，未经许可不能去掉CRMEB相关版权
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------
import { validUsername, validURL, validLowerCase, validUpperCase, validAlphabets } from '@/utils/validate.js'
describe('Utils:validate', () => {
  it('validUsername', () => {
    expect(validUsername('admin')).toBe(true)
    expect(validUsername('editor')).toBe(true)
    expect(validUsername('xxxx')).toBe(false)
  })
  it('validURL', () => {
    expect(validURL('https://github.com/PanJiaChen/vue-element-admin')).toBe(true)
    expect(validURL('http://github.com/PanJiaChen/vue-element-admin')).toBe(true)
    expect(validURL('github.com/PanJiaChen/vue-element-admin')).toBe(false)
  })
  it('validLowerCase', () => {
    expect(validLowerCase('abc')).toBe(true)
    expect(validLowerCase('Abc')).toBe(false)
    expect(validLowerCase('123abc')).toBe(false)
  })
  it('validUpperCase', () => {
    expect(validUpperCase('ABC')).toBe(true)
    expect(validUpperCase('Abc')).toBe(false)
    expect(validUpperCase('123ABC')).toBe(false)
  })
  it('validAlphabets', () => {
    expect(validAlphabets('ABC')).toBe(true)
    expect(validAlphabets('Abc')).toBe(true)
    expect(validAlphabets('123aBC')).toBe(false)
  })
})
