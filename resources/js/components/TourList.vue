<template>
    <div class="container">
        <h2 class="m-5 text-center">Tour List</h2>
        <div class="float-right mb-2">
            <router-link class="btn btn-primary" :to="{name: 'tourAdd'}">
                Add New Tour
            </router-link>
            <router-link class="btn btn-success" :to="{name: 'tourAdd'}">
                Booking List
            </router-link>
        </div>
        <table class="table table-hover">
            <thead class="thead-dark">
                <tr>
                    <th style="width:10%">#</th>
                    <th style="width:50%">Tour</th>
                    <th style="width:20%">Status</th>
                    <th style="width:20%">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(tour, index) in tours" :key="index">
                    <th scope="row">{{ index + 1 }}</th>
                    <td>
                        <router-link class="text-dark" :to="{name: 'tourEdit', params: {id: tour.id}}">{{ tour.name }}</router-link>
                    </td>
                    <td>{{ tour.status }}</td>
                    <td>
                        <router-link class="btn btn-outline-success btn-sm" :to="{name: 'booking', params: {id: tour.id}}">Booking</router-link>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                tours: [],
            }
        },
        created() {
            const uri = 'http://localhost:8000/tour/';
            Axios.get(uri).then(response => {
                this.tours = response.data;
            });
        },
    }
</script>
