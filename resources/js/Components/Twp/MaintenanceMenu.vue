<template>

    <div class="list-group">
        <Link :href="route('twp.maintenance.staff.list')" class="list-group-item list-group-item-action" :class="page.split('staff').length > 1 ? 'active' : ''">Staff</Link>
        <Link :href="route('twp.maintenance.institutions.list')" class="list-group-item list-group-item-action" :class="page.split('institutions').length > 1 ? 'active' : ''">Institutions</Link>
        <Link :href="route('twp.maintenance.reasons.list')" class="list-group-item list-group-item-action" :class="page.split('reasons').length > 1 ? 'active' : ''">Denial Reasons</Link>
        <Link :href="route('twp.maintenance.payments.list')" class="list-group-item list-group-item-action" :class="page.split('payments').length > 1 ? 'active' : ''">Payment Types</Link>
        <Link :href="route('twp.maintenance.reports.show')" class="list-group-item list-group-item-action" :class="page.split('reports').length > 1 ? 'active' : ''">Reports</Link>
    </div>
</template>
<script setup>
import { Link } from '@inertiajs/inertia-vue3';

defineProps({
    page: String,
});

</script>
