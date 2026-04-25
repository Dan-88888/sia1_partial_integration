import { HiOutlineLocationMarker } from "react-icons/hi";

// Default placeholder for missing images
const defaultCampusImage = "https://images.unsplash.com/photo-1541339907198-e08756dedf3f?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80";

const campusesData = [
  { id: 1, name: "Caramoan", imageUrl: "/images/campuses/3.jpg", fallback: defaultCampusImage },
  { id: 2, name: "Goa", imageUrl: "/images/campuses/2.jpg", fallback: defaultCampusImage },
  { id: 3, name: "Lagonoy", imageUrl: "/images/campuses/1.jpg", fallback: defaultCampusImage },
  { id: 4, name: "Sagñay", imageUrl: "/images/campuses/4.jpg", fallback: defaultCampusImage },
  { id: 5, name: "Salogon", imageUrl: "/images/campuses/5.jpg", fallback: defaultCampusImage },
  { id: 6, name: "San Jose", imageUrl: "/images/campuses/6.jpg", fallback: defaultCampusImage },
  { id: 7, name: "Tinambac", imageUrl: "/images/campuses/7.jpg", fallback: defaultCampusImage },
];

export default function Campuses() {
  return (
    <div className="page-container">
      {/* Page Header */}
      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 animate-fade-in mb-8">
        <div className="page-header mb-0">
          <div className="flex items-center gap-3">
            <div className="p-2.5 rounded-xl bg-orange-500/15">
              <HiOutlineLocationMarker className="w-6 h-6 text-orange-400" />
            </div>
            <div>
              <h1 className="page-title">ParSU Campuses</h1>
              <p className="page-subtitle text-base">View and manage information across all ParSU locations</p>
            </div>
          </div>
        </div>
      </div>

      {/* Campuses Grid */}
      <div className="flex flex-wrap justify-center gap-6">
        {campusesData.map((campus) => (
          <div 
            key={campus.id}
            className="group relative w-full sm:w-[calc(50%-12px)] lg:w-[calc(33.333%-16px)] h-72 rounded-2xl overflow-hidden cursor-pointer shadow-lg border border-surface-700/30 transition-all duration-300 hover:shadow-2xl hover:shadow-orange-500/10 hover:-translate-y-1"
          >
            {/* Background Image */}
            <div className="absolute inset-0 bg-surface-800">
              <img 
                src={campus.imageUrl} 
                alt={`${campus.name} Campus`}
                className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                onError={(e) => {
                  e.target.src = campus.fallback;
                }}
              />
            </div>
            
            {/* Gradient Overlay */}
            <div className="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent opacity-80 group-hover:opacity-100 transition-opacity duration-300"></div>

            {/* Content */}
            <div className="absolute bottom-0 left-0 p-6 w-full transform transition-transform duration-300 group-hover:translate-y-[-5px]">
              <h3 className="text-2xl font-bold text-white mb-1.5 drop-shadow-md">{campus.name}</h3>
              <div className="flex items-center gap-2 text-sm text-surface-300 group-hover:text-white transition-colors duration-300">
                <span>Learn more</span>
                <span className="transform transition-transform duration-300 group-hover:translate-x-1">→</span>
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
