import request from "@/utils/request.js";

// 视频脚本提取
export const extractVideoScript = (data) => {
  return request.post('ai/tools/extractCopyByUrl', data)
}

// 仿写视频脚本
export const generateVideoScript = (data) => {
  return request.post('ai/tools/script/initiation ', data)
}

// 重新仿写视频脚本
export const regenerateVideoScript = (id,data) => {
  return request.post(`ai/tools/script/recreate/${id}`, data)
}

// 删除视频脚本
export const deleteVideoScript = (id) => {
  return request.delete(`ai/tools/script/delete/${id}`)
}

// 删除视频脚本记录项
export const deleteVideoScriptVersion = (id) => {
  return request.delete(`ai/tools/script/version/delete/${id}`)
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