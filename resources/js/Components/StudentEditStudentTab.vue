<style scoped>
tr {
    padding-bottom: 7px;
    display: block;
}
[type='checkbox']:checked, [type='radio']:checked {
    background-size: initial;
}
</style>
<template>
    <form v-if="editForm != null" @submit.prevent="updateStudent">
        <div class="row g-3">
            <div class="col-md-3">
                <div class="form-check form-switch">
                    <label for="inputInvestigate" class="form-check-label">Freeze</label>
                    <BreezeInput @change="$emit('investigate')" type="checkbox" role="switch" class="form-check-input" id="inputInvestigate" v-model="editForm.investigate" />
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-check form-switch">
                    <label for="inputOverride" class="form-check-label">Override</label>
                    <BreezeInput type="checkbox" role="switch" class="form-check-input" id="inputOverride" v-model="editForm.overaward_flag" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-check form-switch">
                    <label for="inputDisabilitySwitch" class="form-check-label">Permanent Disability (Documentation required)</label>
                    <BreezeInput class="form-check-input" type="checkbox" role="switch" id="inputDisabilitySwitch" v-model="editForm.pd" />
                </div>
            </div>

            <div class="col-md-4">
                <BreezeLabel for="inputSin" class="form-label" value="SIN" />
                <BreezeInput aria-readonly="true" type="text" class="form-control" id="inputSin" :value="editForm.sin" />
            </div>
            <div class="col-md-4">
                <BreezeLabel for="inputLastName" class="form-label" value="Last Name" />
                <BreezeInput type="text" class="form-control" id="inputLastName" v-model="editForm.last_name" />
            </div>
            <div class="col-md-4">
                <BreezeLabel for="inputFirstName" class="form-label" value="First Name" />
                <BreezeInput type="text" class="form-control" id="inputFirstName" v-model="editForm.first_name" />
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

            <div class="col-md-3">
                <BreezeLabel for="inputTele" class="form-label" value="Telephone" />
                <BreezeInput @keyup="formatPhoneNumber" type="text" class="form-control" id="inputTele" maxlength="13" v-model="editForm.tele" />
            </div>
            <div class="col-md-3">
                <BreezeLabel for="inputEmail" class="form-label" value="Email" />
                <BreezeInput type="email" class="form-control" id="inputEmail" v-model="editForm.email" />
            </div>
            <div class="col-md-3">
                <BreezeLabel for="inputBirth" class="form-label" value="Birth Date" />
                <BreezeInput type="date" placeholder="YYYY-MM-DD" class="form-control" id="inputBirth" v-model="editForm.birth_date" />
            </div>
            <div class="col-md-3">
                <BreezeLabel for="inputGender" class="form-label" value="Gender" />
                <BreezeSelect class="form-select" id="inputGender" v-model="editForm.gender">
                    <option></option>
                    <option value="M">Male</option>
                    <option value="F">Female</option>
                </BreezeSelect>
            </div>

            <div class="col-md-6">
                <BreezeLabel for="inputInstStudentNumber" class="form-label" value="Institution Student #" />
                <BreezeInput type="text" class="form-control" id="inputInstStudentNumber" v-model="editForm.institution_student_number" />
            </div>
            <div class="col-md-6">
                <BreezeLabel for="inputPend" class="form-label" value="PEN" />
                <BreezeInput type="text" class="form-control" id="inputPend" v-model="editForm.pen" />
            </div>

            <hr/>

            <div class="col-md-3">
                <BreezeLabel for="inputTotalAward" class="form-label" value="Total Over Award" />
                <BreezeInput type="text" class="form-control" id="inputTotalAward" v-model="editForm.overaward_amount" disabled />
            </div>
            <div class="col-md-3">
                <BreezeLabel for="inputTotalDeductedAward" class="form-label" value="- Deducted Over Award" />
                <BreezeInput type="text" class="form-control" id="inputTotalDeductedAward" v-model="editForm.overaward_deducted_amount" disabled />
            </div>
            <div class="col-md-3">
                <BreezeLabel for="inputTotalCurrent" class="form-label" value="= Current Over Award" readonly="readonly" />
                <BreezeInput type="text" class="form-control" id="inputTotalCurrent" :value="(editForm.overaward_amount - editForm.overaward_deducted_amount)" disabled />
            </div>
            <div class="col-md-3">
                <BreezeLabel for="inputTotalLifetime" class="form-label" value="Lifetime Total Award" />
                <BreezeInput type="text" class="form-control" id="inputTotalLifetime" :value="editForm.life" disabled />
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
            <button type="submit" class="btn mr-2 btn-outline-success" :disabled="editForm.processing">Update Student</button>
            <Link @click="back" class="btn btn-outline-primary float-right" href="#">Back</Link>
            <!--                                            <Link :href="route('student-grants.show', [result.id])" class="btn btn-outline-warning float-right mr-2">Grants</Link>-->
            <!--                                            <Link :href="route('student-comments.show', [result.id])" class="btn btn-outline-dark float-right mr-2">Comments</Link>-->
        </div>


        <div v-if="showSuccessMsg" class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div id="updateSuccessAlert" class="alert alert-success alert-dismissible fade show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="100">
                <div class="">
                    <div class="toast-body">
                        Student record was updated successfully.
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </form>

</template>
<script>
import {Link, useForm} from '@inertiajs/inertia-vue3';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeSelect from "@/Components/Select";

export default {
    name: 'StudentEditStudentTab',
    components: {
        BreezeInput, BreezeLabel, Link, BreezeSelect
    },
    props: {
        result: Object,
        now: String,
        countries: Object,
        provinces: Object,
    },
    data() {
        return {

            noChanges: true,
            showSuccessMsg: false,

            editForm: null,

        }
    },
    methods: {

        updateStudent: function ()
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

            this.editForm.put(route('students.update', this.result.id), {
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
                vm.noChanges = true;
            }, 5000);
        },
        formatPhoneNumber: function() {
            let cleaned = ('' + this.editForm.tele).replace(/\D/g, '');
            let match = cleaned.match(/^(\d{3})(\d{3})(\d{4})$/);
            if (match) {
                this.editForm.tele = '(' + match[1] + ') ' + match[2] + '-' + match[3];
            }
        },
        back: function()
        {
            window.history.back();
        },

    },
    watch: {

    },
    computed: {
    },
    mounted() {
        this.editForm = this.result;
        this.formatPhoneNumber();
    }
}

</script>
