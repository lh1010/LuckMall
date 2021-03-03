import request from "../utils/request.js";

function getAdver(site) {
  return request.get('wxapi/adver/getAdver?site=' + site);
}

module.exports = {
  getAdver: getAdver,
}