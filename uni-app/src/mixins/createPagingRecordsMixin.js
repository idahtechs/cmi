/**
 * 分页记录加载mixin创建器，只能作用于页面，不能作用于组件
 * @param {{ resourceName: string, pagingFetcher: Function, pageSize: number, loginRequired: boolean}} options 
 * @param {string} options.resourceName 加载资源名称，如：article，该名称会作为相关变量名或方法名都前缀使用
 * @param {Function} options.pagingFetcher 分页加载函数，({ page, pageSize }: { page: number, pageSize: number }) => Promise<{ data: { list: any[] }}>
 * @param {number} options.pageSize 分页大小
 * @param {boolean} options.loginRequired 是否需要登录
 * @returns Mixin
 * 
 * @example
 * // 页面
 * import createPagingRecordsMixin from '@/mixins/createPagingRecordsMixin'
 * 
 * export default {
 *   mixins: [
 *     createPagingRecordsMixin({
 *       resourceName: 'article',
 *       pagingFetcher: getArticleList,
 *       pageSize: 10
 *     })
 *   ],
 *   methods: {
 *     someMethod() {
 *       // 可使用以下生成的变量或方法
 *       this.articleRecords // 数据，数组
 *       this.articlePage // 当前分页
 *       this.articleLoading // 是否正在加载
 *       this.articleFinished // 是否加载完成
 *       this.articleLoadNextPage() // 加载下一页
 *       this.articleReload() // 重新加载
 *     }
 *   }
 * }
 */
export default function createPagingRecordsMixin(options) {
  const { resourceName, pagingFetcher, pageSize = 10, loginRequired = true } = options;

  if (!resourceName || !pagingFetcher) {
    return {};
  }

  const mixin = {
    data() {
      return {
        [`${resourceName}Records`]: [],
        [`${resourceName}Page`]: 0,
        [`${resourceName}Loading`]: false,
        [`${resourceName}Finished`]: false
      };
    },

    onLoad() {
      if (loginRequired) {
        if (this.$store.getters.isLogin) {
          this[`${resourceName}LoadNextPage`]()
        } else {
          this.$util.ensureLogin()
          this[`${resourceName}LoginWatcher`] = this.$watch('$store.getters.isLogin', (isLogin) => {
            if (isLogin) {
              this[`${resourceName}LoadNextPage`]()
            }
          })
        }
      } else {
        this[`${resourceName}LoadNextPage`]()
      }
    },

    onUnload() {
      if (this[`${resourceName}LoginWatcher`]) {
        this[`${resourceName}LoginWatcher`]()
      }
    },

    // 下拉刷新
    onPullDownRefresh() {
      if (!(loginRequired && !this.$store.getters.isLogin)) {
        this[`${resourceName}Reload`]().finally(() => uni.stopPullDownRefresh())
      } else {
        uni.stopPullDownRefresh()
      }
    },

    // 触底加载下一页
    onReachBottom() {
      if (!(loginRequired && !this.$store.getters.isLogin)) {
        this[`${resourceName}LoadNextPage`]()
      }
    },

    methods: {
      // load next page
      [`${resourceName}LoadNextPage`]: async function() {
        if (this[`${resourceName}Loading`] || this[`${resourceName}Finished`]) {
          return;
        }

        const page = this[`${resourceName}Page`] + 1
        this[`${resourceName}Loading`] = true

        const [err,res] = await this.$util.ef(pagingFetcher({ page, limit: pageSize }))
        this[`${resourceName}Loading`] = false

        if (err) {
          return uni.showToast({
            title: err.message,
            icon: 'none'
          })
        } 

        this[`${resourceName}Records`] = [...this[`${resourceName}Records`], ...res.data.list]
        this[`${resourceName}Page`] = page
        this[`${resourceName}Finished`] = res.data.list.length < pageSize
      },

      // reload
      [`${resourceName}Reload`]: async function() {
        this[`${resourceName}Page`] = 0
        this[`${resourceName}Records`] = []
        this[`${resourceName}Loading`] = false
        this[`${resourceName}Finished`] = false
        await this[`${resourceName}LoadNextPage`]()
      }
    }
  };

  return mixin
}