<script setup lang="ts">
    import { Link, Head } from '@inertiajs/vue3'
</script>


<template>
    <div class="min-h-screen bg-gray-50 text-gray-900">
        <Head>
            <title>Fintech Assets Explorer</title>
            <meta name="description" content="Explore and manage your fintech assets" />
        </Head>
        <div style="background:#ffffff;min-height:100vh;width:100vw;position:fixed;top:0;left:0;z-index:-1;"></div>
        <header class="sticky top-0 z-20 bg-white/90 backdrop-blur border-b border-gray-200">
        </header>
        <main class="max-w-6xl mx-auto px-4 py-6">
            <slot />
        </main>
    </div>
</template>