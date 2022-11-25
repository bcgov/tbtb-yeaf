
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
                                        <li @click="switchActiveTab('application')" class="nav-item" role="presentation">
                                            <button class="nav-link" :class="activeTab==='application' ? 'active':''" id="application-tab" data-bs-toggle="tab" data-bs-target="#application-tab-pane" type="button" role="tab" aria-controls="application-tab-pane" aria-selected="false">Application</button>
                                        </li>
                                        <li @click="switchActiveTab('program')" class="nav-item" role="presentation">
                                            <button class="nav-link" :class="activeTab==='program' ? 'active':''" id="program-tab" data-bs-toggle="tab" data-bs-target="#program-tab-pane" type="button" role="tab" aria-controls="program-tab-pane" aria-selected="false">Program</button>
                                        </li>
                                        <li v-if="result.program != null" @click="switchActiveTab('payments')" class="nav-item" role="presentation">
                                            <button class="nav-link" :class="activeTab==='payments' ? 'active':''" id="payments-tab" data-bs-toggle="tab" data-bs-target="#payments-tab-pane" type="button" role="tab" aria-controls="payments-tab-pane" aria-selected="false">Payments</button>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myStudentTabContent">
                                        <div class="tab-pane fade" :class="activeTab==='student' ? 'active show':''" id="student-tab-pane" role="tabpanel" aria-labelledby="student-tab" tabindex="0">
                                            <StudentEditStudentTab v-if="activeTab==='student'" :result="result"></StudentEditStudentTab>
                                        </div>
                                        <div class="tab-pane fade" :class="activeTab==='application' ? 'active show':''" id="application-tab-pane" role="tabpanel" aria-labelledby="application-tab" tabindex="2">
                                            <StudentEditApplicationTab v-if="activeTab==='application'" :reasons="reasons" :twpStudentId="result.id" :result="result.application"></StudentEditApplicationTab>
                                        </div>
                                        <div class="tab-pane fade" :class="activeTab==='program' ? 'active show':''" id="program-tab-pane" role="tabpanel" aria-labelledby="program-tab" tabindex="1">
                                            <StudentEditProgramTab v-if="activeTab==='program'" :twpStudentId="result.id" :result="result.program"></StudentEditProgramTab>
                                        </div>
                                        <div v-if="result.program != null" class="tab-pane fade" :class="activeTab==='payments' ? 'active show':''" id="payments-tab-pane" role="tabpanel" aria-labelledby="payments-tab" tabindex="3">
                                            <StudentEditPaymentTab v-if="activeTab==='payments'" :twpStudentId="result.id" :result="result.payments" :program="result.program"></StudentEditPaymentTab>
                                        </div>
                                    </div>

                                </div>
                                <h1 v-else class="lead">No results</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="modal modal-lg fade" id="newPaymentModal" tabindex="-1" aria-labelledby="newPaymentModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="newPaymentModalLabel">Add New Payment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form @submit.prevent="newPayment">
                            <div class="modal-body">
                                <div class="card-body">
                                    <div class="row g-3">

                                        <div class="col-md-6">
                                            <BreezeLabel for="newPaymentDate" class="form-label" value="Payment Amount" />
                                            <BreezeInput type="date" class="form-control" id="newPaymentDate" v-model="newPaymentForm.payment_date" />
                                        </div>
                                        <div class="col-md-6">
                                            <BreezeLabel for="newPaymentAmount" class="form-label" value="Payment Amount" />
                                            <div class="input-group">
                                                <div class="input-group-text">$</div>
                                                <input type="text" class="form-control" id="newPaymentAmount" v-model="newPaymentForm.payment_amount">
                                            </div>
                                        </div>

                                    </div>

                                    <div v-if="newPaymentForm.errors != undefined" class="row">
                                        <div class="col-12">
                                            <div v-if="newPaymentForm.hasErrors == true" class="alert alert-danger mt-3">
                                                <ul>
                                                    <li v-for="err in newPaymentForm.errors"><small>{{ err }}</small></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn mr-2 btn-outline-success" :disabled="newPaymentForm.processing">Submit</button>
                            </div>
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
        reasons: Object,
        provinces: Object,

        schools: Object,
        batches: Object,
        ineligibles: Object,
        active_staff: Object,
        all_staff: Object,
    },
    data() {
        return {
            editForm: null,
            activeTab: 'student',
            newPaymentForm: useForm({
                twp_student_id: '',
                twp_program_id: null,
                payment_date: '',
                payment_amount: '',
            }),
        }
    },
    methods: {



        switchActiveTab: function (tab)
        {
            this.activeTab = tab;
        },

        newPayment: function ()
        {
            this.newPaymentForm.formState = '';
            this.newPaymentForm.post(route('twp.payments.store'), {
                onSuccess: () => {
                    $("#newPaymentModal").modal('hide');
                    this.newPaymentForm.reset();
                    this.activeTab = 'payments';
                    this.newPaymentForm.formState = true;
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
    watch: {
        result: {
            handler(newValue, oldValue) {
                if(this.result.program != null){
                    this.newPaymentForm.twp_program_id = this.result.program.id;
                }
            },
            deep: true
        }

    },
    mounted() {
        this.editForm = this.result;
        this.newPaymentForm.twp_student_id = this.result.id;
        if(this.result.program != null){
            this.newPaymentForm.twp_program_id = this.result.program.id;
        }
    }
}
</script>
