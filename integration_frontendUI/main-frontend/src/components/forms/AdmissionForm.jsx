import { useState } from "react";
import { HiOutlinePlus } from "react-icons/hi";

export default function AdmissionForm({ onClose }) {
  const [formData, setFormData] = useState({
    name: "",
    email: "",
    program: "",
    previousSchool: "",
    gwa: "",
  });

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    // Would call API here
    alert("Application submitted successfully!");
    setFormData({ name: "", email: "", program: "", previousSchool: "", gwa: "" });
    if (onClose) onClose();
  };

  return (
    <form onSubmit={handleSubmit} className="space-y-5">
      <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
          <label className="input-label">Full Name</label>
          <input
            type="text"
            name="name"
            value={formData.name}
            onChange={handleChange}
            placeholder="e.g. Juan Dela Cruz"
            className="input-field"
            required
          />
        </div>
        <div>
          <label className="input-label">Email Address</label>
          <input
            type="email"
            name="email"
            value={formData.email}
            onChange={handleChange}
            placeholder="e.g. juan@email.com"
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
          <label className="input-label">Previous School</label>
          <input
            type="text"
            name="previousSchool"
            value={formData.previousSchool}
            onChange={handleChange}
            placeholder="e.g. ABC National High School"
            className="input-field"
          />
        </div>
        <div>
          <label className="input-label">GWA / Grade Average</label>
          <input
            type="text"
            name="gwa"
            value={formData.gwa}
            onChange={handleChange}
            placeholder="e.g. 1.50"
            className="input-field"
          />
        </div>
      </div>
      <div className="flex items-center gap-3 pt-2">
        <button type="submit" className="btn-primary flex items-center gap-2">
          <HiOutlinePlus className="w-4 h-4" />
          Submit Application
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