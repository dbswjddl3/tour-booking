<template>
    <div class="container">
        <h2 class="m-5 text-center text-primary">Edit Tour</h2>
        <form v-on:submit.prevent="updateTour">
            <div class="form-group">
                <label for="">Tour Name</label>
                <input 
                    type="text" 
                    ref="name" 
                    v-model="tour.name"
                    placeholder="Tour Name"
                    :class="{'form-control': true, 'is-invalid': submitted && (!tour.name || nameValid === false)}"
                />
                <div class="invalid-feedback">
                    <span v-if="!tour.name">The tour name field is required.</span>
                    <span v-else-if="nameValid === false">The tour name is already used.</span>
                </div>
            </div>
            <div class="form-group">
                <label for="">Itinerary</label>
                <textarea 
                    rows="5"
                    ref="itinerary" 
                    v-model="tour.itinerary" 
                    placeholder="Itinerary" 
                    :class="{'form-control': true, 'is-invalid': submitted && !tour.itinerary}">
                </textarea>
                <div class="invalid-feedback">The itinerary field is required.</div>
            </div>
            <div class="form-group">
                <label for="">Available Dates</label>
                <div class="dates input-group mb-3" v-for="(date, index) in tour.oldDates" :key="index">
                    <input class="form-control" :value="date.date" disabled>
                    <div v-if="!date.status" class="input-group-append">
                        <span class="btn btn-outline-danger" @click="date.status = 1">Disable</span>
                    </div>
                    <div v-else class="input-group-append">
                        <span class="btn btn-outline-success" @click="date.status = 0">Enable</span>
                    </div>
                </div>
                <div class="input-group mb-3" v-for="(date, index) in [...tour.dates, '']">
                    <datepicker 
                        name="date[]" 
                        v-model="tour.dates[index]" 
                        :bootstrap-styling="true" 
                        :disabled-dates="selected"
                        :placeholder="'Select Date'"
                    ></datepicker>
                    <div class="input-group-append" v-show="date" @click="tour.dates.splice(index, 1)">
                        <span class="btn close" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
                <router-link class="btn btn-outline-primary" :to="'/'">Back</router-link>
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
                tour: {
                    originalName: '',
                    name: '',
                    itinerary: '',
                    oldDates: [],
                    dates: [],
                },
                selected: {
                    dates: []
                },
                nameValid: null,
                submitted: false,
            }
        },
        components: {
            Datepicker
        },
        created() {
            let uri = `http://localhost:8000/tour/${this.$route.params.id}`;
            Axios.get(uri).then(response => {
                this.tour.id = response.data.id;
                this.tour.status = response.data.status;
                this.tour.originalName = response.data.name;
                this.tour.name = response.data.name;
                this.tour.itinerary = response.data.itinerary;
                if (response.data.dates) {
                    this.selected.dates = response.data.dates.map(date => new Date(date.date));
                    this.tour.oldDates = response.data.dates.map(date => {
                        return {
                            'id': date.id,
                            'date': moment(date.date).format('DD MMM YYYY'),
                            'status': date.status
                        }
                    });
                }
            });
        },
        methods: {
            // selectDate(index) {
            //     // console.log(this.tour.oldDates)
            //     // this.selected.dates = this.tour.oldDates.map(date => new Date(date.date));
            //     // console.log(this.selected.dates)
            // },
            updateTour() {
                this.submitted = true;
                this.validate().then(() => {
                    this.tour.dates = this.tour.dates.map((date) => 
                        new Date(moment(date).format('YYYY-MM-DD 00:00:00'))
                    );
                }).then(() => {
                    const uri = `http://localhost:8000/tour/${this.$route.params.id}`;
                    Axios.patch(uri, this.tour).then(response => {
                        if (response.data.status === 'success') {
                            this.$router.push({name: 'tourList'});
                        } else {
                            alert(response.data.message);
                        }
                    });
                }).catch(elem => {
                    this.$refs[elem].focus();
                });     
            },
            validate() {
                return new Promise((resolve, reject) => {
                    if (!this.tour.name) {
                        reject('name');
                    } else if (!this.tour.itinerary) {
                        reject('itinerary');
                    } else if (this.tour.name && (this.tour.originalName != this.tour.name)) {
                        this.checkNameValid().then((data) => {
                            if (data === 0) {
                                this.nameValid = true;
                                resolve();
                            } else {
                                this.nameValid = false;
                                reject('name');
                            }
                        });
                    } else {
                        resolve();
                    } 
                })
            },
            checkNameValid() {
                return new Promise(resolve => {
                    const uri = `http://localhost:8000/tour/valid/${this.tour.name}`;
                    Axios.get(uri).then(response => {
                        resolve(response.data);
                    });
                })
            }
        },
    }
</script>