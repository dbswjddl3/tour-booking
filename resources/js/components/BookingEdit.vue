<template>
    <div class="container">
        <h2 class="m-5 text-center text-primary">Booking</h2>
        <form v-on:submit.prevent="updateBooking">
            <input type="hidden" name="tour_id" :value="booking.tour_id">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Tour Name</label>
                <div class="col-sm-10">
                    <input disabled class="form-control-plaintext" type="text" :value="booking.tour_name" >
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Tour Date</label>
                <div class="col-sm-10">
                    <select name="tour_date" v-model="booking.tour_date" :class="{'custom-select': true, 'mr-sm-2': true, 'form-control': true, 'is-invalid': submitted && !booking.tour_date}">
                        <option value="">Choose...</option>
                        <option v-for="date in tour_dates" :key="date.index" :value="date.value" ref="tour_date">{{date.label}}</option>
                    </select>
                    <div class="invalid-feedback">The Tour Date field is required.</div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Passengers</label>
                <div class="col-sm-10 text-right">
                    <input type="button" class="btn btn-success" value="Add" @click="booking.passengers.push({checked: false, given_name: '', surname: '', email: '', mobile: '', passport: '', birth_date: '', special_request: ''})">
                </div>
            </div>
            <div class="form-group">
                <ul v-if="data.status === 0" v-for="(data, index) in booking.passengers" class="list-group mb-2">
                    <li class="list-group-item">
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label>Given Name</label>
                                <input type="text" v-model="data.given_name" :class="{'form-control': true, 'is-invalid': data.checked && !data.given_name}" placeholder="Given Name">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Surname</label>
                                <input type="text" v-model="data.surname" :class="{'form-control': true, 'is-invalid': data.checked && !data.surname}" placeholder="Surname">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Email</label>
                                <input type="text" v-model="data.email" :class="{'form-control': true, 'is-invalid': data.checked && !data.email}" placeholder="Email">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Mobile</label>
                                <input type="text" v-model="data.mobile" :class="{'form-control': true, 'is-invalid': data.checked && !data.mobile}" placeholder="Mobile">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Passport</label>
                                <input type="text" v-model="data.passport" :class="{'form-control': true, 'is-invalid': data.checked && !data.passport}" placeholder="Passport">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Birth Date</label>
                                <datepicker 
                                     v-model="data.birth_date"
                                    :input-class="{'form-control': true, 'is-invalid': data.checked && !data.birth_date}" 
                                    :placeholder="'Select Birth Date'"
                                ></datepicker>
                            </div>
                            <div class="col-md-12">
                                <label>Special Request</label>
                                <textarea class="form-control" v-model="data.special_request" placeholder="Special Request"></textarea>
                            </div>
                        </div>
                    </li>
                    <input type="button" class="btn btn-secondary mb-2" value="Remove" @click="booking.passengers.splice(index, 1)">
                </ul>
            </div>
            <div class="text-center mb-10">
                <button type="submit" class="btn btn-primary">Submit</button>
                <router-link class="btn btn-outline-primary" :to="{name: 'bookingList'}">Back</router-link>
            </div>
        </form>
    </div>
</template>
<script>
    import Datepicker from 'vuejs-datepicker';
    import moment from 'moment'
    
    export default {
        data() {
            return {
                booking: {
                    id: '',
                    tour_id: '',
                    tour_name: '',
                    tour_date: '',
                    passengers: [],
                },
                tour_dates: [],
                submitted: false,
            }
        },
        components: {
            Datepicker
        },
        created() {
            let uri = `http://localhost:8000/booking/${this.$route.params.id}`;
            Axios.get(uri).then(response => {
                this.booking = response.data.booking;
                if (response.data.tour_dates) {
                    this.tour_dates = response.data.tour_dates.map(date => {
                        return {
                            'value': moment(date.date).format('YYYY-MM-DD'),
                            'label': moment(date.date).format('DD MMM YYYY'),
                        }
                    });
                }
            });
        },
        methods: {
            updateBooking() {
                this.submitted = true;
                if (this.validate()) {
                    const uri = `http://localhost:8000/booking/${this.$route.params.id}`;
                    Axios.patch(uri, this.booking).then(response => {
                        if (response.data.status === 'success') {
                            this.$router.push({name: 'bookingList'});
                        } else {
                            alert(response.data.message);
                        }
                    });
                }
            },
            validate() {
                const passenger_required = ['given_name', 'surname', 'email', 'mobile', 'passport', 'birth_date'];
                let valid = true;

                if (!this.booking.tour_date) {
                    valid = false;
                }

                this.booking.passengers.forEach((passenger, index) => {
                    this.booking.passengers[index].checked = true;
                    if (valid) {
                        Object.keys(passenger).some((elem) => {
                            if (passenger_required.includes(elem) && !passenger[elem]) {
                                valid = false;
                            }
                        });
                    }
                });

                return valid;
            },
        },
    }
</script>