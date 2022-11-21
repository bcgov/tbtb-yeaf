
<template>
        <Head title="TWP - Student Edit" />

        <BreezeAuthenticatedLayout v-bind="$attrs">
            <template #header>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    TWP - Edit Student
                </h2>
            </template>

            <div class="mt-3">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="card-header">
                                    TWP Student Search
                                </div>
                                <div class="card-body">
                                    <StudentSearchBox />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 mt-3 mb-5">
                            <div class="card">
                                <div v-if="result != null" class="card-header">
                                    Edit Student
                                    <button v-if="activeTab==='payments'" type="button" class="btn btn-success btn-sm float-end" data-bs-toggle="modal" data-bs-target="#newPaymentModal">New Payment</button>
                                </div>
                                <div class="card-body" v-if="result != null">

                                    <ul class="nav nav-tabs mb-3" id="myStudentTab" role="tablist">
                                        <li @click="switchActiveTab('student')" class="nav-item" role="presentation">
                                            <button class="nav-link" :class="activeTab==='student' ? 'active':''" id="student-tab" data-bs-toggle="tab" data-bs-target="#student-tab-pane" type="button" role="tab" aria-controls="student-tab-pane" aria-selected="true">Student</button>
                                        </li>
                                        <li @click="switchActiveTab('application')" v-if="grantTabVisible" class="nav-item" role="presentation">
                                            <button class="nav-link" :class="activeTab==='application' ? 'active':''" id="application-tab" data-bs-toggle="tab" data-bs-target="#application-tab-pane" type="button" role="tab" aria-controls="application-tab-pane" aria-selected="false">Application</button>
                                        </li>
                                        <li @click="switchActiveTab('program')" class="nav-item" role="presentation">
                                            <button class="nav-link" :class="activeTab==='program' ? 'active':''" id="program-tab" data-bs-toggle="tab" data-bs-target="#program-tab-pane" type="button" role="tab" aria-controls="program-tab-pane" aria-selected="false">Program</button>
                                        </li>
                                        <li @click="switchActiveTab('payments')" class="nav-item" role="presentation">
                                            <button class="nav-link" :class="activeTab==='payments' ? 'active':''" id="payments-tab" data-bs-toggle="tab" data-bs-target="#payments-tab-pane" type="button" role="tab" aria-controls="payments-tab-pane" aria-selected="false">Payments</button>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myStudentTabContent">
                                        <div class="tab-pane fade" :class="activeTab==='student' ? 'active show':''" id="student-tab-pane" role="tabpanel" aria-labelledby="student-tab" tabindex="0">
                                            <StudentEditStudentTab v-if="activeTab==='student'" :result="result"></StudentEditStudentTab>
                                        </div>
                                        <div class="tab-pane fade" :class="activeTab==='program' ? 'active show':''" id="program-tab-pane" role="tabpanel" aria-labelledby="program-tab" tabindex="1">
                                            <StudentEditProgramTab v-if="activeTab==='program'" :result="result.program"></StudentEditProgramTab>
                                        </div>
                                        <div class="tab-pane fade" :class="activeTab==='application' ? 'active show':''" id="application-tab-pane" role="tabpanel" aria-labelledby="application-tab" tabindex="2">
                                            <StudentEditApplicationTab v-if="activeTab==='application'" :result="result.application"></StudentEditApplicationTab>
                                        </div>
                                        <div class="tab-pane fade" :class="activeTab==='payments' ? 'active show':''" id="payments-tab-pane" role="tabpanel" aria-labelledby="payments-tab" tabindex="3">
                                            <StudentEditPaymentTab v-if="activeTab==='payments'" :result="result.payments"></StudentEditPaymentTab>
                                        </div>
                                    </div>

                                </div>
                                <h1 v-else class="lead">No results</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="modal modal-lg fade" id="newGrantModal" tabindex="-1" aria-labelledby="newGrantModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="newGrantModalLabel">Add New Grant</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form @submit.prevent="newGrant">
                            <div class="modal-body">
                                <div class="card-body">
                                    <div class="row g-3">

                                        <div class="col-md-4">
                                            <BreezeLabel for="newInstitution" class="form-label" value="Institution *" />
