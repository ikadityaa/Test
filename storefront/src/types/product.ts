export type Product = {
  id: string;
  slug: string;
  name: string;
  description: string;
  price: number; // in IDR
  image: string; // public path, e.g., /products/xxx.svg
  tags?: string[];
};