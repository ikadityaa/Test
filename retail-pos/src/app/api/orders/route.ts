import prisma from "@/lib/prisma";
import { NextResponse } from "next/server";

export async function POST(request: Request) {
  const body = await request.json();
  const { items, paymentType, note } = body as {
    items: { productId: number; quantity: number; unitPrice: number }[];
    paymentType: string;
    note?: string;
  };

  if (!items || items.length === 0) {
    return NextResponse.json({ error: "Cart is empty" }, { status: 400 });
  }

  const total = items.reduce((s, i) => s + i.unitPrice * i.quantity, 0);

  const order = await prisma.order.create({
    data: {
      paymentType,
      note,
      total,
      items: {
        createMany: {
          data: items.map((i) => ({
            productId: i.productId,
            quantity: i.quantity,
            unitPrice: i.unitPrice,
            subtotal: i.unitPrice * i.quantity,
          })),
        },
      },
    },
    include: { items: true },
  });

  return NextResponse.json(order);
}