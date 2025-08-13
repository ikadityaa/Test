"use client";

import { useCartStore } from "@/store/cart";
import { formatRupiah } from "@/lib/format";
import { useState } from "react";

export default function CartDrawer() {
  const { items, increase, decrease, remove, clear, total } = useCartStore();
  const [open, setOpen] = useState(true);
  const [loading, setLoading] = useState(false);

  const handleCheckout = async () => {
    if (items.length === 0) return;
    setLoading(true);
    try {
      const res = await fetch("/api/orders", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          paymentType: "cash",
          items: items.map((i) => ({
            productId: i.productId,
            quantity: i.quantity,
            unitPrice: i.price,
          })),
        }),
      });
      if (!res.ok) throw new Error("Checkout gagal");
      clear();
      alert("Transaksi berhasil");
    } catch (e) {
      console.error(e);
      alert("Terjadi kesalahan saat checkout");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className={`fixed right-0 top-0 h-full w-full sm:w-[380px] bg-white shadow-2xl transition-transform duration-200 ${open ? "translate-x-0" : "translate-x-full"}`}>
      <div className="p-4 border-b flex items-center justify-between bg-pink-600 text-white">
        <div className="font-semibold">Keranjang</div>
        <button onClick={() => setOpen(!open)} className="px-3 py-1 bg-white text-pink-600 rounded">
          {open ? "Tutup" : "Buka"}
        </button>
      </div>
      <div className="p-4 space-y-3 overflow-y-auto h-[calc(100%-140px)]">
        {items.length === 0 && <div className="text-sm text-gray-500">Belum ada item</div>}
        {items.map((i) => (
          <div key={i.productId} className="flex gap-2 items-center border rounded p-2">
            {/* eslint-disable-next-line @next/next/no-img-element */}
            <img
              src={i.imageUrl ?? "/placeholder.svg"}
              alt={i.name}
              className="w-14 h-14 object-cover rounded"
            />
            <div className="flex-1">
              <div className="font-medium">{i.name}</div>
              <div className="text-xs text-gray-500">{formatRupiah(i.price)}</div>
              <div className="flex items-center gap-2 mt-1">
                <button className="px-2 bg-gray-200 rounded" onClick={() => decrease(i.productId)}>-</button>
                <span>{i.quantity}</span>
                <button className="px-2 bg-gray-200 rounded" onClick={() => increase(i.productId)}>+</button>
                <button className="ml-2 text-red-600 text-sm" onClick={() => remove(i.productId)}>Hapus</button>
              </div>
            </div>
            <div className="font-semibold">{formatRupiah(i.price * i.quantity)}</div>
          </div>
        ))}
      </div>
      <div className="p-4 border-t bg-gray-50">
        <div className="flex items-center justify-between mb-3">
          <div>Total</div>
          <div className="font-bold text-lg">{formatRupiah(total())}</div>
        </div>
        <button
          className="w-full py-2 bg-pink-600 text-white rounded disabled:opacity-50"
          onClick={handleCheckout}
          disabled={items.length === 0 || loading}
        >
          {loading ? "Memproses..." : "Bayar (Cash)"}
        </button>
      </div>
    </div>
  );
}