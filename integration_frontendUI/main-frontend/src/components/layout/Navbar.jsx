import { useLocation } from "react-router-dom";
import { HiOutlineSearch, HiOutlineBell, HiOutlineUser } from "react-icons/hi";

const pageTitles = {
  "/": "Dashboard",
  "/admission": "Admission",
  "/registration": "Registration",
  "/scheduling": "Scheduling",
  "/map": "Campus Map",
  "/campuses": "ParSU Campuses",
};

export default function Navbar() {
  const location = useLocation();
  const currentPage = pageTitles[location.pathname] || "Dashboard";

  return (
    <nav className="sticky top-0 z-20 bg-surface-900/60 backdrop-blur-xl border-b border-surface-700/30">
      <div className="flex items-center justify-between px-6 lg:px-8 py-4">
        {/* Left: Breadcrumb */}
        <div className="flex items-center gap-2">
          <span className="text-surface-500 text-sm">SUSA</span>
          <span className="text-surface-600">/</span>
          <span className="text-white font-medium text-sm">{currentPage}</span>
        </div>

        {/* Right: Actions */}
        <div className="flex items-center gap-3">
          {/* Search */}
          <div className="relative hidden md:block">
            <HiOutlineSearch className="absolute left-3 top-1/2 -translate-y-1/2 text-surface-500 w-4 h-4" />
            <input
              type="text"
              placeholder="Search..."
              className="w-56 pl-10 pr-4 py-2 bg-surface-800/50 border border-surface-700/40 rounded-lg
                         text-sm text-surface-300 placeholder-surface-500
                         focus:outline-none focus:ring-2 focus:ring-primary-500/40 focus:border-primary-500/40
                         transition-all duration-200"
            />
          </div>

          {/* Notification Bell */}
          <button className="relative p-2.5 rounded-xl hover:bg-surface-800/60 text-surface-400 hover:text-white transition-all duration-200">
            <HiOutlineBell className="w-5 h-5" />
            <span className="absolute top-1.5 right-1.5 w-2 h-2 bg-primary-500 rounded-full"></span>
          </button>

          {/* User Avatar */}
          <button className="flex items-center gap-2.5 pl-3 pr-1 py-1 rounded-xl hover:bg-surface-800/60 transition-all duration-200">
            <div className="hidden sm:block text-right">
              <p className="text-sm font-medium text-white leading-tight">Admin</p>
              <p className="text-[11px] text-surface-500">administrator</p>
            </div>
            <div className="w-9 h-9 rounded-xl bg-gradient-to-br from-primary-500 to-violet-500 flex items-center justify-center">
              <HiOutlineUser className="w-4 h-4 text-white" />
            </div>
          </button>
        </div>
      </div>
    </nav>
  );
}