"use client";

import Link from "next/link";
import CartItem from "@/components/CartItem";
import { useCart } from "@/context/CartContext";
import { formatCurrencyIDR } from "@/utils/format";
import { WHATSAPP_PHONE } from "@/config";

export default function CartPage() {
  const { linesDetailed, subtotal, clear } = useCart();

  const waMessage = encodeURIComponent(
    [
      "Halo, saya ingin memesan:",
      ...linesDetailed.map(
        (l) => `- ${l.product.name} x${l.quantity} = ${formatCurrencyIDR(l.lineTotal)}`
      ),
      `Total: ${formatCurrencyIDR(subtotal)}`,
    ].join("\n")
  );

  const waUrl = `https://wa.me/${WHATSAPP_PHONE}?text=${waMessage}`;

  return (
    <div className="mx-auto max-w-6xl px-4 py-8">
      <h1 className="text-2xl font-bold mb-6">Keranjang</h1>

      {linesDetailed.length === 0 ? (
        <div className="rounded-lg border border-black/10 p-6 text-center">
          Keranjang kosong. <Link href="/" className="underline">Belanja sekarang</Link>
        </div>
      ) : (
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div className="md:col-span-2 rounded-lg border border-black/10 p-4">
            {linesDetailed.map((l) => (
              <CartItem
                key={l.product.id}
                productId={l.product.id}
                name={l.product.name}
                image={l.product.image}
                price={l.product.price}
                quantity={l.quantity}
              />
            ))}
          </div>
          <aside className="rounded-lg border border-black/10 p-4 h-fit">
            <div className="flex items-center justify-between mb-2">
              <span className="text-black/70">Subtotal</span>
              <span className="font-semibold">{formatCurrencyIDR(subtotal)}</span>
            </div>
            <button
              onClick={() => window.open(waUrl, "_blank")}
              className="w-full rounded-md bg-green-600 text-white px-4 py-2 mt-4 hover:bg-green-700"
            >
              Checkout via WhatsApp
            </button>
            <button onClick={clear} className="w-full rounded-md border border-black/10 px-4 py-2 mt-3 hover:bg-black/5">
              Kosongkan Keranjang
            </button>
          </aside>
        </div>
      )}
    </div>
  );
}