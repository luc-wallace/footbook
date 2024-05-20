const seatSelects = document.getElementsByClassName("button")
let selected = seatSelects[0].value
seatSelects[0].classList.add("selected")

for (const el of seatSelects) {
  el.addEventListener("click", (e) => {
    for (const el of seatSelects) {
      el.classList.remove("selected")
      el.innerText = "Select"
    }
    e.target.classList.add("selected")
    e.target.innerText = "Selected"
    selected = e.target.value
    console.log(selected)
  })
}

const rowSelects = document.getElementsByClassName("row")
let selectedEl
for (const el of rowSelects) {
  if (el.classList.contains("taken")) {
    continue
  }
  el.addEventListener("click", (e) => {
    if (selectedEl) {
      selectedEl.classList.remove("selected")
    }
    e.target.classList.add("selected")
    selectedEl = e.target
  })
}

const firstNameInput = document.getElementById("first-name")
const lastNameInput = document.getElementById("last-name")
const terms = document.getElementById("terms")
const purchase = document.getElementById("buy")
purchase.addEventListener("click", async () => {
  const firstName = firstNameInput.value
  const lastName = lastNameInput.value
  if (!selectedEl) {
    alert("You must select a row.")
    return
  }
  if (!selectedEl) {
    alert("You must enter first name.")
    return
  }
  if (!selectedEl) {
    alert("You must enter last name.")
    return
  }
  if (!terms.checked) {
    alert("You must agree to the terms and conditions.")
    return
  }

  const data = new URLSearchParams()
  data.append("firstName", firstName)
  data.append("lastName", lastName)
  data.append("row", selectedEl.innerText)
  data.append("seatType", selected)

  const urlParams = new URLSearchParams(window.location.search)

  data.append("homeTeam", urlParams.get("home"))
  data.append("awayTeam", urlParams.get("away"))

  const res = await fetch("/bookings.php", {
    method: "POST",
    body: data,
  })
  const msg = await res.json()
  alert(msg["message"])
  if (res.status == 201) {
    window.location = "/"
  }
})
