<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

import { base, login, register } from '@/routes';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>
    <div class="flex min-h-screen flex-col items-center lg:justify-center lg:p-8">
        <nav class="flex items-center justify-end gap-4">
            <Link v-if="$page.props.auth.user" :href="base()" class="inline-block rounded-sm border px-5 py-1.5 text-sm leading-normal"> Dashboard </Link>
            <template v-else>
                <div class="border rounded-xl flex flex-col h-110 items-center p-10 space-y-20"> 
                    <h2 class="text-5xl">Gerenciador de Prompts</h2>
                    <div class="flex flex-col space-y-15">
                        <Link :href="login()" class="inline-block rounded-sm border px-5 py-1.5 leading-normal text-2xl text-center"> Log in </Link>
                        <Link v-if="canRegister" :href="register()" class="inline-block rounded-sm border px-5 py-1.5 leading-normal text-2xl text-center"> Register </Link>
                    </div>
                </div>
            </template>
        </nav>
    </div>
</template>
