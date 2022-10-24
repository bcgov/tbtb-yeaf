<template>

    <div class="list-group">
        <Link :href="route('maintenance.ministry.show')" class="list-group-item list-group-item-action" :class="page === 'ministry' ? 'active' : ''">Ministry</Link>
        <Link :href="route('maintenance.staff.list')" class="list-group-item list-group-item-action" :class="page === 'staff' ? 'active' : ''">Staff</Link>
        <Link :href="route('maintenance.reports.index')" class="list-group-item list-group-item-action" :class="page === 'reports' ? 'active' : ''">Reports</Link>
    </div>
</template>
<script setup>
import { Link } from '@inertiajs/inertia-vue3';

defineProps({
    page: String,
});

</script>
