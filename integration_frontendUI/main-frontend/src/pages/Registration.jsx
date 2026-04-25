import { useState } from "react";
import { HiOutlineIdentification, HiOutlineCheckCircle, HiOutlineClock, HiOutlineExclamation, HiOutlinePlus } from "react-icons/hi";
import StatsCard from "../components/ui/StatsCard";
import DataTable from "../components/ui/DataTable";
import Card from "../components/ui/Card";
import RegistrationForm from "../components/forms/RegistrationForm";

const columns = [
  { key: "id", label: "Reg ID" },
  { key: "studentId", label: "Student ID" },
  { key: "name", label: "Full Name" },
  { key: "program", label: "Program" },
  { key: "yearLevel", label: "Year Level" },
  { key: "semester", label: "Semester" },
  { key: "units", label: "Units" },
  { key: "status", label: "Status" },
];

export default function Registration() {
  const [showForm, setShowForm] = useState(false);

  const enrolled = 0;
  const pending = 0;
  const waitlisted = 0;

  return (
    <div className="page-container">
      {/* Page Header */}
      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 animate-fade-in">
        <div className="page-header mb-0">
          <div className="flex items-center gap-3">
            <div className="p-2.5 rounded-xl bg-violet-500/15">
              <HiOutlineIdentification className="w-6 h-6 text-violet-400" />
            </div>
            <div>
              <h1 className="page-title">Registration</h1>
              <p className="page-subtitle text-base">Enroll students and manage enrollment status</p>
            </div>
          </div>
        </div>
        <button onClick={() => setShowForm(!showForm)} className="btn-primary flex items-center gap-2 self-start">
          <HiOutlinePlus className="w-4 h-4" />
          New Enrollment
        </button>
      </div>

      {/* Stats */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <StatsCard
          icon={<HiOutlineIdentification className="w-5 h-5" />}
          label="Total Registrations"
          value={0}
          color="violet"
          delay={0}
        />
        <StatsCard
          icon={<HiOutlineCheckCircle className="w-5 h-5" />}
          label="Enrolled"
          value={enrolled}
          color="accent"
          delay={100}
        />
        <StatsCard
          icon={<HiOutlineClock className="w-5 h-5" />}
          label="Pending"
          value={pending}
          color="amber"
          delay={200}
        />
        <StatsCard
          icon={<HiOutlineExclamation className="w-5 h-5" />}
          label="Waitlisted"
          value={waitlisted}
          color="rose"
          delay={300}
        />
      </div>

      {/* Form */}
      {showForm && (
        <div className="animate-scale-in">
          <Card title="New Enrollment" description="Register a student for the semester" icon={<HiOutlinePlus className="w-5 h-5" />}>
            <RegistrationForm onClose={() => setShowForm(false)} />
          </Card>
        </div>
      )}

      {/* Table */}
      <DataTable
        columns={columns}
        data={[]}
        searchPlaceholder="Search by student name, ID, or program..."
      />
    </div>
  );
}