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
            <div class="col-md-5">
                <BreezeLabel for="inputInstitutionName" class="form-label" value="Institution Name" />
                <BreezeInput type="text" class="form-control" id="inputInstitutionName" v-model="editForm.institution_name" />
            </div>
            <div class="col-md-5">
                <BreezeLabel for="inputCredential" class="form-label" value="Credential" />
                <BreezeInput type="text" class="form-control" id="inputCredential" v-model="editForm.credential" />
            </div>
            <div class="col-md-2">
                <BreezeLabel for="inputStudentStatus" class="form-label" value="Student Status" />
                <BreezeSelect class="form-select" id="inputStudentStatus" v-model="editForm.student_status">
                    <option value="Attending">Attending</option>
                    <option value="Completed">Completed</option>
                    <option value="Hiatus">Hiatus</option>
                    <option value="Never Attended">Never Attended</option>
                </BreezeSelect>
            </div>

            <div class="col-md-3">
                <BreezeLabel for="inputStudyStartDate" class="form-label" value="Study Start Date" />
                <BreezeInput type="date" placeholder="YYYY-MM-DD" class="form-control" id="inputStudyStartDate" v-model="editForm.study_period_start_date" />
            </div>
            <div class="col-md-3">
                <BreezeLabel for="inputProgramLength" class="form-label" value="Program Length" />
                <BreezeInput type="number" class="form-control" id="inputCredential" v-model="editForm.program_length" />
            </div>
            <div class="col-md-3">
                <BreezeLabel for="inputLengthType" class="form-label" value="Program Length Type" />
                <BreezeSelect class="form-select" id="inputLengthType" v-model="editForm.program_length_type">
                    <option value="day">Day</option>
                    <option value="week">Week</option>
                    <option value="month">Month</option>
                    <option value="year">Year</option>
                </BreezeSelect>
            </div>
            <div class="col-md-3">
                <BreezeLabel for="inputEstimatedCost" class="form-label" value="Estimated Cost" />
                <div class="input-group">
                    <div class="input-group-text">$</div>
                    <input type="text" class="form-control" id="inputEstimatedCost" v-model="editForm.total_estimated_cost">
                </div>
            </div>

            <div class="col-md-12">
                <BreezeLabel for="inputProgramComments" class="form-label" value="Comments" />
                <BreezeInput type="text" class="form-control" id="inputProgramComments" v-model="editForm.comments" />
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
            <button v-if="result==null" type="submit" class="btn mr-2 btn-outline-success" :disabled="editForm.processing">Create Program</button>
            <button v-else type="submit" class="btn mr-2 btn-outline-success" :disabled="editForm.processing">Update Program</button>
            <Link @click="back" class="btn btn-outline-primary float-right" href="#">Back</Link>
        </div>

        <FormSubmitAlert :form-state="editForm.formState"
                         :success-msg="'Program record was submitted successfully.'"></FormSubmitAlert>

    </form>

</template>
<script>
import {Link, useForm} from '@inertiajs/inertia-vue3';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeSelect from "@/Components/Select";
import FormSubmitAlert from "@/Components/FormSubmitAlert";

export default {
    name: 'StudentEditProgramTab',
    components: {
        BreezeInput, BreezeLabel, Link, BreezeSelect, FormSubmitAlert
    },
    props: {
        result: Object,
        twpStudentId: String|Number|null,
    },
    data() {
        return {
            noChanges: true,
            editForm: useForm({
                formState: true,
                formSuccessMsg: 'Form was submitted successfully.',
                formFailMsg: 'There was an error submitting this form.',
                id: null,
                twp_student_id: this.twpStudentId,
                institution_name: '',
                study_period_start_date: '',
                credential: '',
                program_length: '',
                program_length_type: '',
                total_estimated_cost: '',
                student_status: '',
                comments: '',
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
                this.editForm.post(route('twp.programs.store'), options);
            }else{
                this.editForm.id = this.result.id;
                this.editForm = useForm(this.editForm);
                this.editForm.put(route('twp.programs.update', this.result.id), options);
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
