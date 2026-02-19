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
                summary: cartData.summary || { subtotal: 0, tax: 0, total: 0, tax_rate: 0 },
                count: cartData.items ? cartData.items.reduce((sum, i) => sum + i.quantity, 0) : 0
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
        const newSubtotal = newItems.reduce((sum, i) => sum + (i.quantity * i.unit_price), 0);
        const taxRate = cart.summary?.tax_rate || 0;
        const newTax = newSubtotal * (taxRate / 100);
        // Assuming shipping is handled by backend or is free/flat. Logic: Free > 100 else 15
        const newShipping = newSubtotal > 100 ? 0 : 15.00;
        const newTotal = newSubtotal + newTax + newShipping;
        
        const newCount = newItems.reduce((sum, i) => sum + i.quantity, 0);

        set({
            cart: {
                ...cart,
                items: newItems,
                summary: {
                    ...cart.summary,
                    subtotal: newSubtotal,
                    tax: newTax,
                    shipping: newShipping,
                    total: newTotal
                },
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
        
        const newSubtotal = newItems.reduce((sum, i) => sum + (i.quantity * i.unit_price), 0);
        const taxRate = cart.summary?.tax_rate || 0;
        const newTax = newSubtotal * (taxRate / 100);
        const newShipping = newSubtotal > 100 ? 0 : 15.00;
        const newTotal = newSubtotal + newTax + newShipping;
        
        const newCount = newItems.reduce((sum, i) => sum + i.quantity, 0);

        set({
            cart: {
                ...cart,
                items: newItems,
                summary: {
                    ...cart.summary,
                    subtotal: newSubtotal,
                    tax: newTax,
                    shipping: newShipping,
                    total: newTotal
                },
                count: newCount
            }
        });

        // Server Sync (Immediate for delete)
        router.delete(route('cart.destroy', itemId), {
            preserveScroll: true,
            onError: (errors) => {
                console.error('Failed to remove item', errors);
            }
        });
    }
}));

// Map to store timeouts for each item
const updateTimeouts = {};

const debouncedUpdate = (itemId, quantity) => {
    // Clear existing timeout for this item
    if (updateTimeouts[itemId]) {
        clearTimeout(updateTimeouts[itemId]);
    }

    // Set new timeout
    updateTimeouts[itemId] = setTimeout(() => {
        if (!itemId) {
            console.error('Cannot update cart: itemId is missing');
            return;
        }

        const url = route('cart.update', itemId);
        console.log(`Updating cart item ${itemId} via PATCH ${url}`, { quantity });

        axios.patch(url, { quantity })
            .catch(err => {
                console.error('Failed to update cart', err);
            })
            .finally(() => {
                delete updateTimeouts[itemId];
            });
    }, 500);
};

export default useCartStore;
