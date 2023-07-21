<template>

    <div class="list-group">
        <Link :href="route('maintenance.ministry.show')" class="list-group-item list-group-item-action" :class="page === 'ministry' ? 'active' : ''">Ministry</Link>
        <Link :href="route('maintenance.staff.list')" class="list-group-item list-group-item-action" :class="page.split('staff').length > 1 ? 'active' : ''">Staff</Link>
        <Link :href="route('maintenance.ineligibles.list')" class="list-group-item list-group-item-action" :class="page.split('ineligibles').length > 1 ? 'active' : ''">Ineligible Reasons</Link>
        <Link :href="route('maintenance.program-years.list')" class="list-group-item list-group-item-action" :class="page.split('program-years').length > 1 ? 'active' : ''">Program Years</Link>
        <Link :href="route('maintenance.batches.list')" class="list-group-item list-group-item-action" :class="page.split('batches').length > 1 ? 'active' : ''">Batches</Link>
        <Link :href="route('maintenance.reports.show')" class="list-group-item list-group-item-action" :class="page === 'reports' ? 'active' : ''">Reports</Link>
        <Link :href="route('maintenance.letters.index')" class="list-group-item list-group-item-action" :class="page === 'letters' ? 'active' : ''">Cover Letters</Link>
    </div>
</template>
<script setup>
import { Link } from '@inertiajs/inertia-vue3';

defineProps({
    page: String,
});

</script>
