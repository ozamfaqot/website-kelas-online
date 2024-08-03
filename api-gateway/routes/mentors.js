const express = require("express");
const router = express.Router();

/* GET users listing. */
// router.get("/", function (req, res, next) {
//   res.send(`respond with a resource: ${APP_NAME}`);
// });

const mentorsHandler = require("./handler/mentors");

router.get("/:id", mentorsHandler.get);
router.get("/", mentorsHandler.getAll);
router.post("/", mentorsHandler.create);
router.put("/:id", mentorsHandler.update);
router.delete("/:id", mentorsHandler.destroy);

module.exports = router;
