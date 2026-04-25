import axios from "axios";

const API_BASE = "http://localhost:5000/api"; // Node.js API Gateway

export const getApplicants = async () => {
  const res = await axios.get(`${API_BASE}/admission/applicants`);
  return res.data;
};

export const registerStudent = async (student) => {
  const res = await axios.post(`${API_BASE}/registration/enroll`, student);
  return res.data;
};