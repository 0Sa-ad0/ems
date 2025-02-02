document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".register-btn").forEach((button) => {
    button.addEventListener("click", function () {
      let eventId = this.getAttribute("data-event-id");
      let btn = this;

      btn.disabled = true;
      btn.textContent = "Registering...";

      fetch("../events/register.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "event_id=" + encodeURIComponent(eventId),
      })
        .then((response) => response.json())
        .then((data) => {
          alert(data.message);
          if (data.status === "success") {
            location.reload();
          } else {
            btn.disabled = false;
            btn.textContent = "Register";
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          alert("Registration failed. Please try again.");
          btn.disabled = false;
          btn.textContent = "Register";
        });
    });
  });

  let searchBox = document.querySelector("#searchBox");
  if (searchBox) {
    searchBox.addEventListener("keyup", function () {
      let query = this.value.trim();
      if (query.length < 2) return;

      fetch("../events/search.php?query=" + encodeURIComponent(query))
        .then((response) => response.json())
        .then((data) => {
          let results = document.querySelector("#searchResults");
          results.innerHTML = "";

          if (data.length === 0) {
            results.innerHTML = "<li>No events found</li>";
          } else {
            data.forEach((event) => {
              let li = document.createElement("li");
              li.textContent = event.name + " - " + event.description;
              results.appendChild(li);
            });
          }
        })
        .catch((error) => console.error("Search error:", error));
    });
  }
});
