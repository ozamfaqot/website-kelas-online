const express = require("express");
const router = express.Router();

const chaptersHandler = require("./handler/chapters");
// const verifyToken = require("../middlewares/verifyToken");

// public
router.get("/", chaptersHandler.getAll);
router.get("/:id", chaptersHandler.get);

// private
router.post("/", chaptersHandler.create);
router.put("/:id", chaptersHandler.update);
router.delete("/:id", chaptersHandler.destroy);

module.exports = router;
