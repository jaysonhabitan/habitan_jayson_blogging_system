<template>
  <t-modal
    v-model="value"
  >
  <template v-slot:header>
    <div class="text-gray-500">
      Are you sure you want to delete <span v-if="post" class="font-extrabold">{{ post.title }}?</span>
    </div>
  </template>

  <span class="text-gray-500 font-bold">
    This action cannot be undone.
  </span> 
  <template v-slot:footer>
    <div class="flex justify-between">
      <button 
        class="uppercase 
        text-red-500 
        hover:text-white
        border 
        border-red-500 
        bg-transparent 
        hover:bg-red-500
        rounded
        p-2 
        flex 
        text-sm"
        @click="deletePost"
      >
        Delete
      </button>
      <button 
        class="uppercase 
        bg-gray-400 
        text-white
        hover:bg-gray-500
        border 
        rounded
        p-2 
        flex 
        text-sm"
        @click="closeModal"
      >
        Cancel
      </button>
     
    </div>
  </template>
</t-modal>
</template>

<script>
import axios from 'axios';

export default {
  name: "DeleteArticleComponent",

  props: {
    value: {
      type: Boolean,
      default: false,
    },

    post: {
      type: Object,
      default: null,
    },

    isFromBlogPage: {
      type: Boolean,
      default: false,
    }
  },

  methods: {
    deletePost() {
      axios
        .delete(`/blogs/posts/${this.post.id}`, {
          params: {
            isFromBlogPage: this.isFromBlogPage
          }
        })
        .then(() => {
          this.$store.dispatch('setSnackbar', {
            isVisible: true,
            text: "Post deleted",
            status: 'success'
          });

          if (this.isFromBlogPage) {
            window.location.href = '/blogs';          
          } else {
            window.location.href = '/blogs/posts/me'
          }
        }, (err) => {
          this.$store.dispatch('setSnackbar', {
              isVisible: true,
              text: this.$_errorParser.getFirstError(err),
              status: 'danger'
          });
        });
    },

    closeModal() {
      this.$emit('toggle');
    }
  }
}
</script>

<style>

</style>