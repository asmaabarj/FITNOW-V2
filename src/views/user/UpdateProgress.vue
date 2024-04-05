<template>
    <div>
      <sideBar />
      <div class="ml-72 mr-8">
        <h1 class="text-3xl font-bold my-8">Update Progress</h1>
        <form @submit.prevent="UpdateProgress">
          <div class="mb-4">
            <label for="weight" class="block text-gray-700 font-bold mb-2">Weight</label>
            <input type="text" id="weight" v-model="formData.weight" class="input-field" required>
          </div>
          <div class="mb-4">
            <label for="measurements" class="block text-gray-700 font-bold mb-2">Measurements</label>
            <input type="text" id="measurements" v-model="formData.measurements" class="input-field" required>
          </div>
          <div class="mb-4">
            <label for="performance" class="block text-gray-700 font-bold mb-2">Performance</label>
            <input type="text" id="performance" v-model="formData.performance" class="input-field" required>
          </div>
          <button type="submit" class="btn-primary">Save</button>
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