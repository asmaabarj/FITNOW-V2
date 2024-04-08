<template>
    <div>
      <sideBare />
      <div class="ml-72 mr-8">
        <h1 class="text-3xl font-bold my-8 ">Progress History</h1>
        <div v-if="progressHistory.length === 0">
          <p>No progress history available.</p>
        </div>
        <div v-else>
          <div class="grid grid-cols-1  mb-10 gap-8">
            <div v-for="item in progressHistory" :key="item.id">
              <div class=" border-b-[2px] rounded-xs overflow-hidden">
                <div class="p-6">
                  <p class="text-base text-gray-600 mb-3"><strong>Weight: </strong>{{ item.weight }}</p>
                  <p class="text-base text-gray-600 mb-3"><strong>Measurements:</strong> {{ item.measurements }}</p>
                  <p class="text-base text-gray-600 mb-3"><strong>Performance:</strong> {{ item.performance }}</p>
                  <p class="text-base text-gray-600 mb-3"><strong>Status:</strong> {{ item.status }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import sideBare from '../../components/sidebar.vue';
  
  export default {
    components: {
      sideBare
    },
    data() {
      return {
        progressHistory: []
      };
    },
    mounted() {
      this.fetchProgressHistory();
    },
    methods: {
      fetchProgressHistory() {
        axios.get('http://127.0.0.1:8000/api/progress/history', {
            headers: {
              Authorization: `Bearer ${localStorage.getItem('token')}`
            }
          })
          .then(response => {
            this.progressHistory = response.data.progressHistory;
          })
          .catch(error => {
            console.error('Error fetching progress history:', error);
          });
      }
    }
  };
  </script>
  ../../components/sidebar.vue