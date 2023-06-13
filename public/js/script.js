const toggleNavbar = () => {
    var x = document.getElementById("header-links");
    if (x.style.display === "flex") {
      x.style.display = "none";
    } else {
      x.style.display = "flex";
    }
}

let text = localStorage.getItem("text");
let background = localStorage.getItem("background");
let r = document.querySelector(':root');

if (text != null && background != null) {
    localStorage.setItem("text", text);
    localStorage.setItem("background", background);
    r.style.setProperty("--text", text);
    r.style.setProperty("--background", background);
} else {
    localStorage.setItem("text", "black");
    localStorage.setItem("background", "white");
    r.style.setProperty("--text", "black");
    r.style.setProperty("--background", "white");
}

setInterval(() => {
    let x = document.getElementById("header-links");
    if (document.body.clientWidth > 768) {
        x.style.display = "flex";
    }
}, 250);

const changeTheme = (theme) => {

    rs = getComputedStyle(r);

    let textValue = rs.getPropertyValue('--text');
    let backgroundValue = rs.getPropertyValue('--background');

    if (theme == "dark") {
        r.style.setProperty("--text", "white");
        textValue = "white";
        localStorage.setItem("text", textValue);
        r.style.setProperty("--background", "black");
        backgroundValue = "black";
        localStorage.setItem("background", backgroundValue);
    } else if (theme == "light") {
        r.style.setProperty("--text", "black");
        textValue = "black";
        localStorage.setItem("text", textValue);
        r.style.setProperty("--background", "white");
        backgroundValue = "white";
        localStorage.setItem("background", backgroundValue);
    }
};