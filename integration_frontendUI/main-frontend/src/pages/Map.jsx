import { useState } from "react";
import { HiOutlineMap, HiOutlineZoomIn, HiOutlineZoomOut, HiOutlineLocationMarker } from "react-icons/hi";
import campusMap from "../assets/images/maps/2d-map.png";
import frontView from "../assets/images/maps/3d-map.png";

export default function Map() {
  const [zoom, setZoom] = useState(1);
  const [activeTab, setActiveTab] = useState("map");

  const handleZoomIn = () => setZoom((prev) => Math.min(prev + 0.2, 3));
  const handleZoomOut = () => setZoom((prev) => Math.max(prev - 0.2, 0.5));
  const handleReset = () => setZoom(1);

  const tabs = [
    { id: "map", label: "2D Campus Map", icon: <HiOutlineMap className="w-4 h-4" /> },
    { id: "front", label: "Front View", icon: <HiOutlineLocationMarker className="w-4 h-4" /> },
  ];

  return (
    <div className="page-container">
      {/* Page Header */}
      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 animate-fade-in">
        <div className="page-header mb-0">
          <div className="flex items-center gap-3">
            <div className="p-2.5 rounded-xl bg-teal-500/15">
              <HiOutlineMap className="w-6 h-6 text-teal-400" />
            </div>
            <div>
              <h1 className="page-title">Campus Map</h1>
              <p className="page-subtitle text-base">
                Partido State University — 2D Map &amp; Front View
              </p>
            </div>
          </div>
        </div>
      </div>

      {/* Tab Switcher */}
      <div className="flex items-center gap-2 animate-fade-in-delay-1" style={{ opacity: 0, animation: 'fade-in 0.5s ease-out 0.1s forwards' }}>
        {tabs.map((tab) => (
          <button
            key={tab.id}
            onClick={() => { setActiveTab(tab.id); setZoom(1); }}
            className={`flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
              ${activeTab === tab.id
                ? "bg-primary-600/20 text-primary-400 border border-primary-500/30 shadow-lg shadow-primary-500/10"
                : "text-surface-400 hover:text-white hover:bg-surface-800/60 border border-transparent"
              }`}
          >
            {tab.icon}
            {tab.label}
          </button>
        ))}
      </div>

      {/* Map / Image Display */}
      <div className="glass-card overflow-hidden animate-fade-in-delay-2" style={{ opacity: 0, animation: 'fade-in 0.5s ease-out 0.2s forwards' }}>
        {/* Zoom Controls */}
        <div className="flex items-center justify-between p-4 border-b border-surface-700/40">
          <div className="flex items-center gap-2">
            <span className="text-sm text-surface-400">
              {activeTab === "map" ? "2D Campus Map" : "Front View — Partido State University"}
            </span>
          </div>
          <div className="flex items-center gap-2">
            <button
              onClick={handleZoomOut}
              className="p-2 rounded-lg hover:bg-surface-700/50 text-surface-400 hover:text-white transition-all duration-150"
              title="Zoom Out"
            >
              <HiOutlineZoomOut className="w-5 h-5" />
            </button>
            <button
              onClick={handleReset}
              className="px-3 py-1.5 rounded-lg hover:bg-surface-700/50 text-surface-400 hover:text-white transition-all duration-150 text-xs font-mono"
              title="Reset Zoom"
            >
              {Math.round(zoom * 100)}%
            </button>
            <button
              onClick={handleZoomIn}
              className="p-2 rounded-lg hover:bg-surface-700/50 text-surface-400 hover:text-white transition-all duration-150"
              title="Zoom In"
            >
              <HiOutlineZoomIn className="w-5 h-5" />
            </button>
          </div>
        </div>

        {/* Image Container */}
        <div className="relative overflow-auto bg-surface-950/50" style={{ maxHeight: "70vh" }}>
          <div
            className="flex items-center justify-center p-6 transition-transform duration-300 ease-out"
            style={{ minHeight: "400px" }}
          >
            <img
              src={activeTab === "map" ? campusMap : frontView}
              alt={activeTab === "map" ? "2D Campus Map of Partido State University" : "Front View of Partido State University"}
              className="rounded-lg shadow-2xl shadow-black/30 transition-transform duration-300 ease-out"
              style={{
                transform: `scale(${zoom})`,
                transformOrigin: "center center",
                maxWidth: "100%",
                height: "auto",
              }}
              draggable={false}
            />
          </div>
        </div>

        {/* Info Bar */}
        <div className="px-4 py-3 border-t border-surface-700/40 flex items-center justify-between">
          <p className="text-xs text-surface-500">
            {activeTab === "map"
              ? "ParSU Main Campus — Top-down campus layout showing buildings, fields, and roads"
              : "ParSU Main Gate — Partido State University, Camarines Sur"
            }
          </p>
          <p className="text-xs text-surface-600">
            Scroll to pan • Use controls to zoom
          </p>
        </div>
      </div>

      {/* Campus Info Cards */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-5" style={{ opacity: 0, animation: 'fade-in 0.5s ease-out 0.3s forwards' }}>
        <div className="glass-card p-5 hover:scale-[1.02] transition-all duration-200">
          <div className="flex items-center gap-3 mb-3">
            <div className="p-2 rounded-lg bg-teal-500/15 text-teal-400">
              <HiOutlineLocationMarker className="w-5 h-5" />
            </div>
            <h3 className="font-semibold text-white">Location</h3>
          </div>
          <p className="text-sm text-surface-400">Goa, Camarines Sur, Philippines</p>
        </div>
        <div className="glass-card p-5 hover:scale-[1.02] transition-all duration-200">
          <div className="flex items-center gap-3 mb-3">
            <div className="p-2 rounded-lg bg-primary-500/15 text-primary-400">
              <HiOutlineMap className="w-5 h-5" />
            </div>
            <h3 className="font-semibold text-white">Campus</h3>
          </div>
          <p className="text-sm text-surface-400">Main Campus — featuring academic buildings, sports field, and admin facilities</p>
        </div>
        <div className="glass-card p-5 hover:scale-[1.02] transition-all duration-200">
          <div className="flex items-center gap-3 mb-3">
            <div className="p-2 rounded-lg bg-violet-500/15 text-violet-400">
              <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={1.5}>
                <path strokeLinecap="round" strokeLinejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21" />
              </svg>
            </div>
            <h3 className="font-semibold text-white">Institution</h3>
          </div>
          <p className="text-sm text-surface-400">Partido State University — a public state university in the Bicol Region</p>
        </div>
      </div>
    </div>
  );
}
