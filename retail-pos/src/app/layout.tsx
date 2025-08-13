import type { Metadata } from "next";
import "./globals.css";

export const metadata: Metadata = {
  title: "Kasir Mandiri",
  description: "Aplikasi toko mandiri (POS)",
};

export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="id">
      <body className="bg-pink-50 text-gray-900">
        <div className="min-h-screen">
          {children}
        </div>
      </body>
    </html>
  );
}
