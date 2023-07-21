
<template>
        <Head title="YEAF - Student Edit" />

        <BreezeAuthenticatedLayout>
            <template #header>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    YEAF - Edit Student
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
                                    YEAF Edit Student
                                    <span v-if="!grantTabVisible" class="btn btn-sm rounded-pill text-bg-danger ms-2 disabled">*** STUDENT UNDER INVESTIGATION ***</span>
                                    <span v-if="overawardFlagVisible == true" class="btn btn-sm rounded-pill text-bg-danger ms-2 disabled">Over Award</span>
<!--                                    <a :href="'/reports/download/' + editForm.id" class="btn rounded-pill btn-outline-secondary shadow-none float-end" data-bs-toggle="tooltip" data-bs-title="Download Student Report">-->
<!--                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-down" viewBox="0 0 16 16">-->
<!--                                            <path fill-rule="evenodd" d="M3.5 10a.5.5 0 0 1-.5-.5v-8a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 0 0 1h2A1.5 1.5 0 0 0 14 9.5v-8A1.5 1.5 0 0 0 12.5 0h-9A1.5 1.5 0 0 0 2 1.5v8A1.5 1.5 0 0 0 3.5 11h2a.5.5 0 0 0 0-1h-2z"/>-->
<!--                                            <path fill-rule="evenodd" d="M7.646 15.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 14.293V5.5a.5.5 0 0 0-1 0v8.793l-2.146-2.147a.5.5 0 0 0-.708.708l3 3z"/>-->
<!--                                        </svg>-->
<!--                                    </a>-->
                                </div>
                                <div class="card-body" v-if="result != null">





                                    <ul class="nav nav-tabs mb-3" id="myStudentTab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="student-tab" data-bs-toggle="tab" data-bs-target="#student-tab-pane" type="button" role="tab" aria-controls="student-tab-pane" aria-selected="true">Student</button>
                                        </li>
                                        <li v-if="grantTabVisible" class="nav-item" role="presentation">
                                            <button class="nav-link" id="grant-tab" data-bs-toggle="tab" data-bs-target="#grant-tab-pane" type="button" role="tab" aria-controls="grant-tab-pane" aria-selected="false">Grant</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="comment-tab" data-bs-toggle="tab" data-bs-target="#comment-tab-pane" type="button" role="tab" aria-controls="comment-tab-pane" aria-selected="false">Comment</button>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myStudentTabContent">
                                        <div class="tab-pane fade show active" id="student-tab-pane" role="tabpanel" aria-labelledby="student-tab" tabindex="0">

                                            <StudentEditStudentTab @investigate="updateTabs" @override="updateOverride" :result="result" :countries="countries" :provinces="provinces"></StudentEditStudentTab>

                                        </div>
                                        <div class="tab-pane fade" id="grant-tab-pane" role="tabpanel" aria-labelledby="grant-tab" tabindex="0">...</div>
                                        <div class="tab-pane fade" id="comment-tab-pane" role="tabpanel" aria-labelledby="comment-tab" tabindex="0">...</div>
                                    </div>





                                </div>
                                <h1 v-else class="lead">No results</h1>
<!--                                <div class="card-footer">-->
<!--                                    <Link :href="route('back').back()">Back</Link>-->
<!--                                </div>-->
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
import StudentEditStudentTab from "@/Components/StudentEditStudentTab.vue";

export default {
    name: 'StudentEdit',
    components: {
        StudentEditStudentTab,
        BreezeAuthenticatedLayout, StudentSearchBox, Head, Link
    },
    props: {
        result: Object,
        now: String,
        countries: Object,
        provinces: Object,
    },
    data() {
        return {
            grantTabVisible: true,
            overawardFlagVisible: false,
            editForm: null,
        }
    },
    methods: {
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
        }

    },
    watch: {
    },
    computed: {
    },
    mounted() {
        this.editForm = this.result;
        this.syncVisible();

        // if(this.editForm != null){
        //
        // }else{
        //     this.editForm = this.result;
        //     this.editForm.processing = false;
        // }
        //enable tooltips
        // setTimeout(function (){
        //     const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        //     const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        //
        // }, 3000);
    }
}
</script>
