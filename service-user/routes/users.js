const express = require("express");
const router = express.Router();

const usersHandler = require("./handler/users");

/* GET users listing. */
// router.get("/", function (req, res, next) {
//   res.send("respond with a resource");
// });

router.post("/register", usersHandler.register);
router.post("/login", usersHandler.login);
router.post("/logout", usersHandler.logout);
router.put("/:id", usersHandler.update);
router.get("/:id", usersHandler.getUser);
router.get("/", usersHandler.getUsers);
// router.delete("/:id", usersHandler.logout);

module.exports = router;
