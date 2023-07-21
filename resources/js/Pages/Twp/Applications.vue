<style scoped>
[type='checkbox']:checked, [type='radio']:checked {
    background-size: initial;
}
</style>
<template>
    <Head title="Applications" />

    <BreezeAuthenticatedLayout v-bind="$attrs">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                TWP - Tuition Waiver Program
            </h2>
        </template>

        <div class="mt-3">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3 mt-3">
                        <div class="card">
                            <div class="card-header">
                                TWP Students Search
                            </div>
                            <div class="card-body">
                                <StudentSearchBox :schools="schools" page="twp.application-list" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 mt-3">
                        <div class="card mb-3">
                            <div class="card-header">
                                TWP Applications
                            </div>
                            <div class="card-body">
                                <div v-if="results != null && results.data.length > 0" class="table-responsive pb-3">
                                    <table class="table table-striped">
                                        <thead>
                                        <ApplicationsHeader></ApplicationsHeader>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(row, i) in results.data">
                                            <td><Link v-if="row.student != null" :href="route('twp.students.show', [row.student.id])">{{ row.student.last_name }}</Link></td>
                                            <td><span v-if="row.student != null">{{ row.student.first_name}}</span></td>
                                            <td><span v-if="row.student != null">{{ row.student.birth_date}}</span></td>
                                            <td><span v-if="row.student != null">{{ row.student.pen}}</span></td>
                                            <td><span v-if="row.student != null">{{ row.student.sin}}</span></td>
                                            <td>
                                                <span v-if="row.application_status == 'DENIED'">Denied</span>
                                                <BreezeSelect v-else @change="updateStatus(row, $event)" class="form-select" :id="'inputStudentAppStatus'+i" v-model="row.application_status">
                                                    <option value="APPROVED">Approved</option>
                                                    <option value="IN PROGRESS">In Progress</option>
                                                    <option value="APPROVED ON EXCEPTION">Approved on Exception</option>
                                                    <option value="WITHDRAWN">Withdrawn</option>
                                                </BreezeSelect>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <BreezePagination :links="results.links" :active-page="results.current_page" />
                                </div>
                                <h1 v-else class="lead">No results</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </BreezeAuthenticatedLayout>

</template>
<script>
import BreezeAuthenticatedLayout from '@/Layouts/Twp/Authenticated.vue';
import StudentSearchBox from '@/Components/Twp/StudentSearch.vue';
import ApplicationsHeader from '@/Components/Twp/ApplicationsHeader.vue';
import {Head, Link, useForm} from '@inertiajs/inertia-vue3';
import BreezeInput from "@/Components/Input";
import BreezeSelect from "@/Components/Select";
import BreezeLabel from "@/Components/Label";
import BreezePagination from "@/Components/Pagination";
import FormSubmitAlert from "@/Components/FormSubmitAlert";

export default {
    name: 'Applications',
    components: {
        BreezeAuthenticatedLayout, StudentSearchBox, ApplicationsHeader, Head, Link, BreezeInput, BreezeSelect, BreezeLabel, BreezePagination, FormSubmitAlert
    },
    props: {
        results: Object,
        countries: Object,
        provinces: Object,
        schools: Object,
    },
    data() {
        return {

        }
    },
    methods: {

        updateStatus: function (application, e) {
            let new_status = e.target.value;
            let editForm = useForm({
                status: new_status,
            });
            editForm.put(route('twp.application-status.update', application.id), {
                onSuccess: () => {},
            });

        },
    },
    mounted() {
    }
}
</script>
