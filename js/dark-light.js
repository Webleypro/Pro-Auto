(function () {
  "use strict";
  let mode = window.localStorage.getItem('mode'),
  root = document.getElementsByTagName('html')[0];
  if (mode !== null && mode === 'light') {
    root.setAttribute("data-bs-theme", "light");
  } else {
    root.removeAttribute("data-bs-theme", "light");
  }

  var t, e, r, a, n, o;
  null !== (e = document.querySelector('[data-bs-toggle="mode"]')) &&
  ((t = e.querySelector("#theme-mode-btn")),
    "light" === mode ? (root.setAttribute("data-bs-theme", "light"), (t.checked = !0)) : (root.removeAttribute("data-bs-theme", "light"), (t.checked = !1)),
    e.addEventListener("click", function (e) {
      t.checked ? (root.setAttribute("data-bs-theme", "light"), window.localStorage.setItem("mode", "light")) : (root.removeAttribute("data-bs-theme", "light"), window.localStorage.setItem("mode", "dark"));
    }))
})(jQuery);