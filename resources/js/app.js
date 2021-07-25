import extendValidationRules from './extendValidationRules';
import VueTailwind from 'vue-tailwind';
import Vue from 'vue';
import store from './store';
import plugins from './plugins';
import {
    TInput,
    TSelect,
    TButton,
    TCard,
    TModal,
    TToggle,
    TDialog,
    TCheckbox,
    TCheckboxGroup,
} from 'vue-tailwind/dist/components';
import { mapState } from 'vuex';


const settings = {
    't-modal': {
      component: TModal,
      props: {
        fixedClasses: {
          overlay: 'z-20  overflow-auto scrolling-touch left-0 top-0 bottom-0 right-0 w-full h-full fixed bg-opacity-50',
          wrapper: 'relative mx-auto z-30 max-w-3xl px-3 py-12',
          modal: 'overflow-visible relative  rounded',
          body: 'p-3',
          header: 'border-b p-3 rounded-t',
          footer: ' p-3 rounded-b',
        },
        classes: {
          overlay: 'bg-black',
          wrapper: '',
          modal: 'bg-white shadow',
          body: 'p-3',
          header: 'border-gray-100',
          footer: 'bg-gray-100',
          overlayEnterClass: 'opacity-0',
          overlayEnterActiveClass: 'transition ease-out duration-100',
          overlayEnterToClass: 'opacity-100',
          overlayLeaveClass: 'opacity-100',
          overlayLeaveActiveClass: 'transition ease-in duration-75',
          overlayLeaveToClass: 'opacity-0',
          enterClass: '',
          enterActiveClass: '',
          enterToClass: '',
          leaveClass: '',
          leaveActiveClass: '',
          leaveToClass: ''
        },
        variants: {
          danger: {
            overlay: 'bg-red-100',
            header: 'border-red-50 text-red-700',
            close: 'bg-red-50 text-red-700 hover:bg-red-200 border-red-100 border',
            modal: 'bg-white border border-red-100 shadow-lg',
            footer: 'bg-red-50'
          }
        }
      }
    },
    TInput,
    TDialog,
    TToggle,
    TButton,
    TSelect, 
    TCard,
    TCheckbox,
    TCheckboxGroup,
  }


Vue.use(VueTailwind, settings)
Vue.use(plugins);

window.Vue = require('vue').default;
const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
 
extendValidationRules();

const app = new Vue({
    el: '#app',
    store,

    computed: {
      ...mapState(['snackbar'])
    },
});
