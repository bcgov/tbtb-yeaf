<style scoped>
[type='checkbox']:checked, [type='radio']:checked {
    background-size: initial;
}
</style>
<template>
    <Head title="Schools" />

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
                                YEAF Students Search
                            </div>
                            <div class="card-body">
                                <StudentSearchBox />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 mt-3">
                        <div class="card mb-3">
                            <div class="card-header">
                                YEAF Schools
                                <Link :href="route('institutions.create')" class="btn btn-sm btn-success float-end">New School</Link>
                            </div>
                            <div class="card-body">
                                <div v-if="results != null && results.data.length > 0" class="table-responsive pb-3">
                                    <table class="table table-striped">
                                        <thead>
                                        <SchoolsHeader></SchoolsHeader>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(row, i) in results.data">
                                            <th scope="row"><Link :href="route('institutions.show', [row.id])">{{ row.name }}</Link></th>
                                            <td>{{ row.city }}</td>
                                            <td></td>
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
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import StudentSearchBox from '@/Components/StudentSearch.vue';
import SchoolsHeader from '@/Components/SchoolsHeader.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import BreezeInput from "@/Components/Input";
import BreezeSelect from "@/Components/Select";
import BreezeLabel from "@/Components/Label";
import BreezePagination from "@/Components/Pagination";

export default {
    name: 'Schools',
    components: {
        BreezeAuthenticatedLayout, StudentSearchBox, SchoolsHeader, Head, Link, BreezeInput, BreezeSelect, BreezeLabel, BreezePagination
    },
    props: {
        results: Object,
    },
    data() {
    },
    methods: {
        countOveraward: function(a, b){
            let c = a - b;
            return this.formatMoney(c);
        },
        formatMoney: function (a){
            return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(a);
        }
    },
    watch: {
    },
    computed: {
    },
    mounted() {
    }
}
</script>
