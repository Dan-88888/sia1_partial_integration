const express = require("express");
const cors = require("cors");
const bodyParser = require("body-parser");

const admissionRoutes = require("./routes/admissionRoutes");
const registrationRoutes = require("./routes/registrationRoutes");
const schedulingRoutes = require("./routes/schedulingRoutes");

const app = express();
app.use(cors());
app.use(bodyParser.json());

// API routes
app.use("/gateway/admission", admissionRoutes);
app.use("/gateway/registration", registrationRoutes);
app.use("/gateway/scheduling", schedulingRoutes);

// Start server
app.listen(5000, () => {
  console.log("API Gateway running on port 5000");
});