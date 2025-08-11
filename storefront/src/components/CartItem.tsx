"use client";

import Image from "next/image";
import { formatCurrencyIDR } from "@/utils/format";
import { useCart } from "@/context/CartContext";

export default function CartItem({
  productId,
  name,
  image,
  price,
  quantity,
}: {
  productId: string;
  name: string;
  image: string;
  price: number;
  quantity: number;
}) {
  const { setQuantity, remove } = useCart();

  return (
    <div className="flex items-center gap-4 py-3">
      <div className="relative size-16 shrink-0">
        <Image src={image} alt={name} fill className="object-contain" />
      </div>
      <div className="flex-1">
        <div className="font-medium">{name}</div>
        <div className="text-sm text-black/60">{formatCurrencyIDR(price)}</div>
      </div>
      <div className="flex items-center gap-2">
        <button
          onClick={() => setQuantity(productId, Math.max(1, quantity - 1))}
          className="size-8 rounded-md border border-black/10"
          aria-label="Kurangi"
        >
          −
        </button>
        <input
          type="number"
          min={1}
          value={quantity}
          onChange={(e) => setQuantity(productId, Math.max(1, Number(e.target.value)))}
          className="w-14 rounded-md border border-black/10 p-1 text-center"
        />
        <button
          onClick={() => setQuantity(productId, quantity + 1)}
          className="size-8 rounded-md border border-black/10"
          aria-label="Tambah"
        >
          +
        </button>
      </div>
      <div className="w-24 text-right font-medium">
        {formatCurrencyIDR(price * quantity)}
      </div>
      <button onClick={() => remove(productId)} className="text-sm text-red-600 hover:underline">
        Hapus
      </button>
    </div>
  );
}