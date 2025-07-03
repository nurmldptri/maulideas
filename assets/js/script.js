// script.js

document.addEventListener("DOMContentLoaded", function () {
  console.log("Halaman sudah dimuat!");

  const buttons = document.querySelectorAll(".btn-pink");
  buttons.forEach(function (btn) {
    btn.addEventListener("click", function () {
      btn.classList.add("clicked");
      setTimeout(() => btn.classList.remove("clicked"), 300);
    });
  });
});
