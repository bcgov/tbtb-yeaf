<script setup>
import { Head } from '@inertiajs/inertia-vue3';
import {defineComponent} from "vue";

defineProps({
    loginAttempt: Boolean,
    hasAccess: Boolean,
    status: String,
});
defineComponent( {
    Head
});
</script>
<style scoped>

.bg-bc-gov{
    background: none;
    background-color: #036;
    font-family: 'BCSans', 'Noto Sans', Verdana, Arial, sans-serif;
    color: #036;
}
</style>
<template>
    <div class="min-h-screen text-center flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-bc-gov">


            <Head><title>Select Application</title></Head>
        <h2 class="text-center text-white">Welcome {{ $attrs['auth']['user']['first_name'] }}</h2>
        <p class="text-center text-white">Select an application to log into</p>


            <div class="row col-12 m-3 justify-content-center">
                <div class="col-md-3">
                    <a :href="route('students.index')" class="card p-5" style="text-decoration:none;">
                        <h1>YEAF</h1>
                        <h4>Youth Educational Assistance Fund</h4>
                    </a>
                </div>
                <div class="col-md-3">
                    <a :href="route('students.index')" class="card p-5" style="text-decoration:none;">
                        <h1>TWP</h1>
                        <h4>Tuition Waiver Program</h4>
                    </a>
                </div>
            </div>
    </div>
</template>
