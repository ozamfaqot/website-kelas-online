const express = require("express");
const router = express.Router();

const coursesHandler = require("./handler/courses");
const verifyToken = require("../middlewares/verifyToken");
const role = require("../middlewares/permission");

// public
router.get("/", coursesHandler.getAll);
router.get("/:id", coursesHandler.get);

// private
router.post("/", verifyToken, role("admin"), coursesHandler.create);
router.put(
  "/:id",
  verifyToken,
  role("admin", "student"),
  coursesHandler.update
);
router.delete("/:id", verifyToken, role("admin"), coursesHandler.destroy);

module.exports = router;
