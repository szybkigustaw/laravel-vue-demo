<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Save } from '@lucide/vue';
import { computed, nextTick, useTemplateRef } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { index, store, update } from '@/routes/cards';
import type { CardFormData, EditableCard } from '@/types/cards';

const props = defineProps<{
    card?: EditableCard;
}>();

const isEditing = computed(() => props.card !== undefined);
const formRoot = useTemplateRef<HTMLFormElement>('cardForm');

const form = useForm<CardFormData>({
    card_number: props.card?.card_number ?? '',
    pin: props.card?.pin ?? '',
    activation_date: props.card?.activation_date ?? '',
    expiration_date: props.card?.expiration_date ?? '',
    balance: props.card?.balance ?? '',
});

function submit(): void {
    if (props.card) {
        form.put(update(props.card.id).url, { onError: focusFirstError });

        return;
    }

    form.post(store.url(), { onError: focusFirstError });
}

function focusFirstError(): void {
    void nextTick(() => {
        formRoot.value
            ?.querySelector<HTMLElement>('[aria-invalid="true"]')
            ?.focus();
    });
}
</script>

<template>
    <form ref="cardForm" class="space-y-8" @submit.prevent="submit">
        <div class="grid gap-x-6 gap-y-5 md:grid-cols-2">
            <div class="grid gap-2 md:col-span-2">
                <div class="flex items-baseline justify-between gap-4">
                    <Label for="card_number">Card number</Label>
                    <span class="text-xs text-muted-foreground">20 digits</span>
                </div>
                <Input
                    id="card_number"
                    v-model="form.card_number"
                    name="card_number"
                    inputmode="numeric"
                    autocomplete="off"
                    maxlength="20"
                    pattern="[0-9]{20}"
                    placeholder="00000000000000000000…"
                    :aria-invalid="Boolean(form.errors.card_number)"
                    required
                    class="font-mono tracking-wide"
                />
                <InputError :message="form.errors.card_number" />
            </div>

            <div class="grid gap-2">
                <div class="flex items-baseline justify-between gap-4">
                    <Label for="pin">PIN</Label>
                    <span class="text-xs text-muted-foreground">4 digits</span>
                </div>
                <Input
                    id="pin"
                    v-model="form.pin"
                    name="pin"
                    type="password"
                    inputmode="numeric"
                    autocomplete="off"
                    maxlength="4"
                    pattern="[0-9]{4}"
                    placeholder="0000…"
                    :aria-invalid="Boolean(form.errors.pin)"
                    required
                    class="font-mono tracking-[0.25em]"
                />
                <InputError :message="form.errors.pin" />
            </div>

            <div class="grid gap-2">
                <Label for="balance">Balance</Label>
                <div class="relative">
                    <Input
                        id="balance"
                        v-model="form.balance"
                        name="balance"
                        type="number"
                        min="0"
                        step="0.01"
                        inputmode="decimal"
                        placeholder="0.00…"
                        :aria-invalid="Boolean(form.errors.balance)"
                        required
                        class="pr-14 font-mono tabular-nums"
                    />
                    <span
                        class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-sm text-muted-foreground"
                    >
                        PLN
                    </span>
                </div>
                <InputError :message="form.errors.balance" />
            </div>

            <div class="grid gap-2">
                <Label for="activation_date">Activation date</Label>
                <Input
                    id="activation_date"
                    v-model="form.activation_date"
                    name="activation_date"
                    type="datetime-local"
                    :aria-invalid="Boolean(form.errors.activation_date)"
                    required
                />
                <InputError :message="form.errors.activation_date" />
            </div>

            <div class="grid gap-2">
                <Label for="expiration_date">Expiration date</Label>
                <Input
                    id="expiration_date"
                    v-model="form.expiration_date"
                    name="expiration_date"
                    type="date"
                    :aria-invalid="Boolean(form.errors.expiration_date)"
                    required
                />
                <InputError :message="form.errors.expiration_date" />
            </div>
        </div>

        <div
            class="flex flex-col-reverse gap-3 border-t pt-5 sm:flex-row sm:justify-between"
        >
            <Button variant="ghost" as-child>
                <Link :href="index()">
                    <ArrowLeft aria-hidden="true" />
                    Back to cards
                </Link>
            </Button>
            <Button type="submit" :disabled="form.processing">
                <Spinner v-if="form.processing" />
                <Save v-else aria-hidden="true" />
                {{ isEditing ? 'Save changes' : 'Create card' }}
            </Button>
        </div>
    </form>
</template>
