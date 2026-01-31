// assets/js/theme.js
const themeSelect = document.getElementById("themeSelect");
const html = document.documentElement;

function applyTheme(theme) {
  if (theme === "system") {
    const systemDark = window.matchMedia(
      "(prefers-color-scheme: dark)"
    ).matches;
    html.setAttribute("data-theme", systemDark ? "dark" : "light");
  } else {
    html.setAttribute("data-theme", theme);
  }
}

// Init
const savedTheme = localStorage.getItem("theme") || "system";
applyTheme(savedTheme);

// Listen for selector change if it exists (in settings page)
if (themeSelect) {
  themeSelect.value = savedTheme;
  themeSelect.addEventListener("change", (e) => {
    const val = e.target.value;
    localStorage.setItem("theme", val);
    applyTheme(val);
  });
}

// Listen for system changes
window
  .matchMedia("(prefers-color-scheme: dark)")
  .addEventListener("change", (e) => {
    if (localStorage.getItem("theme") === "system") {
      applyTheme("system");
    }
  });
