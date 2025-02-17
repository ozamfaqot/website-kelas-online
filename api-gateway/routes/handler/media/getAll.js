const { json } = require("express");
const apiAdapter = require("../../apiAdapter");
const { URL_SERVICE_MEDIA } = process.env;
const api = apiAdapter(URL_SERVICE_MEDIA);
// console.log(URL_SERVICE_MEDIA);

module.exports = async (req, res) => {
  //   res.send("ok");
  try {
    const media = await api.get("/media");
    return res.json(media.data);
  } catch (error) {
    if (error.code === "ECONNREFUSED") {
      return res
        .status(500)
        .json({ status: "error", message: "Service Unavailable" });
    }
    const { status, data } = error.response;
    return res.status(status).json(data);
  }
};
