<template>
    <div class="container">
        <h2 class="m-5 text-center text-primary">New Tour</h2>
        <form v-on:submit.prevent="createTour">
            <div class="form-group">
                <label>Status</label>
                <div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input" id="draft" name="status" value="0" v-model="tour.status"> 
                        <label class="custom-control-label" for="draft">Draft</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input" id="public" name="status" value="1" v-model="tour.status"> 
                        <label class="custom-control-label" for="public">Public</label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Tour Name</label>
                <input 
                    type="text" 
                    ref="name" 
                    v-model="tour.name"
                    placeholder="Tour Name"
                    :class="{'form-control': true, 'is-invalid': submitted && (!tour.name || !nameValid)}"
                />
                <div class="invalid-feedback">
                    <span v-if="!tour.name">The tour name field is required.</span>
                    <span v-else-if="!nameValid">The tour name is already used.</span>
                </div>
            </div>
            <div class="form-group">
                <label>Itinerary</label>
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
                <label>Available Dates</label>
                <div class="input-group mb-3" v-for="(date, index) in [...tour.dates, '']" :key="index">
                    <datepicker 
                        name="date[]" 
                        v-model="tour.dates[index]" 
                        :wrapper-class="'w60'"
                        :bootstrap-styling="true" 
                        :disabled-dates="tour"
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
                    name: '',
                    itinerary: '',
                    dates: [],
                    status: 0,
                },
                nameValid: null,
                submitted: false,
            }
        },
        components: {
            Datepicker
        },
        methods: {
            createTour() {
                this.submitted = true;
                this.validate().then(() => {
                    this.tour.dates = this.tour.dates.map((date) => 
                        new Date(moment(date).format('YYYY-MM-DD 00:00:00'))
                    );
                }).then(() => {
                    const uri = 'http://localhost:8000/tour/';
                    Axios.post(uri, this.tour).then(response => {
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
                    if (this.tour.name) {
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
                        reject('name');
                    } 
                    
                    if (!this.tour.itinerary) {
                        reject('itinerary');
                    }
                    
                    resolve();
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
        }
    }
</script>
