import errorParser from './errorParser';
import imageHandler from './imageHandler';

export default {
  install(vm) {
    vm.prototype.$_errorParser = errorParser;
    vm.prototype.$_imageHandler = imageHandler;
  },
};
