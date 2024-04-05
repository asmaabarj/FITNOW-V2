<template>
    <div>
        <sideBare />

        <div class="ml-72 mr-8">
            <h1 class="text-3xl font-bold my-8 ">My Progress</h1>
            <div v-if="progress.length === 0">
                <p>No progress data available.</p>
            </div>
            <div v-else>
                <div class="grid grid-cols-1 md:grid-cols-2 mb-10  relative gap-8">
                    <div v-for="item in progress" :key="item.id">
                        <div class="bg-white shadow-md border-[1px] rounded-lg overflow-hidden relative">
                            <div class="p-6">
                                <p class="text-base text-gray-600 mb-3"><strong>Weight: </strong>{{ item.weight }}</p>
                                <p class="text-base text-gray-600 mb-3"><strong>Measurements:</strong> {{item.measurements }}</p>
                                <p class="text-base text-gray-600 mb-3"><strong>Performance:</strong> {{item.performance }}</p>
                                <p class="text-base text-gray-600 mb-3"><strong>Status:</strong> {{ item.status }}</p>
                            </div>
                            <button v-if="item.status !== 'Completed'" @click="markComplete(item.id)"
                                class="bg-green-500 hover:bg-green-600 absolute text-white py-1 px-2 rounded-md top-0 right-0 mt-3 mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="white"><path d="m10 15.586-3.293-3.293-1.414 1.414L10 18.414l9.707-9.707-1.414-1.414z"></path></svg></button>
                            <div class="bg-orange-100 px-6 py-4 flex justify-end">
                                <button @click="deleteProgress(item.id)"
                                    class="bg-orange-600 hover:bg-orange-700 text-white py-2 px-4 rounded-md mr-2">Delete</button>
                                <button @click="updateProgress(item.id)"
                                    class="bg-orange-900 hover:bg-orange-900 text-white py-2 px-4 rounded-md">Update</button>
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
import sideBare from '../components/sidebar.vue';

export default {
    components: {
        sideBare
    },
    data() {
        return {
            progress: []
        };
    },
    mounted() {
        this.fetchProgress();
    },
    methods: {
        fetchProgress() {
            axios.get('http://127.0.0.1:8000/api/showProgress', {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('token')}`
                }
            })
                .then(response => {
                    this.progress = response.data.progress;
                })
                .catch(error => {
                    console.error('Error fetching progress:', error);
                });
        },
        deleteProgress(id) {
            axios.delete(`http://127.0.0.1:8000/api/progress/${id}`, {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('token')}`
                }
            })
                .then(() => {
                    this.progress = this.progress.filter(item => item.id !== id);
                })
                .catch(error => {
                    console.error('Error deleting progress:', error);
                });
        },
        markComplete(id) {
            axios.put(`http://127.0.0.1:8000/api/progress/${id}/complete`, null, {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('token')}`
                }
            })
                .then(() => {
                    const index = this.progress.findIndex(item => item.id === id);
                    if (index !== -1) {
                        this.progress[index].status = 'Completed';
                    }
                })
                .catch(error => {
                    console.error('Error marking progress as complete:', error);
                });
        }
    }
};
</script>