# h5/小程序

## 1. 安装依赖
```
yarn install
```

## 2. 环境变量配置
1. 公共配置文件
```
.env
```
2. 本地配置文件，不会提交，覆盖`.env`
```
.env.local
``` 

3. QA配置文件，会提交，覆盖`.env`
```
.env.qa
``` 

4. PROD配置文件，会提交，覆盖`.env`
```
.env.production
``` 

## 3. 本地开发
### h5
```
yarn serve
```
### 微信小程序
```
yarn dev:mp-weixin
```

## 4. 构建

### h5 (QA, PROD)
```
yarn build
```
输出目录：`dist/build/h5`

### 微信小程序QA
```
yarn qa:mp-weixin
```
输出根目录: `dist/build/mp-weixin`

### 微信小程序PROD
```
yarn build:mp-weixin
```
输出根目录: `dist/build/mp-weixin`

## 5. 发布
### h5
将构建好的代码复制到`../web/public`目录
```
rm ../web/public/static/js/pages-* ../web/public/static/js/index.* ../web/public/static/js/chunk-vendors.*
cp -R dist/build/h5/* ../web/public
```

### 微信小程序
使用微信开发中工具打开输出目录（第一次需要导入）`dist/build/mp-weixin`，点击右上角上传