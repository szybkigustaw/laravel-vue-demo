<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowRight, CheckCircle2, ShieldCheck } from '@lucide/vue';
import { login, register } from '@/routes';
import { index } from '@/routes/cards';
</script>

<template>
    <Head title="Gift card management" />

    <div
        class="min-h-svh bg-[#f4f8f5] text-[#10271d] dark:bg-[#0c1610] dark:text-[#f0f7f1]"
    >
        <main class="relative isolate min-h-svh overflow-hidden">
            <img
                src="/images/boncard-wallet.png"
                alt=""
                aria-hidden="true"
                class="pointer-events-none absolute top-1/2 right-[-6rem] z-[-1] hidden w-[min(72vw,50rem)] -translate-y-1/2 opacity-15 sm:right-[-2rem] sm:block sm:opacity-20 lg:right-[3vw] lg:w-[min(54vw,46rem)]"
            />

            <header
                class="mx-auto flex w-full max-w-7xl items-center justify-between px-5 py-5 sm:px-8 lg:px-10"
            >
                <Link
                    :href="$page.props.auth.user ? index() : '/'"
                    class="flex items-center gap-3"
                >
                    <img
                        src="/images/boncard-wallet.png"
                        alt="BonCard"
                        width="48"
                        height="48"
                        class="size-10 object-contain"
                    />
                    <span class="text-lg font-semibold tracking-tight"
                        >BonCard</span
                    >
                </Link>

                <nav
                    class="flex items-center gap-2 sm:gap-3"
                    aria-label="Main navigation"
                >
                    <Link
                        v-if="$page.props.auth.user"
                        :href="index()"
                        class="inline-flex h-10 items-center justify-center gap-2 rounded-md bg-[#123d2b] px-4 text-sm font-medium text-white transition-colors hover:bg-[#0d2f20] focus-visible:ring-[3px] focus-visible:ring-[#1f7a55]/40 focus-visible:outline-none"
                    >
                        Open cards
                        <ArrowRight class="size-4" aria-hidden="true" />
                    </Link>
                    <template v-else>
                        <Link
                            :href="login()"
                            class="inline-flex h-10 items-center justify-center rounded-md px-4 text-sm font-medium transition-colors hover:bg-[#dfece3] focus-visible:ring-[3px] focus-visible:ring-[#1f7a55]/40 focus-visible:outline-none dark:hover:bg-white/10"
                        >
                            Log in
                        </Link>
                        <Link
                            :href="register()"
                            class="inline-flex h-10 items-center justify-center rounded-md bg-[#123d2b] px-4 text-sm font-medium text-white transition-colors hover:bg-[#0d2f20] focus-visible:ring-[3px] focus-visible:ring-[#1f7a55]/40 focus-visible:outline-none"
                        >
                            Create account
                        </Link>
                    </template>
                </nav>
            </header>

            <section
                class="mx-auto flex min-h-[calc(100svh-5.5rem)] w-full max-w-7xl items-center px-5 pb-20 sm:px-8 lg:px-10"
            >
                <div class="max-w-2xl py-14 sm:py-20">
                    <p
                        class="mb-5 flex items-center gap-2 text-sm font-semibold text-[#1f7a55] dark:text-[#6fcca2]"
                    >
                        <ShieldCheck class="size-5" aria-hidden="true" />
                        Gift card operations, simplified
                    </p>
                    <h1
                        class="max-w-xl text-4xl font-semibold tracking-tight text-balance sm:text-5xl lg:text-6xl"
                    >
                        Convenience &amp; security, always within reach.
                    </h1>
                    <p
                        class="mt-6 max-w-lg text-base leading-7 text-[#456054] sm:text-lg dark:text-[#c3d6c8]"
                    >
                        BonCard gives your team one dependable place to issue,
                        maintain, and review every gift card you manage.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="index()"
                            class="inline-flex h-11 items-center justify-center gap-2 rounded-md bg-[#123d2b] px-5 text-sm font-medium text-white transition-colors hover:bg-[#0d2f20] focus-visible:ring-[3px] focus-visible:ring-[#1f7a55]/40 focus-visible:outline-none"
                        >
                            Go to card register
                            <ArrowRight class="size-4" aria-hidden="true" />
                        </Link>
                        <template v-else>
                            <Link
                                :href="register()"
                                class="inline-flex h-11 items-center justify-center gap-2 rounded-md bg-[#123d2b] px-5 text-sm font-medium text-white transition-colors hover:bg-[#0d2f20] focus-visible:ring-[3px] focus-visible:ring-[#1f7a55]/40 focus-visible:outline-none"
                            >
                                Start managing cards
                                <ArrowRight class="size-4" aria-hidden="true" />
                            </Link>
                            <Link
                                :href="login()"
                                class="inline-flex h-11 items-center justify-center rounded-md border border-[#b7cdbd] bg-white/70 px-5 text-sm font-medium transition-colors hover:bg-white focus-visible:ring-[3px] focus-visible:ring-[#1f7a55]/40 focus-visible:outline-none dark:border-white/20 dark:bg-white/5 dark:hover:bg-white/10"
                            >
                                Log in to your account
                            </Link>
                        </template>
                    </div>

                    <ul
                        class="mt-12 flex flex-wrap gap-x-6 gap-y-3 text-sm font-medium text-[#365444] dark:text-[#b8d1be]"
                    >
                        <li class="flex items-center gap-2">
                            <CheckCircle2
                                class="size-4 text-[#1f7a55]"
                                aria-hidden="true"
                            />
                            Private card registers
                        </li>
                        <li class="flex items-center gap-2">
                            <CheckCircle2
                                class="size-4 text-[#1f7a55]"
                                aria-hidden="true"
                            />
                            Encrypted PIN storage
                        </li>
                    </ul>
                </div>
            </section>
        </main>
    </div>
</template>
