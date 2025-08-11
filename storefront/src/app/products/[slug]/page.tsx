import Image from "next/image";
import { notFound } from "next/navigation";
import { findProductBySlug } from "@/data/products";
import { formatCurrencyIDR } from "@/utils/format";
import AddToCart from "./parts/AddToCart";

export default function ProductDetail({ params }: { params: { slug: string } }) {
  const product = findProductBySlug(params.slug);
  if (!product) return notFound();

  return (
    <div className="mx-auto max-w-6xl px-4 py-8 grid grid-cols-1 md:grid-cols-2 gap-8">
      <div className="relative aspect-square rounded-lg border border-black/10 bg-white overflow-hidden">
        <Image src={product.image} alt={product.name} fill className="object-contain p-8" />
      </div>
      <div className="space-y-4">
        <h1 className="text-2xl font-bold">{product.name}</h1>
        <div className="text-lg font-semibold">{formatCurrencyIDR(product.price)}</div>
        <p className="text-black/70 leading-relaxed">{product.description}</p>
        <AddToCart productId={product.id} />
      </div>
    </div>
  );
}