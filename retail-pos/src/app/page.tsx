"use client";

import { useState } from "react";
import CategoryTabs from "./components/CategoryTabs";
import ProductGrid from "./components/ProductGrid";
import CartDrawer from "./components/CartDrawer";

export default function Home() {
  const [query, setQuery] = useState("");
  const [category, setCategory] = useState<string | undefined>(undefined);

  return (
    <div className="min-h-screen">
      <div className="bg-pink-600 text-white py-4 shadow">
        <div className="container mx-auto px-4 flex items-center justify-between">
          <div className="font-bold text-xl">Kasir Mandiri</div>
          <div className="flex items-center gap-3">
            <div className="text-sm">Keranjang</div>
          </div>
        </div>
      </div>

      <div className="container mx-auto px-4 py-4">
        <div className="text-center text-2xl font-bold text-pink-700">toko mandiri</div>

        <div className="mt-4 flex items-center gap-2">
          <input
            value={query}
            onChange={(e) => setQuery(e.target.value)}
            placeholder="Cari Barang / Barcode"
            className="flex-1 rounded-full border px-4 py-2"
          />
          {/* Scanner button placeholder */}
          <button className="rounded-full bg-pink-600 text-white px-4 py-2">Scan</button>
        </div>

        <div className="mt-4">
          <CategoryTabs onChange={setCategory} />
        </div>

        <div className="mt-4">
          <ProductGrid query={query} category={category} />
        </div>
      </div>

      <CartDrawer />
    </div>
  );
}
