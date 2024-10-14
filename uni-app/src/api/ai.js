import request from "@/utils/request.js";

// 视频脚本提取
export const extractVideoScript = (data) => {
  return request.post('ai/tools/extractCopyByUrl', data)
}

// 仿写视频脚本
export const rewriteVideoScript = (data) => {
  return request.post('ai/tools/script/rewrite', data)
}

// 视频脚本润色
export const polishVideoScript = (id, data) => {
  return request.post(`ai/tools/script/polish/${id}`, data)
}

// 视频脚本创作详情
export const getVideoScriptDetail = (id) => {
  return request.get(`ai/tools/script/detail/${id}`)
}

// 视频脚本创作列表
export const getVideoScriptList = (data) => {
  return request.get('ai/tools/script/lst', data)
}