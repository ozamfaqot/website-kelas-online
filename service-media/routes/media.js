const express = require("express");
const router = express.Router();
const isBase64 = require("is-base64");
const base64Img = require("base64-img");
const { Media } = require("../models");
const fs = require("fs");

// is-base64
// function isBase64(str) {
//   var notBase64 = /[^A-Z0-9+\/=]/i;
//   const isString = typeof str === "string" || str instanceof String;

//   if (!isString) {
//     let invalidType;
//     if (str === null) {
//       invalidType = "null";
//     } else {
//       invalidType = typeof str;
//       if (
//         invalidType === "object" &&
//         str.constructor &&
//         str.constructor.hasOwnProperty("name")
//       ) {
//         invalidType = str.constructor.name;
//       } else {
//         invalidType = `a ${invalidType}`;
//       }
//     }
//     throw new TypeError(`Expected string but received ${invalidType}.`);
//   }

//   const len = str.length;
//   if (!len || len % 4 !== 0 || notBase64.test(str)) {
//     return false;
//   }
//   const firstPaddingChar = str.indexOf("=");
//   return (
//     firstPaddingChar === -1 ||
//     firstPaddingChar === len - 1 ||
//     (firstPaddingChar === len - 2 && str[len - 1] === "=")
//   );
// }

router.get("/", async (req, res) => {
  const media = await Media.findAll({
    attributes: ["id", "image"],
  });

  console.log(media);

  const mappedMedia = media.map((m) => {
    m.image = `${req.get("host")}/${m.image}`;
    return m;
  });

  console.log(mappedMedia);

  return res.json({
    status: "success",
    data: mappedMedia,
  });
});

router.post("/", (req, res) => {
  const image = req.body.image;
  // console.log(image);

  // cek image apakah base64
  if (!isBase64(image, { mimeRequired: true })) {
    return res.status(400).json({ status: "error", message: "Invalid base64" });
  }

  // res.send("ok");

  // kalo iya base64, maka upload
  base64Img.img(image, "./public/images", Date.now(), async (err, filepath) => {
    if (err) {
      return res.status(400).json({ status: "error", message: err.message });
    }

    // filepath itu '/public/images/12312734.png'
    // console.log(filepath);
    const filename = filepath.split("\\").pop();
    // console.log(filename);

    // res.send("ok");
    const media = await Media.create({ image: `images/${filename}` });

    res.json({
      status: "success",
      data: {
        id: media.id,
        image: `${req.get("host")}/images/${filename}`,
      },
    });
  });
});

router.delete("/:id", async (req, res) => {
  const id = req.params.id;
  const media = await Media.findByPk(id);

  if (!media) {
    return res
      .status(404)
      .json({ status: "error", message: "image not found" });
  }

  fs.unlink(`./public/${media.image}`, async (error) => {
    if (error) {
      return res.status(400).json({ status: "error", message: error.message });
    }

    await media.destroy();

    return res.json({
      status: "success",
      message: "Image deleted successfully",
    });
  });
});

module.exports = router;
