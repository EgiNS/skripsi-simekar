// sidenav transition-burger

var sidenav = document.querySelector("aside");
var sidenav_trigger = document.querySelector("[sidenav-trigger]");
var sidenav_close_button = document.querySelector("[sidenav-close]");
var burger = sidenav_trigger.firstElementChild;
var top_bread = burger.firstElementChild;
var bottom_bread = burger.lastElementChild;

sidenav_trigger.addEventListener("click", function () {
  console.log("click")
  // Toggle tampilan close button
  sidenav_close_button.classList.toggle("hidden");

  console.log(sidenav.style.transform)
  // Cek apakah sidenav sudah berada di posisi translateX(0)
  if (sidenav.style.transform === "translateX(0px)") {
    console.log("1")
    // Jika ya, sembunyikan dengan translateX(-100%) untuk menutup sidenav
    sidenav.style.transform = "translateX(-100%)";
  } else {
    console.log("2")
    // Jika tidak, tampilkan dengan translateX(0) untuk membuka sidenav
    sidenav.style.transform = "translateX(0)";
  }

  // Toggle transisi hamburger bread (untuk ikon burger)
  if (page == "rtl") {
    top_bread.classList.toggle("-translate-x-[5px]");
    bottom_bread.classList.toggle("-translate-x-[5px]");
  } else {
    top_bread.classList.toggle("translate-x-[5px]");
    bottom_bread.classList.toggle("translate-x-[5px]");
  }
});

sidenav_close_button.addEventListener("click", function () {
  // Trigger click pada sidenav_trigger untuk menutup sidenav saat tombol close diklik
  sidenav_trigger.click();
});

// Menutup sidenav jika klik di luar sidebar
window.addEventListener("click", function (e) {
  if (!sidenav.contains(e.target) && !sidenav_trigger.contains(e.target)) {
    // Pastikan hanya menutup jika sidebar terbuka
    if (sidenav.style.transform === "translateX(0)") {
      sidenav_trigger.click();
    }
  }
});

