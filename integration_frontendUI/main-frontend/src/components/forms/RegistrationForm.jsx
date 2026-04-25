import { useState } from "react";
import { HiOutlinePlus } from "react-icons/hi";

export default function RegistrationForm({ onClose }) {
  const [formData, setFormData] = useState({
    studentId: "",
    name: "",
    program: "",
    yearLevel: "",
    semester: "",
  });

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    alert("Registration submitted successfully!");
    setFormData({ studentId: "", name: "", program: "", yearLevel: "", semester: "" });
    if (onClose) onClose();
  };

  return (
    <form onSubmit={handleSubmit} className="space-y-5">
      <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
          <label className="input-label">Student ID</label>
          <input
            type="text"
            name="studentId"
            value={formData.studentId}
            onChange={handleChange}
            placeholder="e.g. 2024-001"
            className="input-field"
            required
          />
        </div>
        <div>
          <label className="input-label">Full Name</label>
          <input
            type="text"
            name="name"
            value={formData.name}
            onChange={handleChange}
            placeholder="e.g. Maria Santos"
            className="input-field"
            required
          />
        </div>
        <div>
          <label className="input-label">Program</label>
          <select name="program" value={formData.program} onChange={handleChange} className="input-field" required>
            <option value="">Select a program</option>
            <option value="BSIT">BSIT — Information Technology</option>
            <option value="BSCS">BSCS — Computer Science</option>
            <option value="BSEd">BSEd — Education</option>
            <option value="BSA">BSA — Accountancy</option>
          </select>
        </div>
        <div>
          <label className="input-label">Year Level</label>
          <select name="yearLevel" value={formData.yearLevel} onChange={handleChange} className="input-field" required>
            <option value="">Select year level</option>
            <option value="1st Year">1st Year</option>
            <option value="2nd Year">2nd Year</option>
            <option value="3rd Year">3rd Year</option>
            <option value="4th Year">4th Year</option>
          </select>
        </div>
        <div>
          <label className="input-label">Semester</label>
          <select name="semester" value={formData.semester} onChange={handleChange} className="input-field" required>
            <option value="">Select semester</option>
            <option value="1st Semester">1st Semester</option>
            <option value="2nd Semester">2nd Semester</option>
            <option value="Summer">Summer</option>
          </select>
        </div>
      </div>
      <div className="flex items-center gap-3 pt-2">
        <button type="submit" className="btn-primary flex items-center gap-2">
          <HiOutlinePlus className="w-4 h-4" />
          Enroll Student
        </button>
        {onClose && (
          <button type="button" onClick={onClose} className="btn-secondary">
            Cancel
          </button>
        )}
      </div>
    </form>
  );
}