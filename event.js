const container = document.getElementById("events-container");
const today = new Date();
const options = { year: "numeric", month: "long", day: "numeric" };
const formattedDate = today.toLocaleDateString("en-US", options);

document.getElementById("calendar-date").textContent = formattedDate;

function createEventCard(event) {
  const progress = Math.round((event.registered / event.capacity) * 100);
  const card = document.createElement("div");
  card.className = "event-card";
  card.innerHTML = `
    <span class="tag">${event.tag}</span>
    <h3>${event.title}</h3>
    <p>${event.description}</p>
    <p><strong>Date & Time:</strong> ${formattedDate}, ${event.time}</p>
    ${event.location ? `<p><strong>Location:</strong> ${event.location}</p>` : ""}
    <div class="progress-bar">
      <div class="progress-fill" style="width:${progress}%"></div>
    </div>
    <p>${event.registered}/${event.capacity} registered</p>
    <button class="register-btn">Register Now</button>
  `;
  return card;
}

fetch("get_event.php")
  .then(response => response.json())
  .then(events => {
    events.forEach(event => container.appendChild(createEventCard(event)));
  })
  .catch(error => {
    container.innerHTML = "<p>Failed to load events.</p>";
    console.error("Error fetching events:", error);
  });