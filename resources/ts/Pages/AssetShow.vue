<script setup lang="ts">
    import AppLayout from '@/Layouts/AppLayout.vue'
    import MainMenu from '@/Components/MainMenu.vue'
    import { onMounted, reactive } from 'vue'
    import { api, type AssetDetails, type Favorite } from '@/lib/api'

    const props = defineProps<{ id: string }>()

    const state = reactive({
        loading: true,
        error: '',
        asset: null as AssetDetails | null,
        isFavorite: false,
    })

    async function load() {
        state.loading = true; state.error = ''
        try {
            const [assetRes, favRes] = await Promise.all([
            api.get<AssetDetails>(`/assets/${props.id}`),
            api.get<Favorite[]>('/favorites'),
            ])
            state.asset = assetRes.data
            state.isFavorite = favRes.data.some(f => f.asset_id === props.id)
        } catch (e: any) {
            state.error = e?.response?.data?.message || e?.message || 'Failed to load asset'
        } finally { state.loading = false }
    }

    async function toggleFavorite() {
        try {
            if (state.isFavorite) {
                const favs = (await api.get<Favorite[]>('/favorites')).data
                const match = favs.find(f => f.asset_id === props.id)
                if (match) await api.delete(`/favorites/${match.id}`)
                state.isFavorite = false
            } else {
                await api.post('/favorites', { assetId: props.id })
                state.isFavorite = true
            }
        } catch (e) { alert('Could not update favorite') }
    }

    onMounted(load)
</script>

<style scoped>
    .loading-container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: calc(100vh - 200px);
    width: 100%;
    }

    .loading-text {
    font-size: 1.125rem;
    color: rgb(75, 85, 99);
    font-weight: 500;
    }

    .asset-container {
    max-width: 1100px;
    margin: 0 auto;
    padding: 2rem;
    }

    .asset-header {
    background: white;
    border: 1px solid rgb(229, 231, 235);
    border-radius: 1rem;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    }

    .asset-image {
    width: 56px;
    height: 56px;
    object-fit: cover;
    border-radius: 50%;
    border: 2px solid rgb(229, 231, 235);
    }

    .asset-name {
    font-size: 1.5rem;
    font-weight: 600;
    color: rgb(17, 24, 39);
    }

    .asset-symbol {
    font-size: 0.875rem;
    color: rgb(75, 85, 99);
    }

    .favorite-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    transition: all 0.2s;
    background: transparent;
    border: 1px solid rgb(229, 231, 235);
    padding: 0.5rem 1rem;
    border-radius: 0.75rem;
    cursor: pointer;
    }

    .favorite-button.active {
    color: rgb(250, 204, 21);
    border-color: rgb(250, 204, 21);
    }

    .favorite-button:hover {
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    }

    .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
    }

    .stat-card {
    background: white;
    border: 1px solid rgb(229, 231, 235);
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    }

    .stat-label {
    font-size: 0.875rem;
    color: rgb(75, 85, 99);
    margin-bottom: 0.5rem;
    }

    .stat-value {
    font-family: ui-monospace, monospace;
    font-size: 1.25rem;
    color: rgb(17, 24, 39);
    font-weight: 600;
    }

    .positive {
    color: rgb(16, 185, 129);
    }

    .negative {
    color: rgb(225, 29, 72);
    }

    .homepage-link {
    color: rgb(37, 99, 235);
    text-decoration: none;
    transition: color 0.2s;
    }

    .homepage-link:hover {
    color: rgb(29, 78, 216);
    }

    .description-section {
    background: white;
    border: 1px solid rgb(229, 231, 235);
    border-radius: 1rem;
    padding: 1.5rem;
    margin-top: 2rem;
    box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    }

    .description-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: rgb(17, 24, 39);
    margin-bottom: 1rem;
    }
</style>

<template>
  <AppLayout>
    <MainMenu />
    
    <div v-if="state.loading" class="loading-container">
      <span class="loading-text">Loading...</span>
    </div>
    
    <div v-else-if="state.error" class="p-4 border border-red-200 bg-red-50 rounded-xl text-center text-red-600">
      {{ state.error }}
    </div>

    <section v-else-if="state.asset" class="asset-container">
      <header class="asset-header flex items-center gap-4">
        <img :src="state.asset.image || ''" alt="" class="asset-image"/>
        <div>
          <h1 class="asset-name">{{ state.asset.name }}</h1>
          <p class="asset-symbol">{{ state.asset.symbol }}</p>
          <a v-if="state.asset.homepage" :href="state.asset.homepage" target="_blank" class="homepage-link text-sm mt-1 inline-block">
            Website Oficial ↗
          </a>
        </div>
        <button @click="toggleFavorite()" 
                class="favorite-button ml-auto"
                :class="state.isFavorite ? 'active' : ''">
          {{ state.isFavorite ? '★' : '☆' }}
        </button>
      </header>

      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-label">Price</div>
          <div class="stat-value">
            {{ state.asset.current_price?.toLocaleString('en-US', { style: 'currency', currency: 'USD', maximumFractionDigits: 20 }) ?? '—' }}
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-label">24h</div>
          <div class="stat-value" :class="(state.asset.price_change_percentage_24h ?? 0) >= 0 ? 'positive' : 'negative'">
            {{ state.asset.price_change_percentage_24h?.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) ?? '—' }}%
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-label">Market Cap</div>
          <div class="stat-value">
            {{ state.asset.market_cap?.toLocaleString('en-US', { style:'currency', currency:'USD', maximumFractionDigits: 0 }) ?? '—' }}
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-label">Max / Min (24h)</div>
          <div class="stat-value">
            {{ state.asset.high_24h?.toLocaleString('en-US', { style:'currency', currency:'USD', maximumFractionDigits: 20 }) ?? '—' }} / 
            {{ state.asset.low_24h?.toLocaleString('en-US', { style:'currency', currency:'USD', maximumFractionDigits: 20 }) ?? '—' }}
          </div>
        </div>
      </div>

      <article v-if="state.asset.description" class="description-section">
        <h2 class="description-title">Description</h2>
        <div class="text-gray-600" v-html="state.asset.description"></div>
      </article>
    </section>
  </AppLayout>
</template>