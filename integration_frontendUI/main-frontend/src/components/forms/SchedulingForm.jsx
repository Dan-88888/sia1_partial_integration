import { useState } from "react";
import { HiOutlinePlus } from "react-icons/hi";

export default function SchedulingForm({ onClose }) {
  const [formData, setFormData] = useState({
    courseCode: "",
    courseName: "",
    day: "",
    time: "",
    room: "",
    instructor: "",
    program: "",
  });

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    alert("Schedule created successfully!");
    setFormData({ courseCode: "", courseName: "", day: "", time: "", room: "", instructor: "", program: "" });
    if (onClose) onClose();
  };

  return (
    <form onSubmit={handleSubmit} className="space-y-5">
      <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
          <label className="input-label">Course Code</label>
          <input
            type="text"
            name="courseCode"
            value={formData.courseCode}
            onChange={handleChange}
            placeholder="e.g. IT101"
            className="input-field"
            required
          />
        </div>
        <div>
          <label className="input-label">Course Name</label>
          <input
            type="text"
            name="courseName"
            value={formData.courseName}
            onChange={handleChange}
            placeholder="e.g. Introduction to Computing"
            className="input-field"
            required
          />
        </div>
        <div>
          <label className="input-label">Day(s)</label>
          <select name="day" value={formData.day} onChange={handleChange} className="input-field" required>
            <option value="">Select day(s)</option>
            <option value="Mon/Wed">Monday / Wednesday</option>
            <option value="Tue/Thu">Tuesday / Thursday</option>
            <option value="Mon/Wed/Fri">Mon / Wed / Fri</option>
            <option value="Fri">Friday Only</option>
            <option value="Sat">Saturday Only</option>
          </select>
        </div>
        <div>
          <label className="input-label">Time</label>
          <select name="time" value={formData.time} onChange={handleChange} className="input-field" required>
            <option value="">Select time slot</option>
            <option value="8:00 AM - 9:30 AM">8:00 AM - 9:30 AM</option>
            <option value="10:00 AM - 11:30 AM">10:00 AM - 11:30 AM</option>
            <option value="1:00 PM - 2:30 PM">1:00 PM - 2:30 PM</option>
            <option value="3:00 PM - 4:30 PM">3:00 PM - 4:30 PM</option>
            <option value="8:00 AM - 11:00 AM">8:00 AM - 11:00 AM (3 hrs)</option>
            <option value="10:00 AM - 11:00 AM">10:00 AM - 11:00 AM (1 hr)</option>
          </select>
        </div>
        <div>
          <label className="input-label">Room</label>
          <input
            type="text"
            name="room"
            value={formData.room}
            onChange={handleChange}
            placeholder="e.g. Room 201 or CL Lab 1"
            className="input-field"
            required
          />
        </div>
        <div>
          <label className="input-label">Instructor</label>
          <input
            type="text"
            name="instructor"
            value={formData.instructor}
            onChange={handleChange}
            placeholder="e.g. Dr. Reyes"
            className="input-field"
            required
          />
        </div>
        <div>
          <label className="input-label">Program</label>
          <select name="program" value={formData.program} onChange={handleChange} className="input-field" required>
            <option value="">Select a program</option>
            <option value="BSIT">BSIT</option>
            <option value="BSCS">BSCS</option>
            <option value="BSEd">BSEd</option>
            <option value="BSA">BSA</option>
            <option value="All">All Programs</option>
          </select>
        </div>
      </div>
      <div className="flex items-center gap-3 pt-2">
        <button type="submit" className="btn-primary flex items-center gap-2">
          <HiOutlinePlus className="w-4 h-4" />
          Create Schedule
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