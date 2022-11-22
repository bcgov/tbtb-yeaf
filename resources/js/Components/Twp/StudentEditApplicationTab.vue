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
            <div class="col-md-4">
                <BreezeLabel for="inputReceivedDate" class="form-label" value="Received Date" />
                <BreezeInput type="date" placeholder="YYYY-MM-DD" class="form-control" id="inputReceivedDate" v-model="editForm.received_date" />
            </div>
            <div class="col-md-4">
                <BreezeLabel for="inputApplicationStatus" class="form-label" value="Application Status" />
                <BreezeSelect class="form-select" id="inputApplicationStatus" v-model="editForm.application_status">
                    <option value="APPROVED">Approved</option>
                    <option value="DENIED">Denied</option>
                    <option value="IN PROGRESS">In Progress</option>
                    <option value="APPROVED ON APPEAL">Approved on Appeal</option>
                    <option value="WITHDRAWN">Withdrawn</option>
                </BreezeSelect>
            </div>
            <div class="col-md-4">
                <BreezeLabel for="inputTwpStatus" class="form-label" value="TWP Status" />
                <BreezeSelect class="form-select" id="inputTwpStatus" v-model="editForm.twp_status">
                    <option value="Inactive">Inactive</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Credential Completed">Credential Completed</option>
                </BreezeSelect>
            </div>

            <div class="col-md-12">
                <BreezeLabel for="inputDenialReason" class="form-label" value="Denial Reason" />
                <BreezeInput type="text" class="form-control" id="inputDenialReason" v-model="editForm.denial_reason" />
            </div>
            <div class="col-md-12">
                <BreezeLabel for="inputExceptionComments" class="form-label" value="Exception Comments" />
                <BreezeInput type="text" class="form-control" id="inputExceptionComments" v-model="editForm.exception_comments" />
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
            <button v-if="result==null" type="submit" class="btn mr-2 btn-outline-success" :disabled="editForm.processing">Create Application</button>
            <button v-else type="submit" class="btn mr-2 btn-outline-success" :disabled="editForm.processing">Update Application</button>
            <Link @click="back" class="btn btn-outline-primary float-right" href="#">Back</Link>
        </div>

        <FormSubmitAlert :form-state="editForm.formState"
                         :success-msg="'Application record was submitted successfully.'"></FormSubmitAlert>

    </form>

</template>
<script>
import {Link, useForm} from '@inertiajs/inertia-vue3';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeSelect from "@/Components/Select";
import FormSubmitAlert from "@/Components/FormSubmitAlert";

export default {
    name: 'StudentEditApplicationTab',
    components: {
        BreezeInput, BreezeLabel, Link, BreezeSelect, FormSubmitAlert
    },
    props: {
        result: Object,
        twp_student_id: String|Number,
    },
    data() {
        return {
            noChanges: true,
            editForm: useForm({
                formState: true,
                formSuccessMsg: 'Form was submitted successfully.',
                formFailMsg: 'There was an error submitting this form.',
                id: null,
                twp_student_id: this.twp_student_id,
                received_date: '',
                application_status: '',
                twp_status: '',
                denial_reason: '',
                exception_comments: '',
            }),
        }
    },
    methods: {
        updateStudent: function ()
        {
            let options = {
                onSuccess: () => {
                    this.editForm.formState = true;
                    this.noChanges = true;
                },
                onFailure: () => {
                },
                onError: () => {
                    this.editForm.formState = false;
                },
                preserveState: true,

            };
            this.editForm.formState = '';

            if(this.result == null){
                this.editForm.post(route('twp.applications.store'), options);
            }else{
                this.editForm.id = this.result.id;
                this.editForm.put(route('twp.applications.update', this.result.id), options);
            }

        },

        back: function()
        {
            window.history.back();
        },
    },
    mounted() {
        if(this.result != null){
            this.editForm = this.result;
        }
    }
}

</script>
