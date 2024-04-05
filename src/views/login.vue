<template>
  <div class="font-sans  text-gray-900 max-w-7xl mx-auto h-screen w-full">
    <div class="grid md:grid-cols-2 items-center  h-full">
      <form @submit.prevent="login" class="max-w-lg  w-full ">
        <div class="mb-10">
          <h3 class="text-4xl font-extrabold">Sign in</h3>
          <p class="text-sm mt-6">Immerse yourself in a hassle-free login journey with our intuitively designed login form. Effortlessly access your account.</p>
        </div>
        <div>
          <label class="text-sm mb-3 block">Email</label>
          <div class="relative flex items-center">
            <input v-model="email" name="email" type="text" required class="w-full text-sm bg-gray-100 px-4 py-4 rounded-md outline-orange-600" placeholder="Enter email" />
          </div>
        </div>
        <div class="mt-6">
          <label class="text-sm mb-3 block">Password</label>
          <div class="relative flex items-center">
            <input v-model="password" name="password" type="password" required class="w-full text-sm bg-gray-100 px-4 py-4 rounded-md outline-orange-600" placeholder="Enter password" />
          </div>
        </div>
        <div class="mt-10">
          <button type="submit" class="w-full shadow-xl py-3 px-4 text-sm font-semibold rounded text-white bg-orange-700 hover:bg-orange-800 focus:outline-none">
            Log in
          </button>
        </div>
        <p class="text-sm mt-10 text-center">Don't have an account <router-link to="/register" class="text-orange-600 font-semibold hover:underline ml-1">Register here</router-link></p>
      </form>
      <div class="h-full  flex items-center relative max-md:before:hidden before:absolute before:bg-gradient-to-r before:from-gray-50 before:via-orange-400 before:to-orange-600 before:h-full before:w-3/4 before:-right-[71px] before:z-0">
        <img src="https://maghreb.simplonline.co/_next/image?url=https%3A%2F%2Fsimplonline-v3-prod.s3.eu-west-3.amazonaws.com%2Fmedia%2Fimage%2Fpng%2Fdark-blue-and-brown-illustrative-fitness-gym-logo-1-65ff80a75ac80796532732.png&w=1280&q=75" class="rounded-md lg:w-[42vw] lg:h-3/4  z-50 relative " alt="Dining Experience" />
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import router from '@/router';

export default {
  name: "Login",
  data() {
    return {
      email: "",
      password: "",
    };
  },
  methods: {
    async login() {
      try {
        const response = await axios.post("http://127.0.0.1:8000/api/auth/login", {
          email: this.email,
          password: this.password,
        });

        const token = response.data.token;

        localStorage.setItem('token', token);

        if (response.status === 200) {
          router.push("/user");
        } else {
          router.push("/");
        }
      } catch (error) {
        console.error("Error during login:", error);
      }
    },
  },
};
</script>


