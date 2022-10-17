<script>

export default {
    name: 'FormSubmitAlert',
    components: {
    },
    props: {
        formState: Boolean|String,
        successMsg: String,
        failMsg: String,
    },
    data() {
        return {
            showHide: false,
        }
    },
    methods: {
    },
    watch: {
        formState: function (newVal, oldVal) {
            let vm = this;
            if (newVal != null) {
                vm.showHide = true;
                setTimeout(function () {
                    vm.showHide = false;
                }, 5000);
            }
        }
    },

}
</script>

<template>
    <div v-if="showHide">
        <div v-if="formState" class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div id="updateSuccessAlert" class="alert alert-success alert-dismissible fade show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="100">
                <div class="">
                    <div class="toast-body">{{ successMsg }}</div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
        <div v-if="formState === false" class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div id="updateFailAlert" class="alert alert-danger alert-dismissible fade show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="100">
                <div class="">
                    <div class="toast-body">{{ failMsg }}</div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>

</template>
