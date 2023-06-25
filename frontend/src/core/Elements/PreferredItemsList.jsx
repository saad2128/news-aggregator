import React from "react";

export function PreferredItemsList({ title, items, maxDisplayedItems }) {
  return (
    <div className="mt-2">
      <label className="block text-sm font-medium text-gray-700">
        {title}:
      </label>
      <div className="flex gap-2">
        {items.slice(0, maxDisplayedItems).map((item) => (
          <div key={item.label} className="px-3 py-1 bg-gray-200 rounded">
            {item.label}
          </div>
        ))}
        {items.length > maxDisplayedItems && (
          <div className="px-3 py-1">.. and more</div>
        )}
      </div>
    </div>
  );
}
