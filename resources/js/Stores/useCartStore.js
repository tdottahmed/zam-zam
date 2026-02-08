import { create } from 'zustand';
import axios from 'axios';

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
        axios.delete(route('cart.destroy', itemId)).catch(err => {
            console.error('Failed to remove item', err);
            // Ideally revert state here on error, but keeping it simple for now
            // Reloading page would fix state if needed
            window.location.reload(); 
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
