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
    <div class="accordion" id="accordionGrant">
        <div v-for="(grant,i) in grantForms" class="accordion-item">
            <h2 class="accordion-header" :id="'heading'+i">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" :data-bs-target="'#collapse'+i" :aria-expanded="i===0" :aria-controls="'collapse'+i">
                    Grant #{{ i+1 }}
                </button>
            </h2>
            <div :id="'collapse'+i" class="accordion-collapse collapse" :class="i===0?'show':''" :aria-labelledby="'heading'+i" data-bs-parent="#accordionGrant">
                <div class="accordion-body">
                    <form @submit.prevent="updateGrant(i)">
                        <div class="row g-3">


                            <div class="col-md-4">
                                <BreezeLabel :for="'inputInstitution'+i" class="form-label" value="Institution" />
                                <BreezeSelect class="form-select" :id="'inputInstitution'+i" v-model="grant.institution_id">
                                    <option v-for="(school,j) in schools" :value="school.institution_id">{{ school.name }}</option>
                                </BreezeSelect>
                            </div>
                            <div class="col-md-4">
                                <BreezeLabel :for="'inputProgramName'+i" class="form-label" value="Program" />
                                <BreezeInput type="text" class="form-control" :id="'inputProgramName'+i" v-model="grant.program_name" />
                            </div>
                            <div class="col-md-4">
                                <BreezeLabel :for="'inputDateReceived'+i" class="form-label" value="Date Received" />
                                <BreezeInput type="text" class="form-control" :id="'inputDateReceived'+i" v-model="grant.application_receive_date" />
                            </div>

                            <div class="col-md-4">
                                <BreezeLabel :for="'inputProgramType'+i" class="form-label" value="Program Type" />
                                <BreezeSelect class="form-select" :id="'inputProgramType'+i" v-model="grant.program_code">
                                    <option v-for="(pt,j) in program_types" :value="pt.program_code">{{ pt.program_description }}</option>
                                </BreezeSelect>
                            </div>
                            <div class="col-md-4">
                                <BreezeLabel :for="'inputProgramYear'+i" class="form-label" value="Program Year" />
                                <BreezeSelect class="form-select" :id="'inputProgramYear'+i" v-model="grant.program_year_id">
                                    <option value=""></option>
                                    <option v-for="(py,j) in program_years" :value="py.program_year_id">{{ py.year_start }}/{{ py.year_end }}</option>
                                </BreezeSelect>
                            </div>
                            <div class="col-md-4">
                                <BreezeLabel :for="'inputProgramOfficer'+i" class="form-label" value="Program Officer" />
                                <BreezeSelect class="form-select" :id="'inputProgramOfficer'+i" v-model="grant.officer_user_id">
                                    <option v-for="(officer,j) in all_staff" :value="officer.user_id">{{ officer.first_name }} {{ officer.last_name }}</option>
                                </BreezeSelect>
                            </div>

                            <div class="col-md-4">
                                <BreezeLabel :for="'inputStartDate'+i" class="form-label" value="Study Start Date" />
                                <BreezeInput type="text" class="form-control" :id="'inputStartDate'+i" v-model="grant.study_start_date" />
                            </div>
                            <div class="col-md-4">
                                <BreezeLabel :for="'inputEndDate'+i" class="form-label" value="Study End Date" />
                                <BreezeInput type="text" class="form-control" :id="'inputEndDate'+i" v-model="grant.study_end_date" />
                            </div>
                            <div class="col-md-4">
                                <BreezeLabel :for="'inputAge'+i" class="form-label" value="Age" />
                                <BreezeInput type="text" class="form-control" :id="'inputAge'+i" v-model="grant.age" />
                            </div>

                            <div class="col-md-4">
                                <BreezeLabel :for="'inputApplicationNumber'+i" class="form-label" value="Application #" />
                                <BreezeInput type="text" class="form-control" :id="'inputApplicationNumber'+i" v-model="grant.application_number" />
                            </div>
                            <div class="col-md-4">
                                <BreezeLabel :for="'inputApplicationType'+i" class="form-label" value="Application Type" />
                                <BreezeSelect class="form-select" :id="'inputApplicationType'+i" v-model="grant.application_type">
                                    <option value=""></option>
                                    <option value="SFAS Extract">SFAS Extract</option>
                                    <option value="Paper Application">Paper Application</option>
                                </BreezeSelect>
                            </div>
                            <div class="col-md-4">
                                <BreezeLabel :for="'inputApplicationStatus'+i" class="form-label" value="Application Status" />
                                <BreezeSelect @change="cboStatus_BeforeUpdate(i)" class="form-select" :id="'inputApplicationStatus'+i" v-model="grant.status_code">
                                    <option value="A">Approved</option>
                                    <option value="D">Denied</option>
                                    <option value="P">Pending</option>
                                </BreezeSelect>
                            </div>
                        </div>

                        <div v-if="grant.status_code === 'P'" class="card mt-3">
                            <div class="card-header">Pending Reasons<button class="btn btn-sm float-end btn-success">add row +</button></div>
                            <div class="card-body">
                                <div class="row mb-3" v-for="(grantPending, j) in grant.grant_pending_ineligibles">
                                    <div class="col-md-10">
                                        <BreezeLabel class="form-label" value="Pending Reason" />
                                        <BreezeSelect class="form-select" v-model="grantPending.ineligible_code_id">
                                            <template v-for="ineligible in ineligibles">
                                                <option v-if="ineligible.code_type === 'P' && ineligible.active_flag === true" :value="ineligible.code_id">{{ ineligible.description }}</option>
                                            </template>
                                        </BreezeSelect>
                                    </div>
                                    <div class="col-md-2">
                                        <BreezeLabel class="form-label" value="Clear?" />
                                        <div class="form-check form-switch">
                                            <BreezeInput type="checkbox" role="switch" class="form-check-input" v-model="grantPending.cleared_flag" />
                                        </div>
                                    </div>
                                </div><!-- end existing rows -->
                                <div class="row mb-3" v-for="(grantPending, j) in grant.new_grant_pending_ineligibles">
                                    <div class="col-md-10">
                                        <BreezeSelect class="form-select" v-model="grantPending.ineligible_code_id">
                                            <template v-for="ineligible in ineligibles">
                                                <option v-if="ineligible.code_type === 'P' && ineligible.active_flag === true" :value="ineligible.code_id">{{ ineligible.description }}</option>
                                            </template>
                                        </BreezeSelect>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check form-switch">
                                            <BreezeInput type="checkbox" role="switch" class="form-check-input" v-model="grantPending.cleared_flag" />
                                        </div>
                                    </div>
                                </div><!-- end existing rows -->

                                <div class="row">
                                    <div class="col-12">
                                        <BreezeLabel class="form-label" value="Custom Pending Reason" />
                                        <textarea class="form-control" v-model="grant.custom_pending_reason"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="grant.status_code === 'D'" class="card mt-3">
                            <div class="card-header">Denied Reasons</div>
                            <div class="card-body">
                                <div class="col-md-4">
                                    <BreezeSelect class="form-select">
                                        <template v-for="ineligible in ineligibles">
                                            <option v-if="ineligible.code_type === 'D' && ineligible.active_flag === true" :value="ineligible.ineligible_code_type">{{ ineligible.description }}</option>
                                        </template>
                                    </BreezeSelect>
                                </div>
                            </div>
                        </div>


                        <div v-if="grant.errors != undefined" class="row">
                            <div class="col-12">
                                <div v-if="grant.hasErrors == true" class="alert alert-danger mt-3">
                                    <ul>
                                        <li v-for="err in grant.errors">{{ err }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer mt-3">
                            <button @click="evaluateGrant(i)" type="button" class="btn mr-2 btn-outline-success">Save &amp; Evaluate App</button>
<!--                            <button type="submit" class="btn mr-2 btn-outline-success" :disabled="grant.processing">Evaluate App</button>-->
                        </div>


                        <div v-if="showSuccessMsg" class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                            <div id="updateSuccessAlert" class="alert alert-success alert-dismissible fade show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="100">
                                <div class="">
                                    <div class="toast-body">
                                        Grant record was updated successfully.
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

</template>
<script>
import axios from 'axios';
import {Link, useForm} from '@inertiajs/inertia-vue3';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeSelect from "@/Components/Select";

export default {
    name: 'StudentEditGrantTab',
    components: {
        BreezeInput, BreezeLabel, Link, BreezeSelect
    },
    props: {
        result: Object,
        now: String,
        countries: Object,
        provinces: Object,

        program_types: Object,
        program_years: Object,
        schools: Object,
        batches: Object,
        ineligibles: Object,
        active_staff: Object,
        all_staff: Object,

    },
    data() {
        return {

            noChanges: true,
            showSuccessMsg: false,

            grantForms: [],

        }
    },
    methods: {
        cboStatus_BeforeUpdate: function (index)
        {
            let cancel = false;
            let grant = this.grantForms[index];
            grant.evaluationValid = true;
            if(grant.institution_id == null || grant.study_end_date == null || grant.study_start_date == null || grant.program_code == null || grant.program_year_id == null)
            {
                alert("You cannot change the status to Approved until all required fields are filled in.");
                cancel = true;
            }

            if(grant.total_yeaf_award > 0 && grant.status_code === 'A')
            {
                alert("You cannot change the status from Approved when an award has been given.");
                cancel = true;
            }

            if(cancel){
                this.grantForms[index].status_code = this.result.grants[index].status_code;
                grant.evaluationValid = false;
                return false;
            }
        },
        updateStudent: function (index)
        {
            this.grantForms[index].put(route('grants.update', this.grantForms[index].id), {
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

        evaluateGrant: function(index){
            let grant = this.grantForms[index];
            grant.evaluationValid = true;

            if(grant.application_receive_date == null || grant.program_year_id === ''){
                alert("You must fill in at least the program year and the date received before evaluating the application.");
                grant.evaluationValid = false;
            }else{
                if(grant.status_code === 'A' && grant.total_yeaf_award > 0){
                    alert("Once an award has been made, you cannot 'evaluate' an application.");
                    grant.evaluationValid = false;
                }else if(grant.program_year_id === ''){
                    alert("You must select a program year.");
                    grant.evaluationValid = false;
                }else{
                    let vm = this;
                    vm.formSubmitting = true;

                    let formData = new FormData();
                    formData.append('frm', JSON.stringify(this.grantForms[index]));
                    axios({
                        url: route('grants.evaluate', this.grantForms[index].id),
                        data: formData,
                        method: 'post',
                        headers: {'Accept': 'application/json', 'Content-Type': 'multipart/form-data'}
                    })
                        .then(function (response) {
                            vm.showSuccessAlert();
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                }
            }
        }

    },
    watch: {

    },
    computed: {
    },
    mounted() {
        this.grantForms = JSON.parse(JSON.stringify(this.result.grants));
    }
}

</script>
