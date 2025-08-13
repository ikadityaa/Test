import prisma from "@/lib/prisma";
import { NextResponse } from "next/server";
import { Prisma } from "@prisma/client";

export async function GET(request: Request) {
  const { searchParams } = new URL(request.url);
  const q = searchParams.get("q") ?? undefined;
  const barcode = searchParams.get("barcode") ?? undefined;
  const category = searchParams.get("category") ?? undefined;

  const where: Prisma.ProductWhereInput = {
    AND: [
      q
        ? {
            OR: [
              { name: { contains: q } },
              { sku: { contains: q } },
              { barcode: { contains: q } },
            ],
          }
        : {},
      barcode ? { barcode } : {},
      category ? { category: { is: { slug: category } } } : {},
    ],
  };

  const products = await prisma.product.findMany({
    where,
    orderBy: { createdAt: "desc" },
  });

  return NextResponse.json(products);
}