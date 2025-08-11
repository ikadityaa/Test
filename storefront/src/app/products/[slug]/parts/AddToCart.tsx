"use client";

import { useState } from "react";
import { useCart } from "@/context/CartContext";

export default function AddToCart({ productId }: { productId: string }) {
  const { add } = useCart();
  const [qty, setQty] = useState(1);

  return (
    <div className="flex items-center gap-3 pt-2">
      <div className="flex items-center gap-2">
        <button onClick={() => setQty((q) => Math.max(1, q - 1))} className="size-8 rounded-md border border-black/10">
          −
        </button>
        <input
          type="number"
          min={1}
          value={qty}
          onChange={(e) => setQty(Math.max(1, Number(e.target.value)))}
          className="w-16 rounded-md border border-black/10 p-1 text-center"
        />
        <button onClick={() => setQty((q) => q + 1)} className="size-8 rounded-md border border-black/10">
          +
        </button>
      </div>
      <button onClick={() => add(productId, qty)} className="rounded-md bg-black text-white px-4 py-2 hover:bg-black/80">
        Tambah ke Keranjang
      </button>
    </div>
  );
}