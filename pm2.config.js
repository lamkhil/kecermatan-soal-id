module.exports = {
  apps: [
    {
      "name": "kecermatan.soal.id:8001",
      "script": "artisan",
      "args": ["octane:start", "--port=8001", "--watch"],
      "interpreter" : "php",
    },
  ],
};

