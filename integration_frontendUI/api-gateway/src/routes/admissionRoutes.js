const express = require("express");
const router = express.Router();
const axios = require("axios");

// CHANGE this when their backend is running
const ADMISSION_URL = "http://localhost:4001";

router.get("/applicants", async (req, res) => {
  try {
    const response = await axios.get(`${ADMISSION_URL}/applicants`);
    res.json(response.data);
  } catch (err) {
    res.status(500).json({ error: "Admission service unavailable" });
  }
});

module.exports = router;