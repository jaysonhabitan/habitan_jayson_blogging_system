<template>
    <div> 
      <button @click="toggleModal" class="bg-transparent uppercase mr-8 hover:bg-green-500 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded flex ">
          Compose an article

          <span class="ml-1">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
          </span>
      </button>

        <article-form-component 
          v-model="isModalVisible"
          :categories="categories" 
          @toggle="toggleModal"
        />
      </div>
</template>

<script>
import axios from 'axios';
import _ from 'lodash';

export default {
  name: 'ComposeArticleComponent',

  props: {
    post: {
      type: Object,
      default: null,
    },
    categories: {
      type: Array,
      default: () => [],
    }
  },

  data: () => ({
    isModalVisible: false,
  }),

  methods: {
    toggleModal() {
        this.isModalVisible = !this.isModalVisible;
    },
  }
}
</script>
