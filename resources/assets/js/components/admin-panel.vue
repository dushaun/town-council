<template>
    <div>
        <div class="panel panel-default">
            <div class="panel-heading">New Customer <i v-if="load" class="fa fa-spinner fa-pulse fa-fw"></i></div>

            <div class="panel-body">
                <form>
                    <h4>Services</h4>
                    <div class="radio">
                        <label>
                            <input type="radio" name="optionsRadios" id="housing" :value=0 v-model="customer.service">
                            Housing
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="optionsRadios" id="benefits" :value=1 v-model="customer.service">
                            Benefits
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="optionsRadios" id="councilTax" :value=2 v-model="customer.service">
                            Council Tax
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="optionsRadios" id="flyTipping" :value=3 v-model="customer.service">
                            Fly-Tipping
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="optionsRadios" id="missedBin" :value=4 v-model="customer.service">
                            Missed Bin
                        </label>
                    </div>

                    <h4>Type</h4>
                    <div class="radio">
                        <label>
                            <input type="radio" name="optionsRadiosType" id="citizen" :value=0 v-model="customer.type">
                            Citizen
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="optionsRadiosType" id="organisation" :value=1 v-model="customer.type">
                            Organisation
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="optionsRadiosType" id="anonymous" :value=2 v-model="customer.type">
                            Anonymous
                        </label>
                    </div>

                    <div v-if="customer.type == 0">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <select class="form-control" id="title" v-model="customer.title">
                                <option>Mr</option>
                                <option>Mrs</option>
                                <option>Miss</option>
                                <option>Ms</option>
                                <option>Dr</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" id="first_name" v-model="customer.first_name">
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="form-control" id="last_name" v-model="customer.last_name">
                        </div>
                    </div>

                    <div v-if="customer.type == 1">
                        <div class="form-group">
                            <label for="organisation_data">Organisation</label>
                            <input type="text" class="form-control" id="organisation_data" v-model="customer.organisation">
                        </div>
                    </div>

                    <button type="button" class="btn btn-primary" @click="addEntry()">Submit</button>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                customer: {
                    service: 0,
                    type: 0,
                    title: null,
                    first_name: null,
                    last_name: null,
                    organisation: null
                },
                load: false
            }
        },
        methods: {
            addEntry() {
                this.load = true;
                this.$http.post('/api/admin/create', {
                    customer: this.customer
                }).then(function (response) {
                    console.log(response.statusText);
                    eventBus.$emit('update_queue');
                    this.customer.service = 0;
                    this.customer.type = 0;
                    this.customer.title = null;
                    this.customer.first_name = null;
                    this.customer.last_name = null;
                    this.customer.organisation = null;
                    this.load = false;
                }).catch(function (error) {
                    console.log(error.statusText);
                    this.load = false;
                    alert('Error submitting new customer');
                })
            }
        }
    }
</script>