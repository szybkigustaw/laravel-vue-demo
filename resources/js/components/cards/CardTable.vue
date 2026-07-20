<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Pencil, Trash2 } from '@lucide/vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import { edit } from '@/routes/cards';
import type { CardListItem } from '@/types/cards';

defineProps<{
    cards: CardListItem[];
}>();

const emit = defineEmits<{
    delete: [card: CardListItem];
}>();

const dateTimeFormatter = new Intl.DateTimeFormat('en-GB', {
    dateStyle: 'medium',
    timeStyle: 'short',
    timeZone: 'UTC',
});

const dateFormatter = new Intl.DateTimeFormat('en-GB', {
    dateStyle: 'medium',
    timeZone: 'UTC',
});

const balanceFormatter = new Intl.NumberFormat('en-GB', {
    style: 'currency',
    currency: 'PLN',
});

function formatActivationDate(value: string): string {
    return dateTimeFormatter.format(new Date(value));
}

function formatExpirationDate(value: string): string {
    return dateFormatter.format(new Date(`${value}T00:00:00Z`));
}

function formatBalance(value: string): string {
    return balanceFormatter.format(Number(value));
}
</script>

<template>
    <div class="overflow-x-auto">
        <table class="w-full min-w-[760px] border-collapse text-left">
            <thead>
                <tr
                    class="border-b bg-muted/40 text-xs font-semibold tracking-wide text-muted-foreground uppercase"
                >
                    <th class="px-5 py-3 sm:px-6">Card number</th>
                    <th class="px-5 py-3">PIN</th>
                    <th class="px-5 py-3">Activated</th>
                    <th class="px-5 py-3">Expires</th>
                    <th class="px-5 py-3 text-right">Balance</th>
                    <th class="w-28 px-5 py-3 text-right sm:px-6">
                        <span class="sr-only">Actions</span>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <tr
                    v-for="card in cards"
                    :key="card.id"
                    class="group transition-colors hover:bg-muted/30"
                >
                    <td
                        class="px-5 py-4 font-mono text-sm font-medium tracking-wide sm:px-6"
                    >
                        {{ card.card_number }}
                    </td>
                    <td class="px-5 py-4">
                        <Badge
                            variant="outline"
                            class="font-mono tracking-[0.2em]"
                        >
                            {{ card.masked_pin }}
                        </Badge>
                    </td>
                    <td class="px-5 py-4 text-sm text-muted-foreground">
                        {{ formatActivationDate(card.activation_date) }}
                    </td>
                    <td class="px-5 py-4 text-sm text-muted-foreground">
                        {{ formatExpirationDate(card.expiration_date) }}
                    </td>
                    <td
                        class="px-5 py-4 text-right font-mono text-sm font-semibold tabular-nums"
                    >
                        {{ formatBalance(card.balance) }}
                    </td>
                    <td class="px-5 py-4 sm:px-6">
                        <div class="flex justify-end gap-1">
                            <TooltipProvider :delay-duration="200">
                                <Tooltip>
                                    <TooltipTrigger as-child>
                                        <Button
                                            variant="ghost"
                                            size="icon-sm"
                                            as-child
                                            aria-label="Edit card"
                                        >
                                            <Link :href="edit(card.id)">
                                                <Pencil aria-hidden="true" />
                                                <span class="sr-only"
                                                    >Edit card</span
                                                >
                                            </Link>
                                        </Button>
                                    </TooltipTrigger>
                                    <TooltipContent>Edit card</TooltipContent>
                                </Tooltip>
                            </TooltipProvider>
                            <TooltipProvider :delay-duration="200">
                                <Tooltip>
                                    <TooltipTrigger as-child>
                                        <Button
                                            variant="ghost"
                                            size="icon-sm"
                                            class="text-destructive hover:text-destructive"
                                            aria-label="Delete card"
                                            @click="emit('delete', card)"
                                        >
                                            <Trash2 aria-hidden="true" />
                                            <span class="sr-only"
                                                >Delete card</span
                                            >
                                        </Button>
                                    </TooltipTrigger>
                                    <TooltipContent>Delete card</TooltipContent>
                                </Tooltip>
                            </TooltipProvider>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
