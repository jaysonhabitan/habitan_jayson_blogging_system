<template>
    <div class="min-h-screen bg-gray-100 text-gray-800 px-4 py-6 md:pt-1 flex flex-col justify-center sm:py-12">
        <div class="relative py-0 sm:max-w-xl md:w-3/5 mx-auto text-center">
            <span class="text-2xl font-light">Register now</span>
            <div class="relative mt-4 bg-white shadow-md sm:rounded-lg text-left">
            <div class="h-2 bg-gray-200 rounded-t-md"></div>
            <div class="py-6 px-8">
              <validation-observer ref="observer">
                <validation-provider
                  slim
                  rules="required"
                  name="Name"
                  v-slot="props"
                >
                  <label class="block font-semibold">Name</label>
                  <t-input 
                    v-model="name" 
                    placeholder="Name" 
                    :disabled="submitting"
                    :class="{'cursor-not-allowed': submitting}"
                  />
                  <div
                    v-cloak
                    class="text-red-500 text-xs sm:text-sm mt-1 transition-all"
                    v-text="props.errors[0]"
                  ></div>
                </validation-provider>

                <validation-provider
                  slim
                  rules="required|email"
                  name="Email"
                  v-slot="props"
                >
                  <label class="block mt-3 font-semibold">Email</label>
                  <t-input 
                    v-model="email" 
                    placeholder="email@gmail.com" 
                    :disabled="submitting"
                    :class="{'cursor-not-allowed': submitting}"
                  />
                  <div
                    v-cloak
                    class="text-red-500 text-xs sm:text-sm mt-1 transition-all"
                    v-text="props.errors[0]"
                    ></div>
                </validation-provider>


                <validation-provider
                  slim
                  rules="required"
                  name="Password"
                  v-slot="props"
                >
                  <label class="block mt-3 font-semibold">Password</label>
                  <t-input 
                    v-model="password" 
                    placeholder="Password" 
                    type="password"
                    :disabled="submitting"
                    :class="{'cursor-not-allowed': submitting}"
                  />
                <div
                  v-cloak
                  class="text-red-500 text-xs sm:text-sm mt-1 transition-all"
                  v-text="props.errors[0]"
                  ></div>
                </validation-provider>

                <validation-provider
                  slim
                  rules="required"
                  name="Confirm password"
                  v-slot="props"
                >
                  <label class="block mt-3 font-semibold">Confirm Password</label>
                  <t-input 
                    v-model="confirmPassword" 
                    placeholder="Confirm Password" 
                    type="password"
                    :disabled="submitting"
                    :class="{'cursor-not-allowed': submitting}"
                  />
                  <div
                    v-cloak
                    class="text-red-500 text-xs sm:text-sm mt-1 transition-all"
                    v-text="props.errors[0]"
                    ></div>
                </validation-provider>
              </validation-observer>

                <div class="flex justify-between items-baseline">
                  <a href="/" class="text-sm hover:underline">Cancel</a>
                  <button 
                    class="mt-4 bg-green-500 text-white py-2 px-6 rounded-md"
                    @click="register"
                  >
                    Register
                  </button>
                </div>
            </div>
          </div>
        </div>

      <snackbar-component />
    </div>
</template>

<script>
import { ValidationObserver, ValidationProvider } from 'vee-validate';
import axios from 'axios';

export default {
  name: 'RegisterComponent',

  components: {
    ValidationObserver,
    ValidationProvider,
  },
  
  data: () => ({
    name: null,
    email: null,
    password: null,
    confirmPassword: null,

    submitting: false,
  }),

  methods: {
    async register() {
      this.submitting = true;

      if (!await this.$refs.observer.validate()) {
        this.submitting = false;
        return;
      }

      axios
        .post('register', {
          name: this.name,
          email: this.email,
          password: this.password,
          password_confirmation: this.confirmPassword,
        })
        .then(() => {
          this.submitting = false;

          this.$store.dispatch('setSnackbar', {
            isVisible: true,
            text: "Registered successfully",
            status: 'success'
          });

          window.location.href = '/';
        }, (err) => {
          this.$store.dispatch('setSnackbar', {
              isVisible: true,
              text: this.$_errorParser.getFirstError(err),
              status: 'danger'
          });
        });
    },
  }
}
</script>
