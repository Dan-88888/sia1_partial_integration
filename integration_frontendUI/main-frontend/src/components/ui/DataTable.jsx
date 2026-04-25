import { useState } from "react";
import { HiOutlineSearch } from "react-icons/hi";
import StatusBadge from "./StatusBadge";

export default function DataTable({ columns, data, searchable = true, searchPlaceholder = "Search..." }) {
  const [search, setSearch] = useState("");
  const [sortKey, setSortKey] = useState(null);
  const [sortDir, setSortDir] = useState("asc");

  const handleSort = (key) => {
    if (sortKey === key) {
      setSortDir(sortDir === "asc" ? "desc" : "asc");
    } else {
      setSortKey(key);
      setSortDir("asc");
    }
  };

  const filteredData = data.filter((row) => {
    if (!search) return true;
    const q = search.toLowerCase();
    return Object.values(row).some(
      (val) => typeof val === "string" && val.toLowerCase().includes(q)
    );
  });

  const sortedData = sortKey
    ? [...filteredData].sort((a, b) => {
        const aVal = a[sortKey] || "";
        const bVal = b[sortKey] || "";
        const comparison = String(aVal).localeCompare(String(bVal), undefined, { numeric: true });
        return sortDir === "asc" ? comparison : -comparison;
      })
    : filteredData;

  return (
    <div className="glass-card overflow-hidden animate-fade-in">
      {searchable && (
        <div className="p-4 border-b border-surface-700/40">
          <div className="relative max-w-sm">
            <HiOutlineSearch className="absolute left-3 top-1/2 -translate-y-1/2 text-surface-500 w-4 h-4" />
            <input
              type="text"
              placeholder={searchPlaceholder}
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              className="w-full pl-10 pr-4 py-2.5 bg-surface-800/50 border border-surface-600/30 rounded-lg
                         text-sm text-surface-200 placeholder-surface-500
                         focus:outline-none focus:ring-2 focus:ring-primary-500/40 focus:border-primary-500/40
                         transition-all duration-200"
            />
          </div>
        </div>
      )}
      <div className="overflow-x-auto">
        <table className="data-table">
          <thead>
            <tr>
              {columns.map((col) => (
                <th
                  key={col.key}
                  onClick={() => col.sortable !== false && handleSort(col.key)}
                  className={col.sortable !== false ? "cursor-pointer hover:text-primary-400 select-none" : ""}
                >
                  <div className="flex items-center gap-1.5">
                    {col.label}
                    {sortKey === col.key && (
                      <span className="text-primary-400">
                        {sortDir === "asc" ? "↑" : "↓"}
                      </span>
                    )}
                  </div>
                </th>
              ))}
            </tr>
          </thead>
          <tbody>
            {sortedData.length === 0 ? (
              <tr>
                <td colSpan={columns.length} className="text-center py-8 text-surface-500">
                  No records found
                </td>
              </tr>
            ) : (
              sortedData.map((row, i) => (
                <tr key={row.id || i}>
                  {columns.map((col) => (
                    <td key={col.key}>
                      {col.render
                        ? col.render(row[col.key], row)
                        : col.key === "status"
                        ? <StatusBadge status={row[col.key]} />
                        : row[col.key]}
                    </td>
                  ))}
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>
      <div className="px-4 py-3 border-t border-surface-700/40 flex items-center justify-between">
        <p className="text-xs text-surface-500">
          Showing {sortedData.length} of {data.length} records
        </p>
      </div>
    </div>
  );
}
