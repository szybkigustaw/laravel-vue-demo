<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import CardForm from '@/components/cards/CardForm.vue';
import { index } from '@/routes/cards';
import type { EditableCard } from '@/types/cards';

const { card } = defineProps<{
    card: EditableCard;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Cards', href: index() },
            { title: 'Edit card', href: index() },
        ],
    },
});
</script>

<template>
    <Head title="Edit card" />

    <div class="mx-auto w-full max-w-3xl px-4 py-6 md:px-6 lg:py-8">
        <header class="mb-8 border-b pb-6">
            <p
                class="mb-2 text-sm font-medium text-emerald-700 dark:text-emerald-400"
            >
                Card maintenance
            </p>
            <h1 class="text-2xl font-semibold tracking-tight">
                Edit gift card
            </h1>
            <p class="mt-1 font-mono text-sm text-muted-foreground">
                {{ card.card_number }}
            </p>
        </header>

        <CardForm :card="card" />
    </div>
</template>
