"use client";

import { createContext, useCallback, useContext, useEffect, useMemo, useReducer } from "react";
import { findProductById } from "@/data/products";
import { Product } from "@/types/product";

export type CartLine = {
  productId: string;
  quantity: number;
};

type CartState = {
  lines: CartLine[];
};

type CartAction =
  | { type: "ADD"; productId: string; quantity?: number }
  | { type: "REMOVE"; productId: string }
  | { type: "SET_QTY"; productId: string; quantity: number }
  | { type: "CLEAR" }
  | { type: "LOAD"; state: CartState };

const STORAGE_KEY = "cart-v1";

function cartReducer(state: CartState, action: CartAction): CartState {
  switch (action.type) {
    case "LOAD":
      return action.state;
    case "ADD": {
      const quantityToAdd = action.quantity ?? 1;
      const existing = state.lines.find((l) => l.productId === action.productId);
      if (existing) {
        return {
          lines: state.lines.map((l) =>
            l.productId === action.productId
              ? { ...l, quantity: l.quantity + quantityToAdd }
              : l
          ),
        };
      }
      return { lines: [...state.lines, { productId: action.productId, quantity: quantityToAdd }] };
    }
    case "REMOVE":
      return { lines: state.lines.filter((l) => l.productId !== action.productId) };
    case "SET_QTY": {
      const newQty = Math.max(1, action.quantity);
      return {
        lines: state.lines.map((l) => (l.productId === action.productId ? { ...l, quantity: newQty } : l)),
      };
    }
    case "CLEAR":
      return { lines: [] };
    default:
      return state;
  }
}

function loadFromStorage(): CartState | null {
  if (typeof window === "undefined") return null;
  try {
    const raw = window.localStorage.getItem(STORAGE_KEY);
    if (!raw) return null;
    return JSON.parse(raw) as CartState;
  } catch {
    return null;
  }
}

function persistToStorage(state: CartState) {
  if (typeof window === "undefined") return;
  try {
    window.localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
  } catch {
    // ignore
  }
}

type EnrichedCartLine = {
  product: Product;
  quantity: number;
  lineTotal: number;
};

export type CartContextValue = {
  state: CartState;
  add: (productId: string, quantity?: number) => void;
  remove: (productId: string) => void;
  setQuantity: (productId: string, quantity: number) => void;
  clear: () => void;
  linesDetailed: EnrichedCartLine[];
  itemCount: number;
  subtotal: number;
};

const CartContext = createContext<CartContextValue | undefined>(undefined);

export function CartProvider({ children }: { children: React.ReactNode }) {
  const [state, dispatch] = useReducer(cartReducer, { lines: [] });

  useEffect(() => {
    const loaded = loadFromStorage();
    if (loaded) {
      dispatch({ type: "LOAD", state: loaded });
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  useEffect(() => {
    persistToStorage(state);
  }, [state]);

  const add = useCallback((productId: string, quantity?: number) => dispatch({ type: "ADD", productId, quantity }), []);
  const remove = useCallback((productId: string) => dispatch({ type: "REMOVE", productId }), []);
  const setQuantity = useCallback((productId: string, quantity: number) => dispatch({ type: "SET_QTY", productId, quantity }), []);
  const clear = useCallback(() => dispatch({ type: "CLEAR" }), []);

  const linesDetailed = useMemo<EnrichedCartLine[]>(() => {
    return state.lines
      .map((line) => {
        const product = findProductById(line.productId);
        if (!product) return null;
        return {
          product,
          quantity: line.quantity,
          lineTotal: product.price * line.quantity,
        };
      })
      .filter(Boolean) as EnrichedCartLine[];
  }, [state.lines]);

  const itemCount = useMemo(() => state.lines.reduce((acc, l) => acc + l.quantity, 0), [state.lines]);
  const subtotal = useMemo(() => linesDetailed.reduce((acc, l) => acc + l.lineTotal, 0), [linesDetailed]);

  const value: CartContextValue = {
    state,
    add,
    remove,
    setQuantity,
    clear,
    linesDetailed,
    itemCount,
    subtotal,
  };

  return <CartContext.Provider value={value}>{children}</CartContext.Provider>;
}

export function useCart(): CartContextValue {
  const ctx = useContext(CartContext);
  if (!ctx) throw new Error("useCart harus dipakai di dalam CartProvider");
  return ctx;
}