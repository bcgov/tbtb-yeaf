<template>
    <div class="card mb-4">
        <div class="card-header">Ministry Reports</div>
        <div class="card-body">

            <a :href="route('maintenance.reports.index', 'students')" target="_blank" class="btn btn-primary w-100 mb-3">Download Students</a><br/>
            <a :href="route('maintenance.reports.index', 'grants')" target="_blank" class="btn btn-info w-100 mb-3">Download Grants</a><br/>
            <a :href="route('maintenance.reports.index', 'grantIneligibles')" target="_blank" class="btn btn-success w-100 mb-3">Download Grant Ineligibles</a><br/>
            <a :href="route('maintenance.reports.index', 'appeals')" target="_blank" class="btn btn-warning w-100 mb-3">Download Appeals</a><br/>
            <a :href="route('maintenance.reports.index', 'staff')" target="_blank" class="btn btn-danger w-100 mb-3">Download Staff</a><br/>
            <a :href="route('maintenance.reports.index', 'institutions')" target="_blank" class="btn btn-secondary w-100 mb-3">Download Institutions</a><br/>
            <a :href="route('maintenance.reports.index', 'comments')" target="_blank" class="btn btn-dark w-100 mb-3">Download Comments</a><br/>
            <a :href="route('maintenance.reports.index', 'ineligibles')" target="_blank" class="btn btn-light w-100 mb-3">Download Ineligibles</a><br/>
            <a :href="route('maintenance.reports.index', 'batches')" target="_blank" class="btn btn-link w-100 mb-3">Download Batches</a><br/>
            <a :href="route('maintenance.reports.index', 'programYears')" target="_blank" class="btn btn-link w-100 mb-3">Download Program Years</a><br/>

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
}

</script>
