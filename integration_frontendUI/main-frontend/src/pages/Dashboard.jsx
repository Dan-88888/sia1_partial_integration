import { HiOutlineClipboardList, HiOutlineIdentification, HiOutlineCalendar, HiOutlineArrowRight, HiOutlineAcademicCap } from "react-icons/hi";
import { Link } from "react-router-dom";
import StatsCard from "../components/ui/StatsCard";

export default function Dashboard() {
  const quickLinks = [
    { path: "/admission", label: "Admission", desc: "Manage student applications", icon: <HiOutlineClipboardList className="w-6 h-6" />, color: "from-primary-600 to-indigo-600" },
    { path: "/registration", label: "Registration", desc: "Enroll approved students", icon: <HiOutlineIdentification className="w-6 h-6" />, color: "from-violet-600 to-purple-600" },
    { path: "/scheduling", label: "Scheduling", desc: "Manage class schedules", icon: <HiOutlineCalendar className="w-6 h-6" />, color: "from-cyan-600 to-teal-600" },
  ];

  return (
    <div className="page-container">
      {/* Welcome Hero */}
      <div className="relative rounded-2xl overflow-hidden shadow-2xl border border-surface-700/30 animate-fade-in h-[340px] mb-8 flex flex-col items-center justify-center text-center">
        <video 
          autoPlay 
          loop 
          muted 
          playsInline
          controlsList="nodownload"
          onContextMenu={(e) => e.preventDefault()}
          style={{ pointerEvents: 'none' }}
          className="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-80"
        >
          <source src="/videos/parsu-sunrise720.mp4" type="video/mp4" />
        </video>
        <div className="absolute inset-0 bg-gradient-to-t from-surface-900/80 via-black/40 to-black/20"></div>
        
        <div className="relative z-10 p-6 flex flex-col items-center transform transition-transform duration-500 hover:scale-[1.02]">
          <h1 className="text-4xl sm:text-5xl md:text-6xl font-bold text-white drop-shadow-2xl tracking-tight leading-tight">
            Welcome to <br className="hidden sm:block" />
            Partido State University
          </h1>
          <p className="text-surface-300 mt-4 text-base sm:text-lg font-medium drop-shadow-md">
            Mus Nak'ta Mga Partidoanon
          </p>
        </div>
      </div>

      {/* Stats Grid */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <StatsCard
          icon={<HiOutlineClipboardList className="w-6 h-6" />}
          label="Total Applicants"
          value={0}
          color="primary"
          delay={0}
        />
        <StatsCard
          icon={<HiOutlineIdentification className="w-6 h-6" />}
          label="Enrolled Students"
          value={0}
          color="accent"
          delay={100}
        />
        <StatsCard
          icon={<HiOutlineCalendar className="w-6 h-6" />}
          label="Class Schedules"
          value={0}
          color="violet"
          delay={200}
        />
        <StatsCard
          icon={<HiOutlineAcademicCap className="w-6 h-6" />}
          label="Instructors"
          value={0}
          color="cyan"
          delay={300}
        />
      </div>

      {/* Quick Links */}
      <div style={{ opacity: 0, animation: 'fade-in 0.5s ease-out 0.3s forwards' }}>
        <h2 className="text-lg font-semibold text-white px-1 mb-3">Quick Actions</h2>
        <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
          {quickLinks.map((link) => (
            <Link
              key={link.path}
              to={link.path}
              className="glass-card p-4 flex items-center gap-4 group hover:scale-[1.02] transition-all duration-200"
            >
              <div className={`p-3 rounded-xl bg-gradient-to-br ${link.color} shadow-lg`}>
                <span className="text-white">{link.icon}</span>
              </div>
              <div className="flex-1">
                <p className="font-semibold text-white text-sm">{link.label}</p>
                <p className="text-surface-500 text-xs mt-0.5">{link.desc}</p>
              </div>
              <HiOutlineArrowRight className="w-4 h-4 text-surface-600 group-hover:text-primary-400 group-hover:translate-x-1 transition-all duration-200" />
            </Link>
          ))}
        </div>
      </div>
    </div>
  );
}
