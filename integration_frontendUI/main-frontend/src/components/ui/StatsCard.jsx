import { useEffect, useState, useRef } from "react";

export default function StatsCard({ icon, label, value, trend, trendLabel, color = "primary", delay = 0 }) {
  const [displayValue, setDisplayValue] = useState(0);
  const [isVisible, setIsVisible] = useState(false);
  const cardRef = useRef(null);

  const colorMap = {
    primary: {
      bg: "from-primary-600/20 to-primary-800/10",
      icon: "bg-primary-500/20 text-primary-400",
      border: "border-primary-500/20",
      glow: "shadow-primary-500/10",
    },
    accent: {
      bg: "from-accent-600/20 to-accent-800/10",
      icon: "bg-accent-500/20 text-accent-400",
      border: "border-accent-500/20",
      glow: "shadow-accent-500/10",
    },
    violet: {
      bg: "from-violet-600/20 to-violet-800/10",
      icon: "bg-violet-500/20 text-violet-400",
      border: "border-violet-500/20",
      glow: "shadow-violet-500/10",
    },
    amber: {
      bg: "from-amber-600/20 to-amber-800/10",
      icon: "bg-amber-500/20 text-amber-400",
      border: "border-amber-500/20",
      glow: "shadow-amber-500/10",
    },
    rose: {
      bg: "from-rose-600/20 to-rose-800/10",
      icon: "bg-rose-500/20 text-rose-400",
      border: "border-rose-500/20",
      glow: "shadow-rose-500/10",
    },
    cyan: {
      bg: "from-cyan-600/20 to-cyan-800/10",
      icon: "bg-cyan-500/20 text-cyan-400",
      border: "border-cyan-500/20",
      glow: "shadow-cyan-500/10",
    },
  };

  const colors = colorMap[color] || colorMap.primary;

  useEffect(() => {
    const timer = setTimeout(() => setIsVisible(true), delay);
    return () => clearTimeout(timer);
  }, [delay]);

  useEffect(() => {
    if (!isVisible) return;
    const numValue = typeof value === "number" ? value : parseInt(value, 10);
    if (isNaN(numValue)) {
      setDisplayValue(value);
      return;
    }

    let start = 0;
    const duration = 1000;
    const stepTime = 20;
    const steps = duration / stepTime;
    const increment = numValue / steps;

    const counter = setInterval(() => {
      start += increment;
      if (start >= numValue) {
        setDisplayValue(numValue);
        clearInterval(counter);
      } else {
        setDisplayValue(Math.round(start));
      }
    }, stepTime);

    return () => clearInterval(counter);
  }, [value, isVisible]);

  return (
    <div
      ref={cardRef}
      className={`glass-card bg-gradient-to-br ${colors.bg} border ${colors.border} p-6 
                  hover:shadow-xl ${colors.glow} hover:scale-[1.02] 
                  transition-all duration-300 cursor-default
                  ${isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-4"}
                  transition-all duration-500`}
      style={{ transitionDelay: `${delay}ms` }}
    >
      <div className="flex items-start justify-between">
        <div className="space-y-3">
          <p className="text-surface-400 text-sm font-medium uppercase tracking-wider">{label}</p>
          <p className="text-3xl font-bold text-white tabular-nums">{displayValue}</p>
          {trend !== undefined && (
            <div className="flex items-center gap-1.5">
              <span className={`text-xs font-medium ${trend >= 0 ? "text-accent-400" : "text-rose-400"}`}>
                {trend >= 0 ? "↑" : "↓"} {Math.abs(trend)}%
              </span>
              {trendLabel && <span className="text-surface-500 text-xs">{trendLabel}</span>}
            </div>
          )}
        </div>
        <div className={`p-3 rounded-xl ${colors.icon}`}>
          {icon}
        </div>
      </div>
    </div>
  );
}
