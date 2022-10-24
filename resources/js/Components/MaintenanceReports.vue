<template>
    <div class="card mb-4">
        <div class="card-header">Ministry Reports</div>
        <div class="card-body">

            <a :href="route('maintenance.reports.students')" target="_blank" class="btn btn-primary w-100 mb-3">Download Students</a><br/>
            <a :href="route('maintenance.reports.grants')" target="_blank" class="btn btn-info w-100 mb-3">Download Grants</a><br/>
            <a :href="route('maintenance.reports.grantIneligibles')" target="_blank" class="btn btn-success w-100 mb-3">Download Grant Ineligibles</a><br/>
            <a :href="route('maintenance.reports.appeals')" target="_blank" class="btn btn-warning w-100 mb-3">Download Appeals</a><br/>
            <a :href="route('maintenance.reports.staff')" target="_blank" class="btn btn-danger w-100 mb-3">Download Staff</a><br/>
            <a :href="route('maintenance.reports.institutions')" target="_blank" class="btn btn-secondary w-100 mb-3">Download Institutions</a><br/>
            <a :href="route('maintenance.reports.comments')" target="_blank" class="btn btn-dark w-100 mb-3">Download Comments</a><br/>

        </div>


    </div>

</template>
<script>
import {Link} from '@inertiajs/inertia-vue3';

export default {
    name: 'MaintenanceReports',
    components: {
        Link
    },
    props: {
    },
    data() {
        return {
        }
    },
    methods: {

    },
    watch: {
    },
    computed: {
    },
    mounted() {
    }
}

</script>
