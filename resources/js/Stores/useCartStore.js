import { create } from 'zustand';

const useCartStore = create((set) => ({
    count: 0,
    setCount: (count) => set({ count }),
    increment: (amount = 1) => set((state) => ({ count: state.count + amount })),
    decrement: (amount = 1) => set((state) => ({ count: Math.max(0, state.count - amount) })),
}));

export default useCartStore;
