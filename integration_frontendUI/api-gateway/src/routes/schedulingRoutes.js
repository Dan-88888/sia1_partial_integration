const express = require("express");
const router = express.Router();
const axios = require("axios");

const SCHED_URL = "http://localhost:4003";

router.get("/classes", async (req, res) => {
  try {
    const response = await axios.get(`${SCHED_URL}/classes`);
    res.json(response.data);
  } catch (err) {
    res.status(500).json({ error: "Scheduling service unavailable" });
  }
});

module.exports = router;