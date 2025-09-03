<script setup lang="ts">
    import AppLayout from '@/Layouts/AppLayout.vue'
    import MainMenu from '@/Components/MainMenu.vue'
    import AssetCard from '@/Components/AssetCard.vue'
    import { onMounted, reactive } from 'vue'
    import { api, type Favorite, type AssetListItem } from '@/lib/api'
    import { Link } from '@inertiajs/vue3'

    const state = reactive({
        loading: true,
        error: '',
        favorites: [] as Favorite[],
        assets: new Map<string, AssetListItem>(), // cache by id for quick display
    })

    async function load() {
        state.loading = true; state.error = ''
        try {
            const res = await api.get<Favorite[]>('/favorites')
            state.favorites = res.data

            // Fetch the top list once (cheap) to enrich favorites with price/image quickly
            const top = await api.get<AssetListItem[]>('/assets')
            for (const a of top.data) state.assets.set(a.id, a)
        } catch (e: any) {
            state.error = e?.response?.data?.message || e?.message || 'Failed to load favorites'
        } finally { state.loading = false }
    }

    async function removeFavorite(id: number) {
        try {
            await api.delete(`/favorites/${id}`)
            await load()
        } catch (e) { alert('Could not remove favorite') }
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

    .empty-state {
    text-align: center;
    color: rgb(75, 85, 99);
    padding: 2rem;
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
      <div class="w-full max-w-[1100px] mx-auto px-4">
        
        <div v-if="!state.favorites.length" class="empty-state">
          No favorites.
        </div>
        
        <table style="margin:auto;width:100%;max-width:1100px;border-collapse:separate;border-spacing:0 0;">
          <tbody>
            <tr v-for="row in Math.ceil(state.favorites.length / 3)" :key="row">
              <td v-for="col in 3" :key="col" style="text-align:center;width:33%;vertical-align:top;padding:0;">
                <template v-if="state.favorites[(row-1)*3 + (col-1)]">
                  <AssetCard 
                    :key="state.favorites[(row-1)*3 + (col-1)].id"
                    :asset="state.assets.get(state.favorites[(row-1)*3 + (col-1)].asset_id)!"
                    :is-favorite="true"
                    :on-toggle-favorite="() => removeFavorite(state.favorites[(row-1)*3 + (col-1)].id)"
                  />
                </template>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AppLayout>
</template>