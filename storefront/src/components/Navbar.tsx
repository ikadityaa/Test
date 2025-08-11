"use client";

import Link from "next/link";
import { useCart } from "@/context/CartContext";
import { STORE_NAME } from "@/config";

export default function Navbar() {
  const { itemCount } = useCart();

  return (
    <header className="sticky top-0 z-30 w-full border-b border-black/10 bg-background/80 backdrop-blur supports-[backdrop-filter]:bg-background/60">
      <div className="mx-auto max-w-6xl px-4 py-3 flex items-center justify-between">
        <Link href="/" className="font-bold text-lg tracking-tight">
          {STORE_NAME}
        </Link>
        <nav className="flex items-center gap-4 text-sm">
          <Link href="/" className="hover:underline">
            Beranda
          </Link>
          <Link href="/cart" className="relative">
            Keranjang
            {itemCount > 0 && (
              <span className="absolute -right-3 -top-2 rounded-full bg-black text-white text-[10px] px-1.5 py-0.5">
                {itemCount}
              </span>
            )}
          </Link>
        </nav>
      </div>
    </header>
  );
}