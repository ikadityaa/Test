import { create } from "zustand";

export type CartItem = {
  productId: number;
  name: string;
  price: number; // smallest unit
  quantity: number;
  imageUrl?: string | null;
};

type CartState = {
  items: CartItem[];
  addItem: (item: Omit<CartItem, "quantity">) => void;
  increase: (productId: number) => void;
  decrease: (productId: number) => void;
  remove: (productId: number) => void;
  clear: () => void;
  total: () => number;
};

export const useCartStore = create<CartState>((set, get) => ({
  items: [],
  addItem: (item) =>
    set((state) => {
      const existing = state.items.find((i) => i.productId === item.productId);
      if (existing) {
        return {
          items: state.items.map((i) =>
            i.productId === item.productId
              ? { ...i, quantity: i.quantity + 1 }
              : i
          ),
        };
      }
      return { items: [...state.items, { ...item, quantity: 1 }] };
    }),
  increase: (productId) =>
    set((state) => ({
      items: state.items.map((i) =>
        i.productId === productId ? { ...i, quantity: i.quantity + 1 } : i
      ),
    })),
  decrease: (productId) =>
    set((state) => ({
      items: state.items
        .map((i) =>
          i.productId === productId ? { ...i, quantity: i.quantity - 1 } : i
        )
        .filter((i) => i.quantity > 0),
    })),
  remove: (productId) =>
    set((state) => ({ items: state.items.filter((i) => i.productId !== productId) })),
  clear: () => set({ items: [] }),
  total: () => get().items.reduce((sum, i) => sum + i.price * i.quantity, 0),
}));