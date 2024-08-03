const { User, RefreshToken } = require("../../../models");

module.exports = async (req, res) => {
  //   const userId = req.params.id;
  const userId = req.body.user_id;
  //cek apakah ada user deng id tersebut
  const user = await User.findByPk(userId);
  if (!user) {
    return res.status(404).json({
      status: "error",
      message: "User not found",
    });
  }

  //Jika ada usernya maka delete tokennya
  const token = await RefreshToken.destroy({
    where: { user_id: userId },
  });
  //   const token = await RefreshToken.findOne({
  //     where: { user_id: userId },
  //   });

  if (!token) {
    return res.status(404).json({
      status: "error",
      message: "Invalid user token",
    });
  }

  //   await token.destroy();

  return res.json({
    status: "success",
    message: "Refresh token deleted",
  });
};
