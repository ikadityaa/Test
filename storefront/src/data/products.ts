import { Product } from "@/types/product";

export const products: Product[] = [
  {
    id: "p-tee-basic",
    slug: "kaos-basic",
    name: "Kaos Basic Premium",
    description: "Kaos basic bahan katun combed 30s, nyaman dipakai harian.",
    price: 79000,
    image: "/products/tee.svg",
    tags: ["fashion", "pria", "wanita"],
  },
  {
    id: "p-hoodie-classic",
    slug: "hoodie-classic",
    name: "Hoodie Classic",
    description: "Hoodie hangat dengan bahan fleece, cocok untuk santai.",
    price: 179000,
    image: "/products/hoodie.svg",
    tags: ["fashion", "hangat"],
  },
  {
    id: "p-mug-ceramic",
    slug: "mug-keramik",
    name: "Mug Keramik 350ml",
    description: "Mug keramik tebal, aman untuk minuman panas/dingin.",
    price: 39000,
    image: "/products/mug.svg",
    tags: ["rumah", "dapur"],
  },
  {
    id: "p-bag-totebag",
    slug: "totebag-kanvas",
    name: "Totebag Kanvas",
    description: "Totebag kanvas kuat, muat laptop 13 inci.",
    price: 59000,
    image: "/products/tote.svg",
    tags: ["fashion", "tas"],
  },
  {
    id: "p-notebook-a5",
    slug: "buku-catatan-a5",
    name: "Buku Catatan A5",
    description: "Notebook A5 120 halaman, kertas halus tidak tembus.",
    price: 29000,
    image: "/products/book.svg",
    tags: ["alat tulis"],
  },
  {
    id: "p-bottle-sport",
    slug: "botol-minum-sport",
    name: "Botol Minum Sport 700ml",
    description: "Botol minum BPA-free dengan tutup flip, mudah dibersihkan.",
    price: 69000,
    image: "/products/bottle.svg",
    tags: ["olahraga", "outdoor"],
  },
];

export function findProductBySlug(slug: string): Product | undefined {
  return products.find((p) => p.slug === slug);
}

export function findProductById(id: string): Product | undefined {
  return products.find((p) => p.id === id);
}