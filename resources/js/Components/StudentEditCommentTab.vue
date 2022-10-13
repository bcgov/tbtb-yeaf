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

<div>
    <p v-if="result.comments.length === 0" class="text-center leading-5">No comments.</p>
    <ul v-else class="list-group list-group-flush">
        <li class="list-group-item">
            <div class="row">
                <div class="col-md-2"><strong>Date</strong></div>
                <div class="col-md-2"><strong>User</strong></div>
                <div class="col-md-8"><strong>Comment</strong></div>
            </div>
        </li>
        <li v-for="(comment, j) in result.comments" class="list-group-item">
            <div class="row">
                <div class="col-md-2">{{ comment.created_human_date }}</div>
                <div class="col-md-2">{{ comment.user_id }}</div>
                <div class="col-md-8">{{ comment.comment_text }}</div>
            </div>
        </li>
    </ul>

</div>



</template>
<script>
import axios from 'axios';
import {Link, useForm} from '@inertiajs/inertia-vue3';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeSelect from "@/Components/Select";

export default {
    name: 'StudentEditCommentTab',
    components: {
        BreezeInput, BreezeLabel, Link, BreezeSelect
    },
    props: {
        result: Object,

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

        exportGrant: function (index)
        {
            let vm = this;
            axios({
                url: route('grants.validate-letter', this.grantForms[index].id),
                method: 'get',
                headers: {'Accept': 'application/json'}
            })
                .then(function (response) {
                    if(response.data.msg === ''){
                        window.location.href = '/grants/export-letter/' + vm.grantForms[index].id + '/' + response.data.docName;
                    }else{
                        vm.grantForms[index].msg = response.data.msg;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        overaward: function (index)
        {
            if(this.grantForms[index].overaward > this.grantForms[index].total_yeaf_award){
                alert("The Overaward amount cannot be larger than the amount awarded");
                this.grantForms[index].overaward = this.result.grants[index].overaward;
            }

            this.grantForms[index].total_yeaf_award -= this.grantForms[index].overaward;
            this.grantForms[index].overaward_deducted_amount = this.grantForms[index].overaward;
        },
        unlock: function (index)
        {
            let check = confirm('Are you sure you want to unlock this grant? This will reset the YEAF Award fied to zero.');
            if(check){
                this.grantForms[index].total_yeaf_award = 0;
            }
        },
        giveAward: function (index)
        {
            for(let i = 0; i<this.program_years.length; i++){
                if(this.program_years[i].program_year_id == this.grantForms[index].program_year_id){
                    this.grantForms[index].total_yeaf_award = this.program_years[i].grant_amount;
                    break;
                }
            }
        },
        checkLocked: function (index)
        {
            return ((this.result.grants[index].total_yeaf_award > 0 && this.result.grants[index].cheque_batch_number != null) && this.grantForms[index].total_yeaf_award > 0);
        },
        //type is P|D
        newReason: function (index, type)
        {
            let new_record = type === 'P' ? this.grantForms[index].new_pending_reasons : this.grantForms[index].new_denial_reasons;
            //if we have already added one row. make sure you don't add another if there is a blank entry/row
            if(new_record !== undefined){
                let found_blank = false;
                for(let i=0; i<new_record.length; i++){
                    if(new_record[i].ineligible_code_id === 0){
                        found_blank = true;
                        break;
                    }
                }

                if(found_blank){
                    return false;
                }
                //if we have not added any new rows/entries. Initialize a new array
            }else{
                type === 'P' ? this.grantForms[index].new_pending_reasons = [] : this.grantForms[index].new_denial_reasons = [];
            }
            type === 'P'
                ? this.grantForms[index].new_pending_reasons.push({
                ineligible_code_id: 0, ineligible_code_type: type, cleared_flag: false
            })
                : this.grantForms[index].new_denial_reasons.push({
                ineligible_code_id: 0, ineligible_code_type: type, cleared_flag: false
            });

        },
        newAppeal: function (index)
        {
            let new_record = this.grantForms[index].new_appeals;
            //if we have already added one row. make sure you don't add another if there is a blank entry/row
            if(new_record !== undefined){
                let found_blank = false;
                for(let i=0; i<new_record.length; i++){
                    if(new_record[i].appeal_code === 0){
                        found_blank = true;
                        break;
                    }
                }

                if(found_blank){
                    return false;
                }
                //if we have not added any new rows/entries. Initialize a new array
            }else{
                this.grantForms[index].new_appeals = [];
            }

            this.grantForms[index].new_appeals.push({
                appeal_code: 0, adjudicated_by_user_id: '', appeal_date: '', appeal_status: 'A', status_affective_date: '', other_appeal_explain: ''
            });

        },
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

            if(this.result.grants[index].total_yeaf_award > 0 && this.result.grants[index].status_code === 'A')
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
        updateGrant: function (index)
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
                if(this.result.grants[index].status_code === 'A' && this.result.grants[index].total_yeaf_award > 0){
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
                            if(response.data.msg !== ''){
                                vm.grantForms[index].msg = response.data.msg;
                            }else{
                                vm.showSuccessAlert();
                                setTimeout(function (){
                                    window.location.href = '/students/' + vm.result.id;
                                }, 3000);
                            }
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
