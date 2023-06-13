const toggleNavbar = () => {
    var x = document.getElementById("header-links");
    if (x.style.display === "flex") {
      x.style.display = "none";
    } else {
      x.style.display = "flex";
    }
}

setInterval(() => {
    let x = document.getElementById("header-links");
    if (document.body.clientWidth > 768) {
        x.style.display = "flex";
    }
}, 250);