<template>

    <div class="list-group">
        <Link :href="route('maintenance.staff.list')" class="list-group-item list-group-item-action" :class="page.split('staff').length > 1 ? 'active' : ''">Staff</Link>
    </div>
</template>
<script setup>
import { Link } from '@inertiajs/inertia-vue3';

defineProps({
    page: String,
});

</script>
