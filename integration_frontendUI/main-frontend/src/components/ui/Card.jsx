export default function Card({ title, description, icon, children, className = "" }) {
  return (
    <div className={`glass-card p-6 hover:shadow-lg transition-all duration-300 ${className}`}>
      {(title || icon) && (
        <div className="flex items-center gap-3 mb-4">
          {icon && <div className="p-2 rounded-lg bg-primary-500/15 text-primary-400">{icon}</div>}
          <div>
            {title && <h3 className="font-semibold text-white text-lg">{title}</h3>}
            {description && <p className="text-surface-400 text-sm mt-0.5">{description}</p>}
          </div>
        </div>
      )}
      {children}
    </div>
  );
}