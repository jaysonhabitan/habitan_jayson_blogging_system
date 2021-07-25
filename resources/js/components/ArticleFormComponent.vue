<template>
  <t-modal
    v-model="value"
    class="z-10"
  >
    <template v-slot:header >
      <div class="text-center">
        {{ formTitle }} Post
      </div>
    </template>

    <validation-observer ref="observer">
      <div class="px-2 bg-white rounded">
        <validation-provider
          slim
          rules="required"
          name="Title"
          v-slot="props"
        >
        <label class="mb-2 text-sm font-bold text-gray-700">
          Title
          </label>
          <input
            class="w-full px-3 py-2 mb-1 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
            type="text"
            placeholder="Title *"
            v-model="title"
          />
          <div
            v-cloak
            class="text-red-500 text-xs sm:text-sm mt-0 mb-3 transition-all"
            v-text="props.errors[0]"
          ></div>
        </validation-provider>

        <validation-provider
          slim
          rules="required"
          name="Slug"
          v-slot="props"
        >
          <label class="mb-2 text-sm font-bold text-gray-700">
            Slug
          </label>
          <input
            class="w-full px-3 py-2 mb-1 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
            type="text"
            placeholder="Slug *"
            v-model="slug"
          />
        <div
            v-cloak
            class="text-red-500 text-xs sm:text-sm mt-0 mb-3 transition-all"
            v-text="props.errors[0]"
          ></div>
        </validation-provider>
          
        <validation-provider
          slim
          rules="required"
          name="Body"
          v-slot="props"
        >
          <div class="mb-4 md:mr-2 md:mb-0">
            <label class="mb-2 text-sm font-bold text-gray-700">
                Body
            </label>
            <label class="block">
              <textarea id="editor" v-model="body" class="form-textarea w-full px-3 py-2 mb-1 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline" rows="10" placeholder="Start writing your story..."></textarea>
            </label>
          </div>
          <div
            v-cloak
            class="text-red-500 text-xs mb-2 sm:text-sm mt-0 transition-all"
            v-text="props.errors[0]"
          ></div>
        </validation-provider>

        <div class="mb-4 mb-4">
          <label class="mb-2 text-sm font-bold text-gray-700">
                  <file-btn-component accept=".jpg,.png" @upload="setImage" />
          </label>

          <div class="flex"> 
              <img :src="postImagePath" alt="" class="rounded mx-auto">
          </div>
        </div>

        <validation-provider
          slim
          rules="required"
          name="Category"
          v-slot="props"
        >
          <div class="mb-4 md:mr-2 md:mb-0">
            <label class="mb-2 text-sm font-bold text-gray-700">
                Category Options
            </label>
            <label class="block">
              <t-select
                v-model="category_id"
                class="w-full px-3 py-2 mb-1 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                placeholder="Select a category"
                text-attribute="name"
                value-attribute="id"
                :options="categories"
              />
            </label>
          </div>
          <div
            v-cloak
            class="text-red-500 text-xs sm:text-sm mt-0 mb-3 transition-all"
            v-text="props.errors[0]"
          ></div>
        </validation-provider>
      </div>
    </validation-observer>

    <template v-slot:footer>
      <div class="flex justify-between">
        <button @click="toggleModal" class="bg-gray-300 hover:bg-gray-400 rounded text-white px-4 py-1">
          Cancel
        </button>
        <button @click="submit" class="bg-blue-500 hover:bg-blue-600 rounded text-white px-4 py-1">
          {{ submitLabel }}
        </button>
      </div>
    </template>
  </t-modal>
</template>


<script>
import { ValidationObserver, ValidationProvider } from 'vee-validate';
import axios from 'axios';
import _ from 'lodash';

export default {
  name: 'ComposeArticleComponent',

  components: {
    ValidationObserver,
    ValidationProvider,
  },

  props: {
    value: {
      type: Boolean,
      default: false,
    },

    post: {
      type: Object,
      default: null,
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
    title: null,
    slug: null,
    body: null,
    category_id: null,

    postImage: null,
    postImagePath: null,
  }),

  watch: {
    title(value) {
      if (value) {
        const lowerCase = _.lowerCase(value);
        this.slug = lowerCase.split(' ').join('-');
      } else {
        this.slug = null;
      }
    },
  },

  computed: {
    formTitle() {
      return this.post
        ? 'Edit'
        : 'Create'
    },

    submitLabel() {
      return this.post
        ? 'Update'
        : 'Post'
    },

    formData() {
      return {
          data: {
            attributes: {
              title: this.title,
              slug: this.slug,
              body: this.body,
              category_id: this.category_id,
              image_path: this.postImage,
            }
          }
        }
    }
  },

  mounted() {
    this.setData();
  },

  methods: {
    buildFormData(object) {
      const formData = new FormData();

      function appendToForm(data, parentKey) {
        if (_.isArray(data) || _.isPlainObject(data)) {
          if (_.size(data) > 0) {
            _.each(data, (value, key) => {
              appendToForm(value, parentKey ? `${parentKey}[${key}]` : key);
            });
          } else {
            formData.append(parentKey, '');
          }
        } else if (_.isBoolean(data)) {
          formData.append(parentKey, data ? 1 : 0);
        } else if (!_.isUndefined(data)) {
          formData.append(parentKey, data === null ? '' : data);
        }
      }

      appendToForm(object);

      return formData;
    },

    toggleModal() {
        this.$emit('toggle');
    },

    async setImage(files) {
      this.postImage = await this.$_imageHandler.compressImage(files[0]);
      this.postImagePath = await this.$_imageHandler.loadImage(this.postImage);
    },

    setData() {
      if (this.post) {
        this.title = this.post.title;
        this.slug = this.post.slug;
        this.body = this.post.body;
        this.category_id = this.post.category_id;
        this.postImagePath = this.post.image_path
      }
    },

    async submit() {
      if (!await this.$refs.observer.validate()) {
        this.submitting = false;
        return;
      }

      if (this.post) {
        return axios
          .post(`/blogs/posts/${this.post.id}`, this.buildFormData(this.formData))
          .then(() => {
            this.$store.dispatch('setSnackbar', {
              isVisible: true,
              text: "Post updated successfully",
              status: 'success'
            });

            this.gotoBlogList();
          }, (err) => {
            this.$store.dispatch('setSnackbar', {
                isVisible: true,
                text: this.$_errorParser.getFirstError(err),
                status: 'danger'
            });
          });
      }

      axios
        .post('/blogs/posts', this.buildFormData(this.formData))
        .then(() => {
          this.$store.dispatch('setSnackbar', {
            isVisible: true,
            text: "Post added successfully",
            status: 'success'
          });

          this.gotoBlogList();
        }, (err) => {
          this.$store.dispatch('setSnackbar', {
              isVisible: true,
              text: this.$_errorParser.getFirstError(err),
              status: 'danger'
          });
        });
    },

    gotoBlogList() {
      if (this.isFromBlogPage) {
        window.location.href = '/blogs';          
      } else {
        window.location.href = '/blogs/posts/me'
      }
    }
  }
}
</script>


<style>

</style>