import request from "../utils/request.js";

function getConfig() {
  return request.post('wxapi/system/getConfig');
}

module.exports = {
  getConfig: getConfig,
}