import { PrismaClient } from "@prisma/client";

const prisma = new PrismaClient();

async function main() {
  const categories = await prisma.category.createMany({
    data: [
      { name: "makanan", slug: "makanan" },
      { name: "minuman", slug: "minuman" },
    ]
  });

  const makanan = await prisma.category.findUnique({ where: { slug: "makanan" } });
  const minuman = await prisma.category.findUnique({ where: { slug: "minuman" } });

  if (!makanan || !minuman) return;

  await prisma.product.createMany({
    data: [
      {
        name: "Nasi Goreng",
        sku: "1",
        barcode: "1",
        price: 1200000, // Rp 12.000,00 in smallest unit
        imageUrl: "https://images.unsplash.com/photo-1550547660-d9450f859349?w=800&q=80",
        stock: 100,
        categoryId: makanan.id,
      },
      {
        name: "Bakso",
        sku: "123",
        barcode: "123",
        price: 1300000,
        imageUrl: "https://images.unsplash.com/photo-1562967914-608f82629710?w=800&q=80",
        stock: 100,
        categoryId: makanan.id,
      },
      {
        name: "Nasi Goreng dan Sate",
        sku: "456",
        barcode: "456",
        price: 2400000,
        imageUrl: "https://images.unsplash.com/photo-1544025162-d76694265947?w=800&q=80",
        stock: 100,
        categoryId: makanan.id,
      },
      {
        name: "Milk Shake",
        sku: "789",
        barcode: "789",
        price: 1800000,
        imageUrl: "https://images.unsplash.com/photo-1497534446932-c925b458314e?w=800&q=80",
        stock: 100,
        categoryId: minuman.id,
      },
    ]
  });

  console.log("Seed completed");
}

main()
  .catch((e) => {
    console.error(e);
    process.exit(1);
  })
  .finally(async () => {
    await prisma.$disconnect();
  });