<!--                                            <BreezeSelect class="form-select" id="newInstitution" v-model="newGrantForm.institution_id">-->
<!--                                                <option v-for="(school,j) in schools" :value="school.institution_id">{{ school.name }}</option>-->
<!--                                            </BreezeSelect>-->

                                            <BreezeInput @focusout="resetFilter" @keyup="filterActiveSchools($event)" type="text" class="form-control" id="newInstitution" v-model="newGrantForm.school.name" />
                                            <input type="hidden" v-model="newGrantForm.institution_id" />
                                            <ul class="dropdown-menu" :class="newGrantForm.schoolsListHidden === false ? 'show' : 'hidden'" data-popper-placement="top-start">
                                                <template v-for="(school,j) in schoolsList"><li @click="assignSchool(school)" :value="school.institution_id" class="dropdown-item">{{ school.name }}</li></template>
                                            </ul>
                                        </div>
                                        <div class="col-md-4">
                                            <BreezeLabel for="newProgramName" class="form-label" value="Program" />
                                            <BreezeInput type="text" class="form-control" id="newProgramName" v-model="newGrantForm.program_name" />
                                        </div>
                                        <div class="col-md-4">
                                            <BreezeLabel for="newDateReceived" class="form-label" value="Date Received *" />
                                            <BreezeInput type="date" class="form-control" id="newDateReceived" v-model="newGrantForm.application_receive_date" />
                                        </div>

                                        <div :class="newGrantForm.program_code !== 'I' ? 'col-md-4' : 'col-md-3'">
                                            <BreezeLabel for="newProgramType" class="form-label" value="Program Type *" />
                                            <BreezeSelect class="form-select" id="newProgramType" v-model="newGrantForm.program_code">
                                                <option v-for="(pt,j) in program_types" :value="pt.program_code">{{ pt.program_description }}</option>
                                            </BreezeSelect>
                                        </div>
                                        <div v-if="newGrantForm.program_code === 'I'" class="col-md-3">
                                            <BreezeLabel for="newProgramOtherDescription" class="form-label" value="Program Other Desc" />
                                            <BreezeInput type="text" class="form-control" id="newProgramOtherDescription" v-model="newGrantForm.program_other_description" />
                                        </div>
                                        <div :class="newGrantForm.program_code !== 'I' ? 'col-md-4' : 'col-md-3'">
                                            <BreezeLabel for="newProgramYear" class="form-label" value="Program Year *" />
                                            <BreezeSelect class="form-select" id="newProgramYear" v-model="newGrantForm.program_year_id">
                                                <option value=""></option>
                                                <option v-for="(py,j) in program_years" :value="py.program_year_id">{{ py.year_start }}/{{ py.year_end }}</option>
                                            </BreezeSelect>
                                        </div>
                                        <div :class="newGrantForm.program_code !== 'I' ? 'col-md-4' : 'col-md-3'">
                                            <BreezeLabel for="newProgramOfficer" class="form-label" value="Program Officer" />
                                            <BreezeSelect class="form-select" id="newProgramOfficer" v-model="newGrantForm.officer_user_id">
                                                <option v-for="(officer,j) in all_staff" :value="officer.user_id">{{ officer.first_name }} {{ officer.last_name }}</option>
                                            </BreezeSelect>
                                        </div>
                                        <div class="col-md-4">
                                            <BreezeLabel for="newStartDate" class="form-label" value="Study Start Date *" />
                                            <BreezeInput type="date" class="form-control" id="newStartDate" v-model="newGrantForm.study_start_date" />
                                        </div>
                                        <div class="col-md-4">
                                            <BreezeLabel for="newEndDate" class="form-label" value="Study End Date *" />
                                            <BreezeInput type="date" class="form-control" id="newEndDate" v-model="newGrantForm.study_end_date" />
                                        </div>
                                        <div class="col-md-4">
                                            <BreezeLabel for="newAge" class="form-label" value="Age" />
                                            <BreezeInput type="text" class="form-control" id="newAge" v-model="newGrantForm.age" />
                                        </div>
                                        <div class="col-md-4">
                                            <BreezeLabel for="newApplicationNumber" class="form-label" value="Application #" />
                                            <BreezeInput type="text" class="form-control" id="newApplicationNumber" v-model="newGrantForm.application_number" />
                                        </div>
                                        <div class="col-md-4">
                                            <BreezeLabel for="newApplicationType" class="form-label" value="Application Type" />
                                            <BreezeSelect class="form-select" id="newApplicationType" v-model="newGrantForm.application_type">
                                                <option value=""></option>
                                                <option value="SFAS Extract">SFAS Extract</option>
                                                <option value="Paper Application">Paper Application</option>
                                            </BreezeSelect>
                                        </div>
                                    </div>

                                    <div v-if="newGrantForm.errors != undefined" class="row">
                                        <div class="col-12">
                                            <div v-if="newGrantForm.hasErrors == true" class="alert alert-danger mt-3">
                                                <ul>
                                                    <li v-for="err in newGrantForm.errors"><small>{{ err }}</small></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn mr-2 btn-outline-success" :disabled="newGrantForm.processing">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal modal-lg fade" id="newCommentModal" tabindex="-1" aria-labelledby="newCommentModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="newCommentModalLabel">Add New Comment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form @submit.prevent="newComment">
                            <div class="modal-body">
                                <div class="card-body">
                                    <div class="row g-3">

                                        <div class="col-12">
                                            <BreezeLabel for="newComment" class="form-label" value="Comment" />
                                            <textarea class="form-control" id="newComment" v-model="newCommentForm.comment_text" />
                                        </div>

                                    </div>

                                    <div v-if="newCommentForm.errors != undefined" class="row">
                                        <div class="col-12">
                                            <div v-if="newCommentForm.hasErrors == true" class="alert alert-danger mt-3">
                                                <ul>
                                                    <li v-for="err in newCommentForm.errors"><small>{{ err }}</small></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn mr-2 btn-outline-success" :disabled="newCommentForm.processing">Submit</button>
                            </div>

                            <FormSubmitAlert :form-state="newCommentForm.formState || newGrantForm.formState"
                                             :success-msg="'Form was submitted successfully.'"
                                             :fail-msg="'There was an error submitting this form.'"></FormSubmitAlert>

                        </form>
                    </div>
                </div>
            </div>


        </BreezeAuthenticatedLayout>

