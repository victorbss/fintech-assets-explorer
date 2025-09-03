import axios from 'axios';

export const api = axios.create({
    baseURL: '/api',
    timeout: 15000,
});

export type AssetListItem = {
    id: string;
    symbol: string;
    name: string;
    image: string | null;
    current_price: number | null;
    price_change_percentage_24h: number | null;
};

export type AssetDetails = AssetListItem & {
    market_cap: number | null;
    high_24h: number | null;
    low_24h: number | null;
    homepage: string | null;
    description: string | null;
};

export type Favorite = { id: number; asset_id: string; created_at: string };