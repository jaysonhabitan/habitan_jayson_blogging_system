<template>
<div class="container mx-auto border-black rounded-md text-black">
 <div class="container mx-auto">
			<div class="w-full px-6 my-12">
					<div class="mx-auto w-4/5 bg-white p-5 rounded-md">
						<h3 class="pt-4 text-2xl text-center">{{ title }} User</h3>
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
                </validation-provider>

                <validation-provider
                  slim
                  rules="required"
                  name="Email"
                  v-slot="props"
                >
                  <label class="mb-2 text-sm font-bold text-gray-700" for="email">
                    Email
                  </label>
                  <input
                    class="w-full px-3 py-2 mb-3 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                    type="email"
                    placeholder="Email *"
                    v-model="email"
                  />
                <div
                    v-cloak
                    class="text-red-500 text-xs sm:text-sm mt-0 mb-3 transition-all"
                    v-text="props.errors[0]"
                  ></div>
                </validation-provider>
          
              
                <div class="mb-4 md:mr-2 md:mb-0">
                  <label class="mb-2 text-sm font-bold text-gray-700" for="password">
                    Password
                  </label>
                  <input
                    class="w-full px-3 py-2 mb-3 text-sm leading-tight text-gray-700 border ounded shadow appearance-none focus:outline-none focus:shadow-outline"
                    type="password"
                    placeholder="******************"
                    v-model="password"
                  />
                </div>

                <validation-provider
                  slim
                  rules="required"
                  name="Password"
                  v-slot="props"
                >
                
                <div class="mb-4 md:mr-2 md:mb-0">
                  <label class="mb-2 text-sm font-bold text-gray-700" for="password">
                    Role Options
                  </label>
                  <t-select
                        v-model="role"
                        class="w-full px-3 py-2 mb-1 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                        text-attribute="name"
                        value-attribute="name"
                        placeholder="Select a Role"
                        :options="roles"
                        @change="changeSetPermission"
                      />
                </div>
                <div
                    v-cloak
                    class="text-red-500 text-xs sm:text-sm mt-0 mb-3 transition-all"
                    v-text="props.errors[0]"
                  ></div>
                </validation-provider>

                <t-checkbox-group 
                  v-model="selectedPermissions" 
                  :options="formattedPermissions"
                />

                <div class="mb-6 text-center">
                  <button
                    class="w-full px-4 py-2 font-bold text-white bg-blue-500 rounded-md hover:bg-blue-700 focus:outline-none focus:shadow-outline mt-10"
                    type="button"
                    @click="submit"
                  >
                    Submit
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
import axios from 'axios';
import _ from 'lodash';

export default {
  name: 'UserFormComponent',

  components: {
        ValidationObserver,
        ValidationProvider,
  },

  props: {
    user: {
      type: Object,
      default: null,
    },

    roles: {
      type: Array,
      defaul: () => [],
    },

    permissions: {
      type: Array,
      defaul: () => [],
    }
  },

  data: () => ({
    name: null,
    email: null,
    password: null,
    role: null,
    formattedPermissions: [],
    selectedPermissions: [],

    canChangePermission: false,
  }),

  mounted() {
    this.canChangePermission = false;

    this.setData();
    this.filterPermissions();
    this.setPermissions();
  },

  computed: {
    title() {
      return this.user
        ? 'Edit'
        : 'Create'
    },
  },

  methods: {
    setData() {
      if (this.user) {
        this.name = this.user.name;
        this.email = this.user.email;
        this.role = _.first(this.user.roles).name || '';
      }
    },

    setPermissions() {
      if (!this.role) return;

      this.selectedPermissions = _.map(this.user.permissions, (permission) => {
          return permission.name
      });
    },

    changeSetPermission() {
      //Made this condition so that @change whill not trigger on page load.
      if (!this.canChangePermission) {
        this.canChangePermission = true;

        return;
      }

      const selectedRole = _.find(this.roles, {name: this.role});

      this.selectedPermissions = _.map(selectedRole.permissions, (permission) => {
          return permission.name
      });
    },

    filterPermissions() {
      this.formattedPermissions = _.map(this.permissions, (permission) => {
          return permission.name
      });
    },

    async submit() {
      if (!await this.$refs.observer.validate()) {
        return;
      }

      if (this.user) {
        return axios
          .put(`/manages/users/${this.user.id}`, {
          data: {
            attributes: {
              name: this.name,
              email: this.email,
              password: this.password,
            },
            relationships: {
              role: this.role,
              permissions: this.selectedPermissions
            }
          }
        })
        .then(() => {
          this.$store.dispatch('setSnackbar', {
                isVisible: true,
                text: "User updated successfully",
                status: 'success'
              });

          this.gotoUsersList();
        }, (err) => {
          this.$store.dispatch('setSnackbar', {
            isVisible: true,
            text: this.$_errorParser.getFirstError(err),
            status: 'danger'
          });
        });
      }

      axios
        .post('/manages/users', {
          data: {
            attributes: {
              name: this.name,
              email: this.email,
              password: this.password,
            },
            relationships: {
              role: this.role,
              permissions: this.selectedPermissions,
            }
          }
        })
        .then(() => {
          this.$store.dispatch('setSnackbar', {
            isVisible: true,
            text: "User added successfully",
            status: 'success'
          });
          
          this.gotoUsersList();
        }, (err) => {
            this.$store.dispatch('setSnackbar', {
              isVisible: true,
              text: this.$_errorParser.getFirstError(err),
              status: 'danger'
          });
        });
    },

    gotoUsersList() {
      window.location.href = "/manages/users";
    }
  }
}
</script>

<style>

</style>