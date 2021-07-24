<template>
    <div class="min-h-screen bg-gray-100 text-gray-800 px-4 py-6 md:pt-1 flex flex-col justify-center sm:py-12">
        <div class="relative py-0 sm:max-w-xl md:w-3/5 mx-auto text-center">
            <span class="text-2xl font-light">Login to your account</span>
            <div class="relative mt-4 bg-white shadow-md sm:rounded-lg text-left">
                <div class="h-2 bg-gray-200 rounded-t-md"></div>
                    <div class="py-6 px-8">
                        <validation-observer ref="observer">
                            <validation-provider
                                slim
                                rules="required|email"
                                name="Email"
                                v-slot="props"
                            >
                            <label class="block font-semibold">Email</label>
                            <t-input 
                                v-model="email" 
                                placeholder="email@gmail.com" 
                                :disabled="submitting"
                                :class="{'cursor-not-allowed': submitting}"
                            />
                            <!-- <input v-model="email" type="text" placeholder="Email" class=" border w-full h-5 px-3 py-5 mt-2 hover:outline-none focus:outline-none focus:ring-1 focus:ring-indigo-600 rounded-md"> -->
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
                                type="password" 
                                placeholder="*********" 
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
                    
                    <div class="flex flex-row justify-end items-baseline">
                        <button 
                            @click="login"
                            :disabled="submitting"
                            :class="{
                                'cursor-not-allowed': submitting,
                                'bg-green-300' : submitting
                            }"
                            class="mt-4 bg-green-500 text-white py-2 px-6 rounded-md flex"
                        >
                            Login
                            <loading-icon-component v-if="submitting" />
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
    name: 'LoginComponent',

    components: {
        ValidationObserver,
        ValidationProvider
    },
  
    data: () => ({
        email: null,
        password: null,

        submitting: false,
    }),

    methods: {
        async login() {
            this.submitting = true;

            if (!await this.$refs.observer.validate()) {
                this.submitting = false;
                return;
            }

        axios
            .post('login', {
                email: this.email,
                password: this.password,
            })
            .then(() => {
                this.$store.dispatch('setSnackbar', {
                    isVisible: true,
                    text: "Logged-in successfully",
                    status: 'success'
                });
            
                window.location.href = '/';
            }, (err) => {
                this.$store.dispatch('setSnackbar', {
                    isVisible: true,
                    text: this.$_errorParser.getFirstError(err),
                    status: 'danger'
                });
            })
            .finally(() => {
                this.submitting = false;
            });
        },
    }
}
</script>
