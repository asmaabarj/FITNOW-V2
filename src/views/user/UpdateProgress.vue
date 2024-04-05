<template>
    <div>
      <sideBar />
      <div class="ml-72 mr-8">
        <h1 class="text-3xl font-bold my-8">Update Progress</h1>
        <form @submit.prevent="updateProgress"
      class="bg-white rounded-lg shadow-md p-5 mt-12 w-[45%] border-red-950 border-[1px] ">
    <div class="mb-4">
        <label for="weight" class="block text-sm font-medium text-gray-700">Weight:</label>
        <input type="text" v-model="formData.weight" id="weight" name="weight"
               class="mt-1 p-5 block w-full rounded-md  shadow-sm outline-none border-[1px] border-orange-500 "
               placeholder="Enter weight">
    </div>
    <div class="mb-4">
        <label for="measurements" class="block text-sm font-medium text-gray-700">Measurements:</label>
        <input type="text" v-model="formData.measurements" id="measurements" name="measurements"
               class="mt-1 p-5 block w-full rounded-md  shadow-sm outline-none border-[1px] border-orange-500 "
               placeholder="Enter measurements">
    </div>
    <div class="mb-4">
        <label for="performance" class="block text-sm font-medium text-gray-700">Performance:</label>
        <input type="text" v-model="formData.performance" id="performance" name="performance"
               class="mt-1 p-5 block w-full rounded-md  shadow-sm outline-none border-[1px] border-orange-500 "
               placeholder="Enter performance">
    </div>
    <button type="submit"
            class="w-full py-3 px-4 text-sm font-semibold rounded-md text-white bg-orange-500 hover:bg-orange-300 focus:outline-none">
        Save
    </button>
</form>

      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import sideBar from '../../components/sidebar.vue';
  import router from '@/router';
  export default {
    components: {
      sideBar
    },
    data() {
      return {
        formData: {
          weight: '',
          measurements: '',
          performance: ''
        }
      };
    },
    mounted() {
      this.fetchProgressData();
    },
    methods: {
        fetchProgressData() {
    const progressId = this.$route.params.id;
    console.log(progressId);
    axios.get(`http://127.0.0.1:8000/api/progressEdit/${progressId}`, {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`
        }
    })
    .then(response => {
        this.formData.weight = response.data.progress.weight;
        this.formData.measurements = response.data.progress.measurements;
        this.formData.performance = response.data.progress.performance;
    })
    .catch(error => {
        console.error('Error fetching progress data:', error);
    });
},

UpdateProgress() {
    const progressId = this.$route.params.id;
    axios.put(`http://127.0.0.1:8000/api/progress/${progressId}`, this.formData, {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`
        }
    })
    .then(() => {
        router.push('/myProgress');
    })
    .catch(error => {
        console.error('Error updating progress:', error);
    });
}

    }
  };
  </script>
  ../../components/sidebar.vue