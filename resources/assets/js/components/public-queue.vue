<template>
    <div>
        <div class="panel panel-default">
            <div class="panel-heading">Queue <i v-if="load" class="fa fa-spinner fa-pulse fa-fw"></i></div>

            <div class="panel-body">
                <p>List of the customers being queued</p>
            </div>

            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th style="text-align: center">#</th>
                    <th style="text-align: center">Type</th>
                    <th style="text-align: center">Name</th>
                    <th style="text-align: center">Service</th>
                    <th style="text-align: center">Queued At</th>
                </tr>
                </thead>
                <tbody>

                <tr v-for=" (customer, index) in queue ">
                    <td style="text-align: center">{{ index + 1 }}</td>
                    <td style="text-align: center">
                        <span v-if="customer.type == 0">Citizen</span>
                        <span v-if="customer.type == 1">Organisation</span>
                        <span v-if="customer.type == 2">Anonymous</span>
                    </td>
                    <td style="text-align: center">
                        <span v-if="customer.type == 0">{{ customer.title}}. {{ customer.first_name }} {{ customer.last_name }}</span>
                        <span v-if="customer.type == 1">{{ customer.organisation }}</span>
                        <span v-if="customer.type == 2">Anonymous</span>
                    </td>
                    <td style="text-align: center">
                        <span v-if="customer.service == 0">Housing</span>
                        <span v-if="customer.service == 1">Benefits</span>
                        <span v-if="customer.service == 2">Council Tax</span>
                        <span v-if="customer.service == 3">Fly-Tipping</span>
                        <span v-if="customer.service == 4">Missed Bin</span>
                    </td>
                    <td style="text-align: center">{{ moment(customer.queued_at).format('HH:mm') }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                queue: [],
                load: false
            }
        },
        created() {
            this.loadQueue();
            eventBus.$on('update_queue', this.loadQueue);
        },
        methods: {
            loadQueue() {
                this.load = true;
                this.$http.get('/api/queue').then(function (response) {
                    console.log(response.statusText);
                    this.$set(this, 'queue', response.data.queue);
                    this.load = false;
                }).catch(function (error) {
                    console.log(error.statusText);
                    this.load = false;
                    alert("Couldn't load queue");
                })
            },
            moment(date) {
                return moment(date);
            }
        }
    }
</script>