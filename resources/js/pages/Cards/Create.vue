<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import CardForm from '@/components/cards/CardForm.vue';
import { create, index } from '@/routes/cards';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Cards', href: index() },
            { title: 'Add card', href: create() },
        ],
    },
});
</script>

<template>
    <Head title="Add card" />

    <div class="mx-auto w-full max-w-3xl px-4 py-6 md:px-6 lg:py-8">
        <header class="mb-8 border-b pb-6">
            <p
                class="mb-2 text-sm font-medium text-emerald-700 dark:text-emerald-400"
            >
                New record
            </p>
            <h1 class="text-2xl font-semibold tracking-tight">Add gift card</h1>
            <p class="mt-1 text-sm text-muted-foreground">
                Enter the card details exactly as they were issued.
            </p>
        </header>

        <CardForm />
    </div>
</template>
