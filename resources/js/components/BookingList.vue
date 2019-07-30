<template>
    <div class="container">
        <h2 class="m-5 text-center">Booking List</h2>
        <div class="float-right mb-2">
            <router-link class="btn btn-primary" :to="{name: 'tourList'}">
                Tour List
            </router-link>
        </div>
        <table class="table table-hover">
            <thead class="thead-dark">
                <tr>
                    <th style="width:10%">#</th>
                    <th style="width:30%">Tour Name</th>
                    <th style="width:20%">Date</th>
                    <th style="width:20%">Passengers</th>
                    <th style="width:20%">Status</th>
                    <!-- <th style="width:20%">Action</th> -->
                </tr>
            </thead>
            <tbody>
                <tr v-for="(booking, index) in bookings" :key="index">
                    <th scope="row">{{ index + 1 }}</th>
                    <td><router-link class="text-dark" :to="{name: 'bookingEdit', params: {id: booking.id}}">{{ booking.tour_name }}</router-link></td>
                    <td>{{booking.tour_date}}</td>
                    <td>{{booking.passenger_count}}</td>
                    <td>{{status[booking.status]}}</td>
                    <!-- <td>
                        <router-link
                            class="btn btn-outline-success btn-sm" 
                            :to="{name: 'bookingAdd', params: {tourId: booking.id}}"
                        >Booking</router-link>
                    </td> -->
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                bookings: [],
                status: ['Submitted', 'Confirmed', 'Cancelled'],
            }
        },
        created() {
            const uri = 'http://localhost:8000/booking/';
            Axios.get(uri).then(response => {
                this.bookings = response.data;
            });
        },
    }
</script>
