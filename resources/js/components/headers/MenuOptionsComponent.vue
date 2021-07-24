<template>
    <div class="flex items-center justify-center p-0">
      <div class="relative inline-block text-left dropdown">
        <span class="rounded-md shadow-sm">
          <button 
            class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition duration-150 ease-in-out bg-white border border-gray-300 rounded-md hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800" 
            @mouseleave="isDropDownVisible = false" 
            type="button" aria-haspopup="true" aria-expanded="true" aria-controls="headlessui-menu-items-117">
            <span>{{ title }}</span>
              <svg class="w-5 h-5 ml-2 -mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button
        ></span>
        <div class="opacity-0 invisible dropdown-menu transition-all duration-300 transform origin-top-right -translate-y-2 scale-95">
          <div class="absolute right-0 w-56 mt-2 origin-top-right bg-white border border-gray-200 divide-y divide-gray-100 rounded-md shadow-lg outline-none">
            <div class="py-1">
              <a 
                href="/blogs/posts/me" 
                class="hover:bg-gray-200 text-gray-700 flex justify-between w-full px-4 py-2 text-sm leading-5 text-center"
              >
                My Posts

                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
              </a>
            </div>
            <div class="py-1">
              <button 
                @click="logout" 
                class="font-bold hover:bg-gray-200 text-gray-700 flex justify-between w-full px-4 py-2 text-sm leading-5 text-center"
              >
                Sign out
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>          
</template>

<script>
import axios from 'axios';

export default {
  name: 'MenuOptionsComponent',
  
  props: {
    title: {
      type: String,
      default: null,
    },

    menuOptions: {
      type: Array,
      default: () => [],
    }
  },

  data: () => ({
    isDropDownVisible: false,
  }),

  methods: {
    toggleDropDown() {
      this.isDropDownVisible = !this.isDropDownVisible;
    },
  
    logout() {
      axios
        .post('/logout')
        .then(() => {
          this.$store.dispatch('setSnackbar', {
              isVisible: true,
              text: "Logged-out successfully",
              status: 'success'
          });

          window.location.href = '/';
        },(err) => {
          this.$store.dispatch('setSnackbar', {
              isVisible: true,
              text: this.$_errorParser.getFirstError(err),
              status: 'danger'
          });
        });
    }
  }
}
</script>