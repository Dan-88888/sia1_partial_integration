import { useState } from "react";
import { HiOutlineClipboardList, HiOutlineCheckCircle, HiOutlineClock, HiOutlineXCircle, HiOutlinePlus } from "react-icons/hi";
import StatsCard from "../components/ui/StatsCard";
import DataTable from "../components/ui/DataTable";
import Card from "../components/ui/Card";
import AdmissionForm from "../components/forms/AdmissionForm";

const columns = [
  { key: "id", label: "Student ID" },
  { key: "name", label: "Full Name" },
  { key: "email", label: "Email" },
  { key: "program", label: "Program" },
  { key: "gwa", label: "GWA" },
  { key: "status", label: "Status" },
];

export default function Admission() {
  const [showForm, setShowForm] = useState(false);

  return (
    <div className="page-container">
      {/* Page Header */}
      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 animate-fade-in">
        <div className="page-header mb-0">
          <div className="flex items-center gap-3">
            <div className="p-2.5 rounded-xl bg-primary-500/15">
              <HiOutlineClipboardList className="w-6 h-6 text-primary-400" />
            </div>
            <div>
              <h1 className="page-title">Admission</h1>
              <p className="page-subtitle text-base">Review and manage student applications</p>
            </div>
          </div>
        </div>
        <button onClick={() => setShowForm(!showForm)} className="btn-primary flex items-center gap-2 self-start">
          <HiOutlinePlus className="w-4 h-4" />
          New Application
        </button>
      </div>

      {/* Stats */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <StatsCard
          icon={<HiOutlineClipboardList className="w-5 h-5" />}
          label="Total Applicants"
          value={0}
          color="primary"
          delay={0}
        />
        <StatsCard
          icon={<HiOutlineCheckCircle className="w-5 h-5" />}
          label="Approved"
          value={0}
          color="accent"
          delay={100}
        />
        <StatsCard
          icon={<HiOutlineClock className="w-5 h-5" />}
          label="Pending"
          value={0}
          color="amber"
          delay={200}
        />
        <StatsCard
          icon={<HiOutlineXCircle className="w-5 h-5" />}
          label="Rejected"
          value={0}
          color="rose"
          delay={300}
        />
      </div>

      {/* Form */}
      {showForm && (
        <div className="animate-scale-in">
          <Card title="New Application" description="Fill out the admission form" icon={<HiOutlinePlus className="w-5 h-5" />}>
            <AdmissionForm onClose={() => setShowForm(false)} />
          </Card>
        </div>
      )}

      {/* Table */}
      <DataTable
        columns={columns}
        data={[]}
        searchPlaceholder="Search applicants by name, program, or ID..."
      />
    </div>
  );
}