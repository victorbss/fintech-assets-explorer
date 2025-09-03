
<template>
  <nav class="w-full flex flex-col items-center justify-center mb-8">
    <div class="menu-bar">
      <Link href="/" class="menu-btn" :class="{ 'active': currentRoute === '/' }">
        Home
      </Link>
      <Link href="/favorites" class="menu-btn" :class="{ 'active': currentRoute === '/favorites' }">
        Favorites
      </Link>
    </div>
  </nav>
</template>

<style scoped>
    .menu-bar {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    gap: 1rem;
    width: 100%;
    max-width: 600px;
    margin: 0 auto;
    padding: 1rem 0;
    }

    .menu-btn {
    display: inline-block;
    min-width: 120px;
    padding: 0.75rem 1.5rem;
    margin: 0.25rem 0;
    font-size: 0.875rem;
    font-weight: 500;
    color: rgb(75, 85, 99);
    background: white;
    border: 1px solid rgb(229, 231, 235);
    border-radius: 1rem;
    box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    text-align: center;
    text-decoration: none;
    transition: all 0.2s;
    }

    .menu-btn:hover {
    color: rgb(37, 99, 235);
    background: white;
    border-color: rgb(229, 231, 235);
    box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1);
    }

    .menu-btn.active {
    color: rgb(37, 99, 235);
    background: rgb(239, 246, 255);
    border-color: rgb(37, 99, 235);
    font-weight: 600;
    }

    @media (max-width: 600px) {
    .menu-btn {
        min-width: 100px;
        padding: 0.625rem 1rem;
    }
    }
</style>

<script setup lang="ts">
    import { Link, usePage } from '@inertiajs/vue3'

    const currentRoute = usePage().url
</script>