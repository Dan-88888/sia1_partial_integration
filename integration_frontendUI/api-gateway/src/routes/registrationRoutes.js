const express = require("express");
const router = express.Router();
const axios = require("axios");

const REG_URL = "http://localhost:4002";

router.get("/students", async (req, res) => {
  try {
    const response = await axios.get(`${REG_URL}/students`);
    res.json(response.data);
  } catch (err) {
    res.status(500).json({ error: "Registration service unavailable" });
  }
});

module.exports = router;