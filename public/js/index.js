for (const el of document.querySelectorAll("[data-href]")) {
  el.addEventListener("click", (e) => {
    window.location = el.dataset.href
  })
}
