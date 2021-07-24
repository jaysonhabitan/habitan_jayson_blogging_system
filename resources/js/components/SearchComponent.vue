<template>
  <div class="flex flex-row">
      <input v-model="search"
        type="text"
        :placeholder="`Search ${searchBy} here...`"
        class="mr-1 rounded-md text-gray-700 border border-gray-400 leading-tight focus:outline-none focus:bg-white focus:border-gray-800 p-2 w-5/5 h-full"
      >
      <a  :href="searchRoute" class="mr-1 rounded hover:bg-blue-500 text-blue-700 font-base hover:text-white py-1 px-2 border border-blue-500 hover:border-transparent flex">
          Search
          <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16l2.879-2.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </a>
      <a  v-if="searchQuery" @click="search = null" :href="clearRoute" class="mr-2 rounded hover:bg-red-500 text-red-700 font-base hover:text-white py-1 px-2 border border-red-500 hover:border-transparent flex">
          CLEAR
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
      </a>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'SearchComponent',
  
  props: {
    goToRoute: {
      type: String,
      default: null,
    },
    
    searchBy: {
      type: String,
      default: '',
    },
    
    searchQuery: {
      type: String,
      default: '',
    }
  },

  data: () => ({
    search: null
  }),


  computed: {
    searchRoute() {
      if (!this.search) {
        return `/${this.goToRoute}`;
      }
      return `/${this.goToRoute}?search=${this.search}`;
    },

    clearRoute() {
        return `/${this.goToRoute}`;
    }
  },

  mounted() {
    this.search = this.searchQuery;
  },
}
</script>

<style>

</style>