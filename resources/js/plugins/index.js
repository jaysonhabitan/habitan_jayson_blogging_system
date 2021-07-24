import errorParser from './errorParser';

export default {
  install(vm) {
    vm.prototype.$_errorParser = errorParser;
  },
};
