import _ from 'lodash';

export default {
  getFirstError(data) {
    if (data.response.data.message) {
      const error =  data.response.data.message

      return error;
    } else {
      const error =  _.first(data.response.data.errors)

      return error.detail;
    }
  }
}