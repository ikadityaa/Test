"use client";

import useSWR from "swr";
import { useCartStore } from "@/store/cart";
import { formatRupiah } from "@/lib/format";

const fetcher = (url: string) => fetch(url).then((r) => r.json());

type Product = {
  id: number;
  name: string;
  price: number;
  imageUrl?: string | null;
};

export default function ProductGrid({
  query,
  category,
}: {
  query?: string;
  category?: string;
}) {
  const { data } = useSWR<Product[]>(
    `/api/products?${new URLSearchParams({ ...(query ? { q: query } : {}), ...(category ? { category } : {}) }).toString()}`,
    fetcher
  );
  const addItem = useCartStore((s) => s.addItem);

  return (
    <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
      {data?.map((p) => (
        <div key={p.id} className="bg-white rounded-xl shadow p-3">
          {/* eslint-disable-next-line @next/next/no-img-element */}
          <img
            src={p.imageUrl ?? "/placeholder.svg"}
            alt={p.name}
            className="w-full h-40 object-cover rounded-lg"
          />
          <div className="mt-2 font-semibold">{p.name}</div>
          <div className="text-sm text-gray-600">{formatRupiah(p.price)}</div>
          <button
            onClick={() =>
              addItem({ productId: p.id, name: p.name, price: p.price, imageUrl: p.imageUrl })
            }
            className="mt-2 w-full bg-pink-600 text-white py-2 rounded-lg"
          >
            Tambah
          </button>
        </div>
      ))}
    </div>
  );
}