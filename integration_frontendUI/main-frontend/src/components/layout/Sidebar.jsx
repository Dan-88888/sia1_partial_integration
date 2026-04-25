import { Link, useLocation } from "react-router-dom";
import { HiOutlineHome, HiOutlineClipboardList, HiOutlineIdentification, HiOutlineCalendar, HiOutlineMap, HiOutlineOfficeBuilding, HiOutlineChevronLeft, HiOutlineChevronRight } from "react-icons/hi";
import { useState } from "react";
import logo from "../../assets/logo.png";

const navItems = [
  { path: "/dashboard", label: "Dashboard", icon: <HiOutlineHome className="w-5 h-5" /> },
  { path: "/admission", label: "Admission", icon: <HiOutlineClipboardList className="w-5 h-5" /> },
  { path: "/registration", label: "Registration", icon: <HiOutlineIdentification className="w-5 h-5" /> },
  { path: "/scheduling", label: "Scheduling", icon: <HiOutlineCalendar className="w-5 h-5" /> },
  { path: "/map", label: "Map", icon: <HiOutlineMap className="w-5 h-5" /> },
  { path: "/campuses", label: "ParSU Campuses", icon: <HiOutlineOfficeBuilding className="w-5 h-5" /> },
];

export default function Sidebar() {
  const location = useLocation();
  const [collapsed, setCollapsed] = useState(false);

  return (
    <aside
      className={`${
        collapsed ? "w-20" : "w-72"
      } min-h-screen bg-surface-950/80 backdrop-blur-xl border-r border-surface-700/30
        flex flex-col transition-all duration-300 ease-in-out relative z-10`}
    >
      {/* Logo Section */}
      <div className={`p-6 border-b border-surface-700/30 flex items-center ${collapsed ? "justify-center" : "gap-3"}`}>
        {/* Modern Logo Container */}
        <div className="relative flex-shrink-0 group cursor-default">
          <div className="absolute inset-0 bg-gradient-to-tr from-primary-500/30 to-violet-500/30 blur-md rounded-full group-hover:from-primary-500/50 group-hover:to-violet-500/50 transition-all duration-300"></div>
          <div className="relative p-0.5 bg-surface-800 rounded-full border border-surface-600/50 shadow-xl">
             <img src={logo} alt="SUSA Logo" className="w-12 h-12 rounded-full object-contain bg-white/5" />
          </div>
        </div>
        {!collapsed && (
          <div className="animate-fade-in">
            <h1 className="font-bold text-white text-sm leading-tight">SUSA - State University<br/>System for Academics</h1>
            <p className="text-[10px] text-surface-500 uppercase tracking-widest mt-1">ParSU SYSTEM</p>
          </div>
        )}
      </div>

      {/* Navigation */}
      <nav className="flex-1 p-4 space-y-1.5">
        <p className={`text-[10px] uppercase tracking-widest text-surface-600 font-semibold mb-4 px-3 ${collapsed ? "text-center" : ""}`}>
          {collapsed ? "•••" : "Main Menu"}
        </p>
        {navItems.map((item) => {
          const isActive = location.pathname === item.path;
          return (
            <Link
              key={item.path}
              to={item.path}
              className={`flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 group relative
                ${collapsed ? "justify-center" : ""}
                ${
                  isActive
                    ? "bg-primary-600/20 text-primary-400 shadow-lg shadow-primary-500/10"
                    : "text-surface-400 hover:text-white hover:bg-surface-800/60"
                }`}
            >
              {/* Active indicator bar */}
              {isActive && (
                <div className="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-7 bg-primary-500 rounded-r-full" />
              )}
              <span className={`flex-shrink-0 transition-transform duration-200 ${isActive ? "scale-110" : "group-hover:scale-110"}`}>
                {item.icon}
              </span>
              {!collapsed && (
                <span className="font-medium text-sm">{item.label}</span>
              )}
              {collapsed && (
                <div className="absolute left-full ml-2 px-2 py-1 bg-surface-800 text-white text-xs rounded-md
                              opacity-0 invisible group-hover:opacity-100 group-hover:visible
                              transition-all duration-200 whitespace-nowrap shadow-xl z-50 pointer-events-none">
                  {item.label}
                </div>
              )}
            </Link>
          );
        })}
      </nav>

      {/* Collapse Toggle */}
      <div className="p-4 border-t border-surface-700/30">
        <button
          onClick={() => setCollapsed(!collapsed)}
          className="w-full flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl
                     text-surface-500 hover:text-white hover:bg-surface-800/60
                     transition-all duration-200 text-sm"
        >
          {collapsed ? (
            <HiOutlineChevronRight className="w-4 h-4" />
          ) : (
            <>
              <HiOutlineChevronLeft className="w-4 h-4" />
              <span>Collapse</span>
            </>
          )}
        </button>
      </div>
    </aside>
  );
}