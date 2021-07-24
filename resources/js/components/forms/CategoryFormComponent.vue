<template>
<div class="container mx-auto border-black rounded-md text-black">
 <div class="container mx-auto">
			<div class="w-full px-6 my-12">
					<div class="mx-auto w-4/5 bg-white p-5 rounded-md">
						<h3 class="pt-4 text-2xl text-center">{{ title }} Category</h3>
            <validation-observer ref="observer">
              <div class="px-8 pt-6 pb-0 mb-4 bg-white rounded">
                <validation-provider
                  slim
                  rules="required"
                  name="Name"
                  v-slot="props"
                >
                <label class="mb-2 text-sm font-bold text-gray-700">
                  Name
                  </label>
                  <div class="grid grid-cols-4">
                    <div class="col-span-3">
                      <input
                        class="w-full px-3 py-2 mb-3 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                        type="text"
                        placeholder="Name *"
                        v-model="name"
                      />
                    <div
                      v-cloak
                      class="text-red-500 text-xs sm:text-sm mt-0 mb-3 transition-all"
                      v-text="props.errors[0]"
                    ></div>
                    </div>
                    <div class="ml-3">
                      <div class="flex flex-row">
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
                    class="w-full px-3 py-2 mb-3 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
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
                  
                <div class="mb-4 md:mr-2 md:mb-0">
                  <label class="mb-2 text-sm font-bold text-gray-700">
                      Description
                  </label>
                  <label class="block">
                    <textarea v-model="description" class="form-textarea w-full px-3 py-2 mb-3 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline" rows="5" placeholder="Category description.."></textarea>
                  </label>
                </div>

                <div class="mb-6 text-center">
                  <button
                    class="w-full px-4 py-2 font-bold text-white bg-blue-500 rounded-md hover:bg-blue-700 focus:outline-none focus:shadow-outline mt-10"
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
import SwitchComponent from '../SwitchComponent.vue';
import axios from 'axios';
import _ from 'lodash';

export default {
  name: 'CategoryFormComponent',

  components: {
    ValidationObserver,
    ValidationProvider,
    SwitchComponent,
  },

  props: {
    routeName: {
      type: String,
      default: null,
    },

    category: {
      type: Object,
      default: null,
    }
  },

  data: () => ({
    name: null,
    slug: null,
    description: null,
    is_visible: false,
  }),
  
  watch: {
    name(value) {
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
    title() {
      return this.category
        ? 'Edit'
        : 'Create'
    },

    submitLabel() {
      return this.category
        ? 'Update'
        : 'Submit'
    }
  },

  methods: {
    setData() {
      if (this.category) {
        this.name = this.category.name;
        this.slug = this.category.slug;
        this.description = this.category.description;
        this.is_visible = this.category.is_visible;
      }
    },

    async submit() {
      if (!await this.$refs.observer.validate()) {
        this.submitting = false;
        return;
      }

      if (this.category) {
        return axios
          .put(`/manages/categories/${this.category.id}`, {
          data: {
            attributes: {
              name: this.name,
              slug: this.slug,
              description: this.description,
              is_visible: this.is_visible,
            }
          }
        })
        .then(() => {
          this.$store.dispatch('setSnackbar', {
            isVisible: true,
            text: "Category updated successfully",
            status: 'success'
          });

          this.gotoCategoryList();
        }, (err) => {
          this.$store.dispatch('setSnackbar', {
              isVisible: true,
              text: this.$_errorParser.getFirstError(err),
              status: 'danger'
          });
        });
      }

      axios
        .post('/manages/categories', {
          data: {
            attributes: {
              name: this.name,
              slug: this.slug,
              description: this.description,
              is_visible: this.is_visible,
            }
          }
        })
        .then(() => {
          this.$store.dispatch('setSnackbar', {
            isVisible: true,
            text: "Category added successfully",
            status: 'success'
          });

          this.gotoCategoryList();
        }, (err) => {
          this.$store.dispatch('setSnackbar', {
              isVisible: true,
              text: this.$_errorParser.getFirstError(err),
              status: 'danger'
          });
        });
    },

    gotoCategoryList() {
      window.location.href = "/manages/categories";
    }
  }
}
</script>

<style>

</style>