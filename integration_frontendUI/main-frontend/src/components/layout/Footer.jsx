export default function Footer() {
  return (
    <footer className="border-t border-surface-700/30 px-6 lg:px-8 py-4">
      <div className="flex flex-col sm:flex-row items-center justify-between gap-2">
        <p className="text-xs text-surface-600">
          This site is maintained and managed by the ICTMO © {new Date().getFullYear()}
        </p>
      </div>
    </footer>
  );
}
