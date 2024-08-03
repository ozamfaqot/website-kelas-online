const express = require("express");
const router = express.Router();
// const { APP_NAME } = process.env;

/* GET users listing. */
// router.get("/", function (req, res, next) {
//   res.send(`respond with a resource: ${APP_NAME}`);
// });

const userHandler = require("./handler/users");
const verifyToken = require("../middlewares/verifyToken");

router.post("/register", userHandler.register);
router.post("/login", userHandler.login);
router.post("/logout", verifyToken, userHandler.logout);
router.put("/", verifyToken, userHandler.update);
router.get("/", verifyToken, userHandler.getUser);

module.exports = router;
