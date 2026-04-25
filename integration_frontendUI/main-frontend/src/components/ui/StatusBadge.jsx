export default function StatusBadge({ status }) {
  const statusConfig = {
    Approved: { bg: "bg-emerald-500/15", text: "text-emerald-400", dot: "bg-emerald-400" },
    Enrolled: { bg: "bg-emerald-500/15", text: "text-emerald-400", dot: "bg-emerald-400" },
    Pending: { bg: "bg-amber-500/15", text: "text-amber-400", dot: "bg-amber-400" },
    Waitlisted: { bg: "bg-orange-500/15", text: "text-orange-400", dot: "bg-orange-400" },
    Rejected: { bg: "bg-rose-500/15", text: "text-rose-400", dot: "bg-rose-400" },
    Active: { bg: "bg-cyan-500/15", text: "text-cyan-400", dot: "bg-cyan-400" },
  };

  const config = statusConfig[status] || { bg: "bg-surface-500/15", text: "text-surface-400", dot: "bg-surface-400" };

  return (
    <span className={`inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium ${config.bg} ${config.text}`}>
      <span className={`w-1.5 h-1.5 rounded-full ${config.dot}`}></span>
      {status}
    </span>
  );
}
