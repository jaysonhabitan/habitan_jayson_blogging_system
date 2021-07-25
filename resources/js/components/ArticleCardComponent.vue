<template>
  <div>
    <t-card class="rounded-b-none">
      <template v-slot:header>
        <div class="flex justify-between">
          <div class="text-xl text-gray-600 font-semibold">
            {{ post.title }}
            <span class="italic font-normal text-base">
              by {{ post.author.name }} 
              <span v-if="post.published_at">
                (Published At {{ formattedPublishDate }} | Published By {{ post.publisher.name }})
              </span>
            </span>
          </div>

          <div class="flex space-x-2">
            <div v-if="user.id 
              && user.id === post.user_id
              && !post.published_at
              && canDeletePost" 
              class="italic has-tooltip cursor-pointer hover:bg-transparent py-1 hover:text-red-500" 
              @click="toggleDeleteModal" 
            >
              <span class='tooltip rounded shadow-lg p-1 bg-gray-100 text-gray-500 -mt-8'>
                Delete Post
              </span>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
            </div>
            
            <div v-if="user.id 
              && user.id === post.user_id
              && !post.published_at
              && canUpdatePost" 
              class="italic has-tooltip cursor-pointer hover:bg-transparent py-1 hover:text-yellow-500" 
              @click="toggleModal" 
            >
              <span class='tooltip rounded shadow-lg p-1 bg-gray-100 text-gray-500 -mt-8'>
                Edit Post
              </span>
                <svg xmlns="http://www.w3.org/2000/svg" class="transform hover:scale-110 hover:bg-transparent h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
            </div>
          </div>
        </div>

        <article-form-component 
          v-model="isModalVisible" 
          @toggle="toggleModal"
          :post="post" 
          :categories="categories" 
          :is-from-blog-page="isFromBlogPage"
        />

        <delete-article-component 
          v-model="isDialogVisible"
          @toggle="toggleDeleteModal"
          :post="post" 
          :is-from-blog-page="isFromBlogPage"
        />
      </template>

      <div class="text-gray-600 text-base mb-3" style="white-space: pre-line">
        {{ post.body }}
      </div>

      <div class="flex border border-gray-100 rounded w-full" v-if="post.image_path">
        <img :src="post.image_path" class="mx-auto" />
      </div>
      
      <template v-slot:footer >
        <div class="flex justify-end ">
          <button @click="areCommentsVisible = !areCommentsVisible" class="uppercase text-gray-500 hover:text-white hover:bg-gray-400 rounded-md py-1 px-2 flex text-sm">
            Comments ({{ comments.length }})
            
            <span class="ml-1">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
              </svg>
            </span>
          </button>
        </div>
      </template>
    </t-card>

    <div v-if="areCommentsVisible">
      <t-card class="rounded-t-none border-t-0">
      <div v-for="comment in fetchedComments" :key="comment.id" class="text-base py-1 px-1">
        <span class="font-semibold">
          {{ comment.commenter.name }} 
        </span>

        <div v-if="user.id === comment.commenter.id && (canUpdateComment || canDeleteComment)" 
          class="italic has-tooltip cursor-pointer hover:bg-gray-50 py-1 rounded px-2" 
          @click="parseComment(comment)" 
        >
          <span class='tooltip rounded shadow-lg p-1 bg-gray-100 text-gray-500 -mt-8'>
            Click to edit comment
          </span>
            {{ comment.content }}
        </div>

        <div v-else class="italic">
            {{ comment.content }}
        </div>
      </div>

      <template v-slot:footer v-if="user.id">
        <textarea
          v-if="canCreateComment || content_id"
          v-model="content"
          rows="1" 
          class="bg-gray-200 
            rounded-md
            appearance-none 
            border-2 
            border-gray-200 
            w-full py-1 px-4 
            text-gray-700 
            focus:outline-none" 
          type="text" 
          placeholder="Write a comment..." 
        />

          <div class="flex pt-2" 
            :class="{
              'justify-between': content_id && canDeleteComment, 
              'justify-end': content_id && !canDeleteComment || !content_id, 
              }"
            >
            <button
              v-if="content && content_id && canDeleteComment"
              class="uppercase 
              text-red-500 
              hover:text-white
                border 
              border-red-500 
                bg-transparent 
              hover:bg-red-500
                rounded-md 
                py-1 
                px-2 
                flex 
                text-sm
                mr-3"
              @click="deleteComment"
            >
              Delete Comment
              <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
            </button>

          <div v-if="content" class="flex flex-row">
            <template v-if="content_id && canUpdateComment">
              <button 
                class="uppercase 
                  text-gray-500 
                  hover:text-white
                  border 
                  border-gray-500 
                  bg-transparent 
                  hover:bg-gray-500
                  rounded-md 
                  py-1 
                  px-2 
                  flex 
                  text-sm"
                @click="content = null"
              >
                  Clear
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>

              <button 
                class="uppercase 
                text-white 
                bg-green-400 
                  rounded-md 
                  py-1 
                  px-2 
                  flex 
                  text-sm
                  ml-2"
                @click="submit"
              >
                  Update
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5- ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
              </button>
            </template>

            <template v-if="!content_id">
              <button 
                class="uppercase 
                  text-gray-500 
                  hover:text-white
                  border 
                  border-gray-500 
                  bg-transparent 
                  hover:bg-gray-500
                  rounded-md 
                  py-1 
                  px-2 
                  flex 
                  text-sm"
                @click="content = null"
              >
                  Clear
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>

              <button 
                class="uppercase 
                text-white 
                bg-green-400 
                  rounded-md 
                  py-1 
                  px-2 
                  flex 
                  text-sm
                  ml-2"
                @click="submit"
              >
                  Comment
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5- ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
              </button>
            </template>
          </div>
        </div>
      </template>
    </t-card>
    </div>
  </div>
