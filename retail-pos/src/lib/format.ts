export function formatRupiah(amountInSmallestUnit: number): string {
  const amount = amountInSmallestUnit / 100;
  return new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    minimumFractionDigits: 2,
  }).format(amount);
}

export function toSmallestUnit(amount: number): number {
  return Math.round(amount * 100);
}