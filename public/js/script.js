const toggleNavbar = () => {
    var x = document.getElementById("header-links");
    if (x.style.display === "flex") {
      x.style.display = "none";
    } else {
      x.style.display = "flex";
    }
}

const darkColor = "rgb(26, 26, 65)";
const lightColor = "rgb(238, 238, 238)"

let text = localStorage.getItem("text");
let background = localStorage.getItem("background");
let r = document.querySelector(':root');

if (text != null && background != null) {
    localStorage.setItem("text", text);
    localStorage.setItem("background", background);
    r.style.setProperty("--text", text);
    r.style.setProperty("--background", background);
} else {
    localStorage.setItem("text", darkColor);
    localStorage.setItem("background", lightColor);
    r.style.setProperty("--text", darkColor);
    r.style.setProperty("--background", lightColor);
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
        textValue = lightColor;
        r.style.setProperty("--text", textValue);
        localStorage.setItem("text", textValue);
        backgroundValue = darkColor;
        r.style.setProperty("--background", backgroundValue);
        localStorage.setItem("background", backgroundValue);
    } else if (theme == "light") {
        textValue = darkColor;
        r.style.setProperty("--text", textValue);
        localStorage.setItem("text", textValue);
        backgroundValue = lightColor;
        r.style.setProperty("--background", backgroundValue);
        localStorage.setItem("background", backgroundValue);
    }
};