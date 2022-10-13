
<template>
        <Head title="YEAF - School Edit" />

        <BreezeAuthenticatedLayout>
            <template #header>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit School
                </h2>
            </template>

            <div class="mt-3">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="card-header">
                                    YEAF Student Search
                                </div>
                                <div class="card-body">
                                    <StudentSearchBox />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 mt-3 mb-5">

                            <div class="card">
                                <div v-if="result != null" class="card-header">
                                    YEAF Edit School
                                </div>
                                <div class="card-body">

                                    <form v-if="editForm != null" @submit.prevent="updateSchool">
                                        <div class="row g-3">

                                            <div class="col-md-4">
                                                <BreezeLabel for="inputInstitutionName" class="form-label" value="Institution Name" />
                                                <BreezeInput aria-readonly="true" type="text" class="form-control" id="inputInstitutionName" :value="editForm.name" />
                                            </div>

                                            <div class="col-md-8">
                                                <BreezeLabel for="inputAddress" class="form-label" value="Address" />
                                                <BreezeInput type="text" class="form-control" id="inputAddress" v-model="editForm.address" />
                                            </div>
                                            <div class="col-md-4">
                                                <BreezeLabel for="inputCity" class="form-label" value="City" />
                                                <BreezeInput type="text" class="form-control" id="inputCity" v-model="editForm.city" />
                                            </div>

                                            <div class="col-md-4">
                                                <BreezeLabel for="inputProvince" class="form-label" value="Prov/State" />
                                                <BreezeSelect class="form-select" id="inputProvince" v-model="editForm.province">
                                                    <option v-for="(province,j) in provinces" :value="province.province_code">{{ province.province_name }}</option>
                                                </BreezeSelect>
                                            </div>
                                            <div class="col-md-4">
                                                <BreezeLabel for="inputPostal" class="form-label" value="Postal/Zip" />
                                                <BreezeInput type="text" class="form-control" id="inputPostal" maxlength="7" v-model="editForm.postal_code" />
                                            </div>
                                            <div class="col-md-4">
                                                <BreezeLabel for="inputCountry" class="form-label" value="Country" />
                                                <BreezeSelect class="form-select" id="inputCountry" v-model="editForm.country">
                                                    <option v-for="(country,j) in countries" :value="country.country_code">{{ country.country_name }}</option>
                                                </BreezeSelect>
                                            </div>

                                            <div class="col-md-4">
                                                <BreezeLabel for="inputTele" class="form-label" value="Telephone" />
                                                <BreezeInput @keyup="formatPhoneNumber" type="text" class="form-control" id="inputTele" maxlength="13" v-model="editForm.tele" />
                                            </div>
                                            <div class="col-md-4">
                                                <BreezeLabel for="inputFax" class="form-label" value="Fax" />
                                                <BreezeInput @keyup="formatPhoneNumber" type="text" class="form-control" id="inputFax" maxlength="13" v-model="editForm.fax" />
                                            </div>

                                            <div v-if="editForm.errors != undefined" class="row">
                                                <div class="col-12">
                                                    <div v-if="editForm.hasErrors == true" class="alert alert-danger mt-3">
                                                        <ul>
                                                            <li v-for="err in editForm.errors">{{ err }}</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="card-footer mt-3">
                                            <button type="submit" class="btn mr-2 btn-outline-success" :disabled="editForm.processing">Update School</button>
                                            <Link @click="back" class="btn btn-outline-primary float-right" href="#">Back</Link>
                                            <!--                                            <Link :href="route('student-grants.show', [result.id])" class="btn btn-outline-warning float-right mr-2">Grants</Link>-->
                                            <!--                                            <Link :href="route('student-comments.show', [result.id])" class="btn btn-outline-dark float-right mr-2">Comments</Link>-->
                                        </div>


                                        <div v-if="showSuccessMsg" class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                                            <div id="updateSuccessAlert" class="alert alert-success alert-dismissible fade show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="100">
                                                <div class="">
                                                    <div class="toast-body">
                                                        School record was updated successfully.
                                                    </div>
                                                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>



        </BreezeAuthenticatedLayout>

</template>
<script>
import {computed} from "vue";

import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import StudentSearchBox from '@/Components/StudentSearch.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeSelect from "@/Components/Select";

export default {
    name: 'SchoolEdit',
    components: {
        BreezeAuthenticatedLayout, StudentSearchBox, Head, BreezeInput, BreezeLabel, Link, BreezeSelect, useForm
    },
    props: {
        result: Object,
        now: String,
        countries: Object,
        provinces: Object,

},
    data() {
        return {
            editForm: null,
            showFailMsg: false,
            showSuccessMsg: false,

        }
    },
    methods: {
        back: function()
        {
            window.history.back();
        },
        formatPhoneNumber: function() {
            let cleaned = ('' + this.editForm.tele).replace(/\D/g, '');
            let match = cleaned.match(/^(\d{3})(\d{3})(\d{4})$/);
            if (match) {
                this.editForm.tele = '(' + match[1] + ') ' + match[2] + '-' + match[3];
            }
        },
        updateSchool: function ()
        {


            this.editForm = useForm({

                student_id: this.editForm.student_id,
                last_name: this.editForm.last_name,
                first_name: this.editForm.first_name,
                sin: this.editForm.sin,
                birth_date: this.editForm.birth_date,
                address: this.editForm.address,
                city: this.editForm.city,
                province: this.editForm.province,
                postal_code: this.editForm.postal_code,
                country: this.editForm.country,
                tele: this.editForm.tele,
                email: this.editForm.email,
                gender: this.editForm.gender,
                overaward_flag: this.editForm.overaward_flag,
                investigate: this.editForm.area_of_audit_code,
                pen: this.editForm.pen,
                pd: this.editForm.pd,
                institution_student_number: this.editForm.institution_student_number,

                life: this.result.life,
                overaward_amount: this.result.overaward_amount,
                overaward_deducted_amount: this.result.overaward_deducted_amount,


            });

            this.editForm.put(route('institutions.update', this.result.id), {
                onSuccess: () => {
                    this.showSuccessAlert();
                },
                onFailure: () => {
                },
                onError: () => {
                },
                preserveState: false,

            });
        },
        showSuccessAlert: function ()
        {
            this.showSuccessMsg = true;
            let vm = this;
            setTimeout(function (){
                vm.showSuccessMsg = false;
            }, 5000);
        },
        showFailAlert: function ()
        {
            this.showFailMsg = true;
            let vm = this;
            setTimeout(function (){
                vm.showFailMsg = false;
            }, 5000);
        },
    },
    watch: {
    },
    computed: {
    },
    mounted() {
        this.editForm = this.result;
    }
}
</script>
