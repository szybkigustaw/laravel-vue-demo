<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronLeft, ChevronRight } from '@lucide/vue';
import { Button } from '@/components/ui/button';

defineProps<{
    currentPage: number;
    lastPage: number;
    previousUrl: string | null;
    nextUrl: string | null;
}>();
</script>

<template>
    <nav
        v-if="lastPage > 1"
        class="flex items-center justify-between gap-4 border-t px-5 py-3 sm:px-6"
        aria-label="Card list pagination"
    >
        <p class="text-sm text-muted-foreground">
            Page {{ currentPage }} of {{ lastPage }}
        </p>

        <div class="flex items-center gap-2">
            <Button v-if="previousUrl" variant="outline" size="sm" as-child>
                <Link :href="previousUrl">
                    <ChevronLeft aria-hidden="true" />
                    Previous
                </Link>
            </Button>
            <Button v-else variant="outline" size="sm" disabled>
                <ChevronLeft aria-hidden="true" />
                Previous
            </Button>

            <Button v-if="nextUrl" variant="outline" size="sm" as-child>
                <Link :href="nextUrl">
                    Next
                    <ChevronRight aria-hidden="true" />
                </Link>
            </Button>
            <Button v-else variant="outline" size="sm" disabled>
                Next
                <ChevronRight aria-hidden="true" />
            </Button>
        </div>
    </nav>
</template>
