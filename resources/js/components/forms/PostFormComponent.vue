<template>
<div class="container mx-auto border-black rounded-md text-black">
 <div class="container mx-auto">
			<div class="w-full px-6 my-12">
					<div class="mx-auto w-4/5 bg-white p-5 rounded-md">
						<h3 class="pt-4 text-2xl text-center">{{ formTitle }} Post</h3>
            <validation-observer ref="observer">
              <div class="px-8 pt-6 mb-4 bg-white rounded">
                <validation-provider
                  slim
                  rules="required"
                  name="Title"
                  v-slot="props"
                >
                <label class="mb-2 text-sm font-bold text-gray-700">
                  Title
                  </label>
                  <div class="grid grid-cols-4">
                    <div class="col-span-3">
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
                    </div>
                    <div class="ml-3">
                      <div class="flex flex-row p-2">
                        <t-toggle v-model="is_visible" class="mr-2 pa-0" />
                          <span class="text-l h-full font-semibold">
                            Is Visible?
                          </span> 
                      </div>
                    </div>
                  </div>
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
                        text-attribute="name"
                        value-attribute="id"
                        placeholder="Select a category"
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

                  <div class="mt-4" v-if="!post.published_at">
                     <input v-model="publish_now" type="checkbox" class="p-4 focus:outline-none cursor-pointer"/>
                    <span> Publish right away?</span>
                  </div>

                  <div class="mt-4" v-else>
                    <span class="text-base font-bold italic">Published At {{ formattedPublishDate }}</span>
                  </div>

                <div class="text-center">
                  <button
                    class="w-full px-4 py-2 font-bold text-white bg-blue-500 rounded-md hover:bg-blue-700 focus:outline-none focus:shadow-outline mt-16"
                    type="button"
                    @click="submit"
                  >
                    {{ submitLabel }}
                  </button>
                </div>
              </div>
            </validation-observer>
				</div>
			</div>
		</div>
</div>
</template>

<script>
import { ValidationObserver, ValidationProvider } from 'vee-validate';
import moment from 'moment';
import axios from 'axios';
import _ from 'lodash';

export default {
  name: 'PostFormComponent',

  components: {
    ValidationObserver,
    ValidationProvider,
  },

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
    title: null,
    slug: null,
    body: null,
    is_visible: false,
    category_id: null,
    publish_now: false,
    published_at: null,
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

  mounted() {
    this.setData();
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
        : 'Submit'
    },

    formattedPublishDate() {
      if (this.post.published_at) {
        return moment(this.post.published_at).format('LL');
      }
    }
  },

  methods: {
    setData() {
      if (this.post) {
        this.title = this.post.title;
        this.slug = this.post.slug;
        this.body = this.post.body;
        this.is_visible = this.post.is_visible;
        this.publish_now = !!this.post.published_at;
        this.published_at = this.post.published_at;

        this.category_id = this.post.category_id;
      }
    },

    async submit() {
      if (!await this.$refs.observer.validate()) {
        this.submitting = false;
        return;
      }

      if (this.post) {
        return axios
          .put(`/manages/posts/${this.post.id}`, {
          data: {
            attributes: {
              title: this.title,
              body: this.body,
              slug: this.slug,
              is_visible: this.is_visible,
              publish_now: this.publish_now,
              category_id: this.category_id,
            }
          }
        })
        .then(() => {
          this.$store.dispatch('setSnackbar', {
            isVisible: true,
            text: "Post updated successfully",
            status: 'success'
          });

          this.gotoPostList();
        }, () => {
          this.$store.dispatch('setSnackbar', {
            isVisible: true,
            text: this.$_errorParser.getFirstError(err),
            status: 'danger'
          });
        });
      }

      axios
        .post('/manages/posts', {
          data: {
            attributes: {
              title: this.title,
              slug: this.slug,
              body: this.body,
              is_visible: this.is_visible,
              publish_now: this.publish_now,
              category_id: this.category_id,
            }
          }
        })
        .then(() => {
          this.$store.dispatch('setSnackbar', {
            isVisible: true,
            text: "Post added successfully",
            status: 'success'
          });

          this.gotoPostList();
        }, () => {
          this.$store.dispatch('setSnackbar', {
            isVisible: true,
            text: this.$_errorParser.getFirstError(err),
            status: 'danger'
          });
        });
    },

    gotoPostList() {
      window.location.href = "/manages/posts";
    }
  }
}
</script>

<style>

</style>