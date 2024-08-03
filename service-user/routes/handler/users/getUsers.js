const { User } = require("../../../models");

module.exports = async (req, res) => {
  const userIds = req.query.user_ids || [];
  //   console.log(userIds);
  const sqlOptions = {
    attributes: ["id", "name", "email", "role", "profession", "avatar"],
  };

  if (userIds.length) {
    sqlOptions.where = {
      id: userIds,
    };
  }

  const users = await User.findAll(sqlOptions);
  //   console.log(users);
  if (!users) {
    return res.status(404).json({
      status: "error",
      message: "Users not found",
    });
  }

  return res.json({
    status: "success",
    data: users,
  });
};
