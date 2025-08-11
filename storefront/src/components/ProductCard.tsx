"use client";

import Image from "next/image";
import Link from "next/link";
import { Product } from "@/types/product";
import { formatCurrencyIDR } from "@/utils/format";
import { useCart } from "@/context/CartContext";

export default function ProductCard({ product }: { product: Product }) {
  const { add } = useCart();

  return (
    <div className="rounded-lg border border-black/10 overflow-hidden bg-white flex flex-col">
      <div className="relative aspect-square">
        <Image src={product.image} alt={product.name} fill className="object-contain p-6" />
      </div>
      <div className="p-4 flex flex-col gap-2">
        <Link href={`/products/${product.slug}`} className="font-medium hover:underline">
          {product.name}
        </Link>
        <p className="text-sm text-black/60 line-clamp-2 min-h-[2.5rem]">{product.description}</p>
        <div className="mt-2 flex items-center justify-between">
          <span className="font-semibold">{formatCurrencyIDR(product.price)}</span>
          <button
            onClick={() => add(product.id, 1)}
            className="rounded-md bg-black text-white text-sm px-3 py-1.5 hover:bg-black/80"
          >
            Tambah
          </button>
        </div>
      </div>
    </div>
  );
}