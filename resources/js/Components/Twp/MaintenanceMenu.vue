<template>

    <div class="list-group">
        <Link :href="route('twp.maintenance.staff.list')" class="list-group-item list-group-item-action" :class="page.split('staff').length > 1 ? 'active' : ''">Staff</Link>
        <Link :href="route('twp.maintenance.application-reasons.list')" class="list-group-item list-group-item-action" :class="page.split('application-reasons').length > 1 ? 'active' : ''">Denial Reasons</Link>
    </div>
</template>
<script setup>
import { Link } from '@inertiajs/inertia-vue3';

defineProps({
    page: String,
});

</script>
