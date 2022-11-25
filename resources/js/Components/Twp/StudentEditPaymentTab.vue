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
    <p v-if="paymentForms.length === 0" class="text-center leading-5">No Payments.</p>
    <div v-else>
        <div class="row mb-3 text-center">
            <div class="col-md-6">
                <div class="card p-5">
                    <h1 class="display-6 font-sans font-light">TOTAL PAYMENT</h1>
                    <span>${{totalPayment}}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-5">
                    <h1 class="display-6 font-sans font-light">VARIANCE</h1>
                    <span v-if="variance < 0" class="text-danger">${{variance}}</span>
                    <span v-else class="text-success">${{variance}}</span>
                </div>
            </div>
        </div>
        <div class="accordion" id="accordionGrant">
            <div v-for="(payment,i) in paymentForms" class="accordion-item">
                <h2 class="accordion-header" :id="'heading'+i">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" :data-bs-target="'#collapse'+i" :aria-expanded="i===0" :aria-controls="'collapse'+i">
                        Payment #{{ i+1 }}
                    </button>
                </h2>
                <div :id="'collapse'+i" class="accordion-collapse collapse" :class="i===0?'show':''" :aria-labelledby="'heading'+i" data-bs-parent="#accordionGrant">
                    <div class="accordion-body">
                        <form @submit.prevent="updatePayment(i)">
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <BreezeLabel :for="'inputPaymentDate'+i" class="form-label" value="Payment Date" />
                                    <BreezeInput type="date" class="form-control" :id="'inputPaymentDate'+i" v-model="payment.payment_date" />
                                </div>
                                <div class="col-md-6">
                                    <BreezeLabel :for="'inputPaymentAmount'+i" class="form-label" value="Payment Amount" />
                                    <div class="input-group">
                                        <div class="input-group-text">$</div>
                                        <input type="text" class="form-control" id="'inputPaymentAmount'+i" v-model="payment.payment_amount">
                                    </div>
                                </div>

                            </div>

                            <div v-if="payment.msg != undefined" class="row">
                                <div class="col-12">
                                    <div class="alert alert-danger mt-3">
                                        <ul>
                                            <li v-if="typeof (payment.msg) === 'string'" v-html="msg"></li>
                                            <template v-else>
                                                <li v-for="msg in payment.msg" v-html="msg"></li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer mt-3">
                                <button type="submit" class="btn mr-2 btn-outline-success">Save Payment</button>
                            </div>

                            <FormSubmitAlert :form-state="payment.formState"
                                             :success-msg="'Payment record was updated successfully.'"></FormSubmitAlert>
                        </form>

                    </div>
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
import FormSubmitAlert from "@/Components/FormSubmitAlert";

export default {
    name: 'StudentEditPaymentTab',
    components: {
        BreezeInput, BreezeLabel, Link, BreezeSelect, FormSubmitAlert
    },
    props: {
        result: Object,
        twpStudentId: String|null,
        program: Object,
    },
    data() {
        return {
            noChanges: true,
            paymentForms: [],
        }
    },
    methods: {


        updatePayment: function (index)
        {
            this.paymentForms[index].formState = '';
            this.paymentForms[index].put(route('twp.payments.update', this.paymentForms[index].id), {
                onSuccess: () => {
                    this.paymentForms[index].formState = true;
                    this.noChanges = true;
                },
                onFailure: () => {
                },
                onError: () => {
                    this.paymentForms[index].formState = false;
                },
                preserveState: false,

            });
        },
        back: function()
        {
            window.history.back();
        },


    },
    mounted() {
        // this.paymentForms = this.result;
        for(let i=0; i<this.result.length; i++){
            this.paymentForms.push(useForm(this.result[i]));
        }
    },
    computed: {
        totalPayment: function ()
        {
            let total = 0;
            for(let i=0; i<this.result.length; i++){
                total += parseFloat(this.result[i].payment_amount);
            }
            return total.toFixed(2);
        },
        variance: function ()
        {
            return (parseFloat(this.program.total_estimated_cost) - this.totalPayment).toFixed(2);
        }
    }
}

</script>
