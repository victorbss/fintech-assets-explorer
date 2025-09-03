<script setup lang="ts">
    import AppLayout from '@/Layouts/AppLayout.vue'
    import MainMenu from '@/Components/MainMenu.vue'
    import { onMounted, reactive, computed } from 'vue'
    import { api, type AssetListItem, type Favorite } from '@/lib/api'
    import AssetCard from '@/Components/AssetCard.vue'

    const state = reactive({
        loading: true,
        error: '',
        assets: [] as AssetListItem[],
        favorites: [] as Favorite[],
    })

    const favoriteIds = computed(() => new Set(state.favorites.map(f => f.asset_id)))

    async function load() {
        state.loading = true; state.error = ''
        try {
            const [assetsRes, favRes] = await Promise.all([
                api.get<AssetListItem[]>('/assets'),
                api.get<Favorite[]>('/favorites'),
            ])
            console.log('Assets received:', assetsRes.data)
            state.assets = assetsRes.data
            state.favorites = favRes.data
        } catch (e: any) {
            state.error = e?.response?.data?.message || e?.message || 'Failed to load'
        } finally {
            state.loading = false
        }
    }

    async function toggleFavorite(assetId: string) {
      try {
        if (favoriteIds.value.has(assetId)) {
          // find fav id to delete
          const fav = state.favorites.find(f => f.asset_id === assetId)
          if (fav) await api.delete(`/favorites/${fav.id}`)
        } else {
          await api.post('/favorites', { assetId })
        }
        // refresh favorites only
        const favRes = await api.get<Favorite[]>('/favorites')
        state.favorites = favRes.data
      } catch (e) {
        alert('Could not update favorites')
      }
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

  .page-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: rgb(17, 24, 39);
    margin-bottom: 2rem;
    text-align: center;
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
    <div v-else class="w-full flex justify-center items-center" style="min-height:60vh;">
      <table style="margin:auto;width:100%;max-width:1100px;border-collapse:separate;border-spacing:0 1.5rem;">
        <tbody>
          <tr v-for="row in Math.ceil(state.assets.length / 3)" :key="row">
            <td v-for="col in 3" :key="col" style="text-align:center;width:33%;vertical-align:top;">
              <template v-if="state.assets[(row-1)*3 + (col-1)]">
                <AssetCard 
                  :key="state.assets[(row-1)*3 + (col-1)].id"
                  :asset="state.assets[(row-1)*3 + (col-1)]"
                  :is-favorite="favoriteIds.has(state.assets[(row-1)*3 + (col-1)].id)"
                  :on-toggle-favorite="toggleFavorite"
                />
              </template>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AppLayout>
</template>