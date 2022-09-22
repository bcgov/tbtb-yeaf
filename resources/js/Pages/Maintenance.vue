<style scoped>
[type='checkbox']:checked, [type='radio']:checked {
    background-size: initial;
}
</style>
<template>
    <Head title="Maintenance" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                YEAF - Youth Education Assistance Fund
            </h2>
        </template>

        <div class="mt-3">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3 mt-3">
                        <div class="card">
                            <div class="card-header">
                                Maintenance Menu
                            </div>
                            <div class="card-body">
                                <MaintenanceMenu :page="page" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 mt-3">
                        <MaintenanceMinistry v-if="page === 'ministry'" :results="results" :provinces="provinces"></MaintenanceMinistry>
                        <MaintenanceStaff v-if="page === 'staff'" :results="results"></MaintenanceStaff>
                        <MaintenanceStaffEdit v-if="page === 'staff-edit'" :results="results"></MaintenanceStaffEdit>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>

</template>
<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import MaintenanceMenu from "@/Components/MaintenanceMenu";
import MaintenanceMinistry from "@/Components/MaintenanceMinistry";
import MaintenanceStaff from "@/Components/MaintenanceStaff";
import MaintenanceStaffEdit from "@/Components/MaintenanceStaffEdit";

export default {
    name: 'Maintenance',
    components: {
        MaintenanceMenu,
        BreezeAuthenticatedLayout, Head, Link, MaintenanceMinistry, MaintenanceStaff, MaintenanceStaffEdit
    },
    props: {
        results: Object,
        page: String,
        provinces: Object,
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
