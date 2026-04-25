import { useState } from "react";
import { HiOutlineCalendar, HiOutlineLocationMarker, HiOutlineUserGroup, HiOutlineBookOpen, HiOutlinePlus } from "react-icons/hi";
import StatsCard from "../components/ui/StatsCard";
import DataTable from "../components/ui/DataTable";
import Card from "../components/ui/Card";
import SchedulingForm from "../components/forms/SchedulingForm";

const columns = [
  { key: "courseCode", label: "Code" },
  { key: "courseName", label: "Course Name" },
  { key: "day", label: "Day(s)" },
  { key: "time", label: "Time" },
  { key: "room", label: "Room" },
  { key: "instructor", label: "Instructor" },
  { key: "program", label: "Program" },
  { key: "units", label: "Units" },
];

export default function Scheduling() {
  const [showForm, setShowForm] = useState(false);

  const uniqueRooms = 0;
  const uniqueInstructors = 0;

  return (
    <div className="page-container">
      {/* Page Header */}
      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 animate-fade-in">
        <div className="page-header mb-0">
          <div className="flex items-center gap-3">
            <div className="p-2.5 rounded-xl bg-cyan-500/15">
              <HiOutlineCalendar className="w-6 h-6 text-cyan-400" />
            </div>
            <div>
              <h1 className="page-title">Scheduling</h1>
              <p className="page-subtitle text-base">Manage class schedules, rooms, and instructors</p>
            </div>
          </div>
        </div>
        <button onClick={() => setShowForm(!showForm)} className="btn-primary flex items-center gap-2 self-start">
          <HiOutlinePlus className="w-4 h-4" />
          New Schedule
        </button>
      </div>

      {/* Stats */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <StatsCard
          icon={<HiOutlineBookOpen className="w-5 h-5" />}
          label="Total Classes"
          value={0}
          color="cyan"
          delay={0}
        />
        <StatsCard
          icon={<HiOutlineLocationMarker className="w-5 h-5" />}
          label="Rooms Used"
          value={uniqueRooms}
          color="primary"
          delay={100}
        />
        <StatsCard
          icon={<HiOutlineUserGroup className="w-5 h-5" />}
          label="Instructors"
          value={uniqueInstructors}
          color="violet"
          delay={200}
        />
        <StatsCard
          icon={<HiOutlineCalendar className="w-5 h-5" />}
          label="Total Units"
          value={0}
          color="accent"
          delay={300}
        />
      </div>

      {/* Form */}
      {showForm && (
        <div className="animate-scale-in">
          <Card title="New Schedule" description="Create a new class schedule" icon={<HiOutlinePlus className="w-5 h-5" />}>
            <SchedulingForm onClose={() => setShowForm(false)} />
          </Card>
        </div>
      )}

      {/* Table */}
      <DataTable
        columns={columns}
        data={[]}
        searchPlaceholder="Search by course, instructor, or room..."
      />
    </div>
  );
}