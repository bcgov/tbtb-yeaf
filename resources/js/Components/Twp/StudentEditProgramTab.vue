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
                <BreezeLabel for="inputLastName" class="form-label" value="Last Name" />
                <BreezeInput type="text" class="form-control" id="inputLastName" v-model="editForm.last_name" />
            </div>
            <div class="col-md-4">
                <BreezeLabel for="inputFirstName" class="form-label" value="First Name" />
                <BreezeInput type="text" class="form-control" id="inputFirstName" v-model="editForm.first_name" />
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
        </div>

        <FormSubmitAlert :form-state="editForm.formState"
                         :success-msg="'Student record was updated successfully.'"></FormSubmitAlert>

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
        now: String,
        countries: Object,
        provinces: Object,
    },
    data() {
        return {
            noChanges: true,
            editForm: null,
        }
    },
    methods: {
        updateStudent: function ()
        {
            this.editForm = useForm({

                id: this.editForm.id,
                last_name: this.editForm.last_name,
                first_name: this.editForm.first_name,
                birth_date: this.editForm.birth_date,
                email: this.editForm.email,
                gender: this.editForm.gender,
                pen: this.editForm.pen,
                pd: this.editForm.pd,
                institution_student_number: this.editForm.institution_student_number,
            });

            this.editForm.formState = '';
            this.editForm.put(route('twp.students.update', this.result.id), {
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

            });
        },

        back: function()
        {
            window.history.back();
        },
    },
    mounted() {
        this.editForm = this.result;
    }
}

</script>
