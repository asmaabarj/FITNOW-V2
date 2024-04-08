<template>
 
      <sideBare />
  <div class="font-sans text-gray-900 max-w-7xl mx-auto h-screen flex flex-col  items-center mr-96">
    <div class="max-w-2xl p-8 ">
      <h1 class="text-3xl font-bold mb-8">Welcome to FitNow</h1>
      <div class="mb-8">
        <p class="text-lg">You are now logged in. Enjoy your FitNow experience!</p>
      </div>
      <form @submit.prevent="createProgress"
        class="bg-white rounded-lg shadow-md p-5 mt-12 w-[120%] border-red-950 border-[1px] ">
        <div class="mb-4">
          <label for="weight" class="block text-sm font-medium text-gray-700">Weight:</label>
          <input type="text" v-model="weight" id="weight" name="weight"
            class="mt-1 p-5 block w-full rounded-md  shadow-sm outline-none border-[1px] border-orange-500 "
            placeholder="Enter weight">
        </div>
        <div class="mb-4">
          <label for="measurements" class="block text-sm font-medium text-gray-700">Measurements:</label>
          <input type="text" v-model="measurements" id="measurements" name="measurements"
            class="mt-1 p-5 block w-full rounded-md  shadow-sm outline-none border-[1px] border-orange-500 "
            placeholder="Enter measurements">
        </div>
        <div class="mb-4">
          <label for="performance" class="block text-sm font-medium text-gray-700">Performance:</label>
          <input type="text" v-model="performance" id="performance" name="performance"
            class="mt-1 p-5 block w-full rounded-md  shadow-sm outline-none border-[1px] border-orange-500 "
            placeholder="Enter performance">
        </div>
        <button type="submit"
          class="w-full py-3 px-4 text-sm font-semibold rounded-md text-white bg-orange-500 hover:bg-orange-300 focus:outline-none">Create
          Progress</button>
      </form>
    </div>
  </div>
</template>

<script>
import sideBare from '../../components/sidebar.vue'

import axios from 'axios';

export default {
  components: {
    sideBare
  },
  data() {
    return {
      weight: '',
      measurements: '',
      performance: ''
    };
  },
  methods: {
    createProgress() {
      const progressData = {
        weight: this.weight,
        measurements: this.measurements,
        performance: this.performance
      };

      axios.post('http://127.0.0.1:8000/api/progress', progressData, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`
        }
      })
        .then(response => {
          console.log('Progress created successfully:', response.data);
          this.weight = '';
          this.measurements = '';
          this.performance = '';
        })
        .catch(error => {
          console.error('Error creating progress:', error.response.data);
        });
    },
  }
}
</script>
