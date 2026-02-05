import { create } from 'zustand';

const useCartStore = create((set) => ({
    count: 0,
    isCartOpen: false,
    setCount: (count) => set({ count }),
    openCart: () => set({ isCartOpen: true }),
    closeCart: () => set({ isCartOpen: false }),
    toggleCart: () => set((state) => ({ isCartOpen: !state.isCartOpen })),
    increment: (amount = 1) => set((state) => ({ count: state.count + amount })),
    decrement: (amount = 1) => set((state) => ({ count: Math.max(0, state.count - amount) })),
}));

export default useCartStore;
