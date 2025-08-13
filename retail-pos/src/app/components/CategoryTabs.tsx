"use client";

import useSWR from "swr";
import { useState } from "react";

const fetcher = (url: string) => fetch(url).then((r) => r.json());

export type Category = {
  id: number;
  name: string;
  slug: string;
};

export default function CategoryTabs({
  onChange,
}: {
  onChange: (slug?: string) => void;
}) {
  const { data } = useSWR<Category[]>("/api/categories", fetcher);
  const [active, setActive] = useState<string | undefined>(undefined);

  const handleClick = (slug?: string) => {
    setActive(slug);
    onChange(slug);
  };

  return (
    <div className="flex gap-2 my-2 flex-wrap">
      <button
        className={`px-3 py-1 rounded-full border ${
          !active ? "bg-pink-600 text-white" : "bg-white"
        }`}
        onClick={() => handleClick(undefined)}
      >
        Semua
      </button>
      {data?.map((c) => (
        <button
          key={c.id}
          className={`px-3 py-1 rounded-full border ${
            active === c.slug ? "bg-pink-600 text-white" : "bg-white"
          }`}
          onClick={() => handleClick(c.slug)}
        >
          {c.name}
        </button>
      ))}
    </div>
  );
}