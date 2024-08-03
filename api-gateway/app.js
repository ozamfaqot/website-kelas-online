const express = require("express");
const path = require("path");
const cookieParser = require("cookie-parser");
const logger = require("morgan");

require("dotenv").config();

const indexRouter = require("./routes/index");
const usersRouter = require("./routes/users");
const coursesRouter = require("./routes/courses");
const mediaRouter = require("./routes/media");
const orderPaymentsRouter = require("./routes/orderPayments");
const refreshTokensRouter = require("./routes/refreshTokens");
const mentorsRouter = require("./routes/mentors");
const chaptersRouter = require("./routes/chapters");
const lessonsRouter = require("./routes/lessons");
const imageCoursesRouter = require("./routes/image-courses");
const myCoursesRouter = require("./routes/my-courses");
const reviewsRouter = require("./routes/reviews");
const webhookRouter = require("./routes/webhook");

const verifyToken = require("./middlewares/verifyToken");
const role = require("./middlewares/permission");

const app = express();

app.use(logger("dev"));
app.use(express.json({ limit: "50mb" }));
app.use(express.urlencoded({ extended: false, limit: "50mb" }));
app.use(cookieParser());
app.use(express.static(path.join(__dirname, "public")));

app.use("/", indexRouter);
app.use("/users", usersRouter);
app.use("/courses", coursesRouter);
app.use("/mentors", verifyToken, role("admin"), mentorsRouter);
app.use("/chapters", verifyToken, role("admin"), chaptersRouter);
app.use("/lessons", verifyToken, role("admin"), lessonsRouter);
app.use("/image-courses", verifyToken, role("admin"), imageCoursesRouter);
app.use("/my-courses", verifyToken, role("admin", "student"), myCoursesRouter);
app.use("/reviews", verifyToken, role("admin", "student"), reviewsRouter);
// app.use("/media", verifyToken, role("admin", "student"), mediaRouter);
app.use("/media", mediaRouter);
app.use("/orders", verifyToken, role("admin", "student"), orderPaymentsRouter);
// app.use("/payments", paymentsRouter);
app.use("/refresh-tokens", refreshTokensRouter);
app.use("/webhook", webhookRouter);

// app.listen(process.env.PORT, () => {
//   console.log(`Running on server ${process.env.PORT}`);
// });

module.exports = app;
