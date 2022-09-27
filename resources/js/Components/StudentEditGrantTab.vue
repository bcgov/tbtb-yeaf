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
                    <form>
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

                            <div v-if="grant.errors != undefined" class="row">
                                <div class="col-12">
                                    <div v-if="grant.hasErrors == true" class="alert alert-danger mt-3">
                                        <ul>
                                            <li v-for="err in grant.errors">{{ err }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="card-footer mt-3">
                            <button type="submit" class="btn mr-2 btn-outline-success" :disabled="grant.processing">Update Grant</button>
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
        for(let i=0; i<this.result.grants.length; i++)
        {
            this.grantForms.push(useForm(this.result.grants[i]));
        }
    }
}

</script>
