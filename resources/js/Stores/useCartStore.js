import { create } from 'zustand';
import axios from 'axios';
import { router } from '@inertiajs/react';

// Simple debounce function
const debounce = (func, wait) => {
    let timeout;
    return (...args) => {
        clearTimeout(timeout);
        timeout = setTimeout(() => func(...args), wait);
    };
};

const useCartStore = create((set, get) => ({
    cart: { items: [], total: 0, count: 0 },
    isCartOpen: false,

    // Sync store with Initial Props (from Inertia)
    setCart: (cartData) => {
        if (!cartData) return;
        set({
            cart: {
                items: cartData.items || [],
                total: cartData.total || 0,
                count: cartData.count || 0
            }
        });
    },

    openCart: () => set({ isCartOpen: true }),
    closeCart: () => set({ isCartOpen: false }),
    toggleCart: () => set((state) => ({ isCartOpen: !state.isCartOpen })),

    updateQuantity: (itemId, quantity) => {
        const { cart } = get();
        const itemIndex = cart.items.findIndex(i => i.id === itemId);
        if (itemIndex === -1) return;

        const newItems = [...cart.items];
        const item = { ...newItems[itemIndex] };
        
        // Optimistic update
        item.quantity = parseInt(quantity);
        item.total = item.quantity * item.unit_price;
        newItems[itemIndex] = item;

        // Recalculate totals
        const newTotal = newItems.reduce((sum, i) => sum + (i.quantity * i.unit_price), 0);
        const newCount = newItems.reduce((sum, i) => sum + i.quantity, 0);

        set({
            cart: {
                ...cart,
                items: newItems,
                total: newTotal,
                count: newCount
            }
        });

        // Debounced Server Sync
        debouncedUpdate(itemId, quantity);
    },

    removeItem: (itemId) => {
        // Optimistic remove
        const { cart } = get();
        const newItems = cart.items.filter(i => i.id !== itemId);
        
        const newTotal = newItems.reduce((sum, i) => sum + (i.quantity * i.unit_price), 0);
        const newCount = newItems.reduce((sum, i) => sum + i.quantity, 0);

        set({
            cart: {
                ...cart,
                items: newItems,
                total: newTotal,
                count: newCount
            }
        });

        // Server Sync (Immediate for delete)
        router.delete(route('cart.destroy', itemId), {
            preserveScroll: true,
            onSuccess: () => {
                // Props will update, triggering useEffect to sync store
            },
            onError: (errors) => {
                console.error('Failed to remove item', errors);
                // Revert optimistic update if necessary, or let the user try again.
                // For now, we mainly want to avoid the forced reload loop.
            }
        });
    }
}));

// Debounced API call outside the store to persist across renders/calls
const debouncedUpdate = debounce((itemId, quantity) => {
    axios.patch(route('cart.update', itemId), { quantity }).catch(err => {
        console.error('Failed to update cart', err);
    });
}, 500);

export default useCartStore;
