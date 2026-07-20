<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { CreditCard, Plus, Trash2 } from '@lucide/vue';
import { shallowRef } from 'vue';
import CardPagination from '@/components/cards/CardPagination.vue';
import CardTable from '@/components/cards/CardTable.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { create, destroy, index } from '@/routes/cards';
import type { CardListItem, PaginatedCards } from '@/types/cards';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Cards',
                href: index(),
            },
        ],
    },
});

defineProps<{
    cards: PaginatedCards;
}>();

const cardPendingDeletion = shallowRef<CardListItem | null>(null);

function confirmDelete(card: CardListItem): void {
    cardPendingDeletion.value = card;
}

function closeDeleteDialog(open: boolean): void {
    if (!open) {
        cardPendingDeletion.value = null;
    }
}

function deleteCard(): void {
    const card = cardPendingDeletion.value;

    if (!card) {
        return;
    }

    router.delete(destroy(card.id).url, {
        preserveScroll: true,
        onSuccess: () => {
            cardPendingDeletion.value = null;
        },
    });
}
</script>

<template>
    <Head title="Cards" />

    <div
        class="mx-auto flex w-full max-w-7xl flex-1 flex-col gap-6 px-4 py-6 md:px-6 lg:py-8"
    >
        <header
            class="flex flex-col gap-4 border-b pb-6 sm:flex-row sm:items-end sm:justify-between"
        >
            <div class="space-y-2">
                <div
                    class="flex items-center gap-2 text-sm font-medium text-emerald-700 dark:text-emerald-400"
                >
                    <span
                        class="size-2 rounded-full bg-emerald-500"
                        aria-hidden="true"
                    ></span>
                    Card register
                </div>
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight">
                        Gift cards
                    </h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Issue, review, and maintain your active card inventory.
                    </p>
                </div>
            </div>
            <Button as-child>
                <Link :href="create()">
                    <Plus aria-hidden="true" />
                    Add card
                </Link>
            </Button>
        </header>

        <section
            v-if="cards.data.length"
            class="overflow-hidden rounded-lg border bg-card shadow-sm"
        >
            <div
                class="flex items-center justify-between gap-4 border-b bg-muted/20 px-5 py-3 sm:px-6"
            >
                <p class="text-sm font-medium">Your cards</p>
                <p class="text-sm text-muted-foreground">
                    {{ cards.total }} {{ cards.total === 1 ? 'card' : 'cards' }}
                </p>
            </div>
            <CardTable :cards="cards.data" @delete="confirmDelete" />
            <CardPagination
                :current-page="cards.current_page"
                :last-page="cards.last_page"
                :previous-url="cards.prev_page_url"
                :next-url="cards.next_page_url"
            />
        </section>

        <section
            v-else
            class="grid min-h-80 place-items-center border border-dashed bg-muted/20 p-8 text-center"
        >
            <div class="max-w-sm space-y-4">
                <div
                    class="mx-auto grid size-12 place-items-center rounded-lg border bg-background text-emerald-700 shadow-sm dark:text-emerald-400"
                >
                    <CreditCard class="size-6" aria-hidden="true" />
                </div>
                <div class="space-y-1">
                    <h2 class="font-semibold">No cards yet</h2>
                    <p class="text-sm text-muted-foreground">
                        Add your first gift card to start managing its
                        lifecycle.
                    </p>
                </div>
                <Button as-child>
                    <Link :href="create()">
                        <Plus aria-hidden="true" />
                        Add card
                    </Link>
                </Button>
            </div>
        </section>
    </div>

    <Dialog
        :open="cardPendingDeletion !== null"
        @update:open="closeDeleteDialog"
    >
        <DialogContent>
            <DialogHeader>
                <DialogTitle>Delete this card?</DialogTitle>
                <DialogDescription>
                    Card {{ cardPendingDeletion?.card_number }} will be
                    permanently removed from your register.
                </DialogDescription>
            </DialogHeader>
            <DialogFooter class="gap-2 sm:justify-end">
                <Button variant="outline" @click="cardPendingDeletion = null">
                    Cancel
                </Button>
                <Button variant="destructive" @click="deleteCard">
                    <Trash2 aria-hidden="true" />
                    Delete card
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
