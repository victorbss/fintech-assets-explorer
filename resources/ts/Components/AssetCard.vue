<script setup lang="ts">
    import { Link } from '@inertiajs/vue3'

    interface Props {
        asset: {
            id: string
            name: string
            symbol: string
            image: string
            current_price?: number
            price_change_percentage_24h?: number
        }
        isFavorite: boolean
        onToggleFavorite: (id: string) => void
    }

    defineProps<Props>()
</script>

<style>
    .asset-card {
    width: 100%;
    max-width: 24rem;
    margin: 0 auto;
    padding: 1.5rem;
    border-radius: 1rem;
    border: 1px solid rgb(229, 231, 235);
    background: white;
    box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    transition: all 0.2s;
    }

    .asset-card:hover {
    box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1);
    }

    .asset-header {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
    }

    .asset-image {
    width: 32px;
    height: 32px;
    object-fit: cover;
    border-radius: 50%;
    border: 2px solid rgb(229, 231, 235);
    display: inline-block;
    vertical-align: middle;
    }

    .asset-name {
    margin-right: 5px;
    text-decoration: none;
    }

    .asset-name:hover {
    text-decoration: none;
    }

    .asset-symbol {
    font-size: 0.75rem;
    color: rgb(75, 85, 99);
    }

    .asset-price-container {
    margin-top: 0.75rem;
    font-size: 0.875rem;
    }

    .asset-price {
    font-family: ui-monospace, monospace;
    color: rgb(17, 24, 39);
    font-weight: 600;
    }

    .price-change-positive {
    color: rgb(16, 185, 129);
    font-weight: 500;
    }

    .price-change-negative {
    color: rgb(225, 29, 72);
    font-weight: 500;
    }

    .favorite-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    transition: all 0.2s;
    background: transparent;
    border: none;
    cursor: pointer;
    }

    .favorite-button.active {
    color: rgb(250, 204, 21);
    }

    .favorite-button.inactive {
    color: rgb(156, 163, 175);
    }

    .favorite-button.inactive:hover {
    color: rgb(250, 204, 21);
    }
</style>

<template>
  <div class="asset-card">
    <header class="asset-header">
      <img :src="asset.image || ''" alt="" class="asset-image" />
      <div class="flex-1">
          <div class="flex flex-col">
            <div class="flex items-center">
              <Link :href="`/assets/${asset.id}`" class="group no-underline asset-name">
                <span class="font-medium text-gray-900 group-hover:text-blue-600 transition-colors">{{ asset.name }}</span>
              </Link>
              <button @click="onToggleFavorite(asset.id)" 
                      class="favorite-button"
                      :class="isFavorite ? 'active' : 'inactive'">
                {{ isFavorite ? '★' : '☆' }}
              </button>
            </div>
            <p class="asset-symbol">{{ asset.symbol }}</p>
          </div>
      </div>
    </header>
    <div class="asset-price-container">
      <p class="text-gray-700">Price: <span class="asset-price">{{ asset.current_price?.toLocaleString('en-US', { style:'currency', currency:'USD' }) ?? '—' }}</span></p>
      <p :class="(asset.price_change_percentage_24h ?? 0) >= 0 ? 'price-change-positive' : 'price-change-negative'">
        24h: {{ asset.price_change_percentage_24h?.toFixed(2) ?? '—' }}%
      </p>
    </div>
  </div>
</template>
