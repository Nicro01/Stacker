const express = require("express");
const cors = require("cors");
const morgan = require("morgan");
const projectRoutes = require("./Routes/projectRoutes");

const app = express();

app.use(cors());
app.use(morgan("dev"));
app.use(express.json());

app.use("/api", projectRoutes);

app.use((err, req, res, next) => {
  console.error(err.stack);
  res.status(500).json({ error: err.message });
});

const PORT = process.env.PORT || 2025;
app.listen(PORT, () => {
  console.log(`Server running on port ${PORT}`);
});