</template>

<script>
import moment from 'moment';
import axios from 'axios';
import _ from 'lodash';

export default {
  name: "ArticleCardComponent",

  props: {
    user: {
      type: Object,
      default: null,
    },

    post: {
      type: Object,
      default: null,
    },

    comments: {
      type: Array,
      default: () => [],
    },

    categories: {
      type: Array,
      default: () => [],
    },
    
    isFromBlogPage: {
      type: Boolean,
      default: false,
    }
  },

  data: () => ({
    areCommentsVisible: false,
    content: null,
    content_id: null,
    isModalVisible: false,
    isDialogVisible: false,

    fetchedComments: [],
  }),

  watch: {
    content(value) {
      if (!value) {
        this.content_id = null;
      }
    },
  },

  computed: {
    formattedPublishDate() {
      if (this.post.published_at) {
        return moment(this.post.published_at).format('LL');
      }

      return '';
    },

    canUpdatePost() {
      return _.find(this.user.permissions, {name: 'update-post'}) !== undefined;
    },

    canDeletePost() {
      return _.find(this.user.permissions, {name: 'delete-post'}) !== undefined;
    },

    canMangeComment() {
      return _.find(this.user.permissions, {name: 'manage-comment'}) !== undefined;
    },

    canCreateComment() {
      return _.find(this.user.permissions, {name: 'create-comment'}) !== undefined;
    },

    canUpdateComment() {
      return _.find(this.user.permissions, {name: 'update-comment'}) !== undefined;
    },

    canDeleteComment() {
      return _.find(this.user.permissions, {name: 'delete-comment'}) !== undefined;
    }
  },

  mounted() {
    this.fetchedComments = this.comments;
  },

  methods: {
    toggleModal() {
      this.isModalVisible = !this.isModalVisible;
    },

    toggleDeleteModal() {
      this.isDialogVisible = !this.isDialogVisible;
    },

    submit() {
      if (this.content_id) {
          return axios
            .put(`/posts/${this.post.id}/comments/${this.content_id}`, {
              data: {
                attributes: {
                  content: this.content,
                }
              }
            })
            .then(({ data }) => {
              const commentIndex = _.findIndex(this.fetchedComments, {id: this.content_id});
              this.fetchedComments.splice(commentIndex, 1, _.first(data.data));
              
              this.content_id = null;
              this.content = null;
            }, (err) => {
              this.$store.dispatch('setSnackbar', {
                  isVisible: true,
                  text: this.$_errorParser.getFirstError(err),
                  status: 'danger'
              });
            });
      }

      axios
        .post(`/posts/${this.post.id}/comments`, {
          data: {
            attributes: {
              content: this.content,
            },
          },
        })
        .then(({ data }) => {
          this.content = null;
          this.fetchedComments.push(_.first(data.data));
        }, (err) => {
          this.$store.dispatch('setSnackbar', {
              isVisible: true,
              text: this.$_errorParser.getFirstError(err),
              status: 'danger'
          });
        })
    },

    parseComment(comment) {
      this.content = comment.content;
      this.content_id = comment.id;
    },

    deleteComment() {
      axios
        .delete(`/posts/${this.post.id}/comments/${this.content_id}`)
        .then(() => {
          const commentIndex = _.findIndex(this.fetchedComments, {id: this.content_id});
          this.fetchedComments.splice(commentIndex, 1);

          this.content = null;
          this.content_id = null;
          
          this.$store.dispatch('setSnackbar', {
              isVisible: true,
              text: 'Comment deleted',
              status: 'success'
          });
        }, (err) => {
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

<style>

</style>