import request from "../utils/request.js";

function getPayments() {
  return request.get('wxapi/payment/getPayments');
}

module.exports = {
  getPayments: getPayments,
}