</template>
<script>

import BreezeAuthenticatedLayout from '@/Layouts/Twp/Authenticated.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import StudentSearchBox from '@/Components/Twp/StudentSearch.vue';
import StudentEditStudentTab from "@/Components/Twp/StudentEditStudentTab.vue";
import StudentEditProgramTab from "@/Components/Twp/StudentEditProgramTab.vue";
import StudentEditApplicationTab from "@/Components/Twp/StudentEditApplicationTab.vue";
import StudentEditPaymentTab from "@/Components/Twp/StudentEditPaymentTab.vue";
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeSelect from "@/Components/Select";
import FormSubmitAlert from "@/Components/FormSubmitAlert";

export default {
    name: 'StudentEdit',
    components: {
        StudentEditStudentTab,
        StudentEditApplicationTab,
        StudentEditProgramTab,
        StudentEditPaymentTab,
        BreezeAuthenticatedLayout, StudentSearchBox, Head, BreezeInput, BreezeLabel, Link, BreezeSelect, useForm, FormSubmitAlert
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
            schoolsList: [],
            grantTabVisible: true,
            overawardFlagVisible: false,
            editForm: null,
            activeTab: 'student',
            newGrantForm: useForm({
                student_id: '',
                institution_id: '',
                program_name: '',
                application_receive_date: '',
                program_code: '',
                program_other_description: '',
                program_year_id: '',
                officer_user_id: '',
                study_start_date: '',
                study_end_date: '',
                age: '',
                application_number: '',
                application_type: '',
                school: {name:''},
                schoolsListHidden: true,
            }),
            newCommentForm: useForm({comment_text: '', student_id: ''}),
        }
    },
    methods: {
        resetFilter: function (){
            //if schoolsListHidden is true then the schools list is still shown
            //and the input field lost focus because the user clicked something
            //other than the list ot assignSchool
            let vm = this;
            setTimeout(function (){
                if(vm.newGrantForm.schoolsListHidden === false){
                    vm.newGrantForm.school = {name:''};
                    vm.newGrantForm.schoolsListHidden = true;
                    vm.schoolsList = vm.schools;
                }
            }, 300);
        },
        assignSchool: function (school) {
            this.newGrantForm.institution_id = school.institution_id;
            this.newGrantForm.school = school;
            this.newGrantForm.schoolsListHidden = true;
            this.schoolsList = this.schools;
        },
        filterActiveSchools: function (e) {
            this.newGrantForm.schoolsListHidden = false;
            let search = e.target.value.toLowerCase();
            if(search.length > 2){
                this.schoolsList = this.schools.filter(obj => {
                    if(obj.name == null)
                        return false;
                    return obj.name.toLowerCase().indexOf(search) >= 0;
                } );
            }
        },
        switchActiveTab: function (tab)
        {
            this.activeTab = tab;
        },
        updateTabs: function(){
            this.grantTabVisible = !this.grantTabVisible;
        },
        syncVisible: function(){
            if(this.editForm != null && this.editForm.investigate === true){
                this.grantTabVisible = false;
            }

            //hide overawrd flag label if override is set. Only show it if override is false and amount and deducted are not the same
            if(this.editForm.overaward_flag == true){
                this.overawardFlagVisible = false;
            }else if(this.editForm.overaward_amount != this.editForm.overaward_deducted_amount){
                this.overawardFlagVisible = true;
            }
        },
        updateOverride: function(){
            this.editForm.overaward_flag = !this.editForm.overaward_flag;
            this.syncVisible();
        },
        newGrant: function ()
        {
            this.newGrantForm.formState = '';
            this.newGrantForm.post(route('grants.store'), {
                onSuccess: () => {
                    $("#newGrantModal").modal('hide');
                    this.newGrantForm.reset();
                    this.newGrantForm.student_id = this.result.student_id;
                    this.activeTab = 'student';
                    this.newGrantForm.formState = true;
                },
                onFailure: () => {
                },
                onError: () => {
                    this.newGrantForm.formState = false;
                },
                preserveState: true
            });
        },
        newComment: function ()
        {
            this.newCommentForm.formState = '';
            this.newCommentForm.post(route('comments.store'), {
                onSuccess: () => {
                    $("#newCommentModal").modal('hide');
                    this.newCommentForm.reset();
                    this.newCommentForm.student_id = this.result.student_id;
                    this.activeTab = 'student';
                    this.newCommentForm.formState = true;
                },
                onFailure: () => {
                },
                onError: () => {
                    this.newCommentForm.formState = false;
                },
                preserveState: true
            });
        },
    },
    mounted() {
        this.editForm = this.result;
        this.syncVisible();
        this.newGrantForm.student_id = this.result.student_id;
        this.newCommentForm.student_id = this.result.student_id;
        this.schoolsList = this.schools;
    }
}
</script>
