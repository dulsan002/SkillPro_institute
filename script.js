function toggleMenu() {
  const navLinks = document.getElementById("navLinks");
  const toggleBtn = document.querySelector(".menu-toggle");
  navLinks.classList.toggle("show");
  toggleBtn.classList.toggle("active");
}

function login() {
  window.location.href = "/skillpro/login.html";
}

function viewAllCourses() {
  window.location.href = "/skillpro/course.html";
}

function viewDetails(courseName) {
  const encoded = encodeURIComponent(courseName);
  window.location.href = `course-details.html?course=${encoded}`;
}

function viewAllEvents() {
  window.location.href = "/skillpro/event.html";
}

function learnMore(branchName) {
  alert(`More info about ${branchName} coming soon!`);
}

const branches = [
  { name: "Colombo Campus", description: "State-of-the-art facilities with modern equipment and experienced instructors." },
  { name: "Kandy Campus", description: "State-of-the-art facilities with modern equipment and experienced instructors." },
  { name: "Matara Campus", description: "State-of-the-art facilities with modern equipment and experienced instructors." }
];

document.addEventListener("DOMContentLoaded", () => {
  // Render branches
  const branchContainer = document.getElementById("branchContainer");
  if (branchContainer) {
    branches.forEach(branch => {
      const card = document.createElement("div");
      card.className = "branch-card";
      card.innerHTML = `
        <div class="icon">📍</div>
        <h3>${branch.name}</h3>
        <p>${branch.description}</p>
        <button onclick="learnMore('${branch.name}')">Learn More</button>
      `;
      branchContainer.appendChild(card);
    });
  }

  // Buttons
  const btnFilled = document.querySelector('.btn-filled');
  const btnOutline = document.querySelector('.btn-outline');

  if (btnFilled) btnFilled.addEventListener('click', () => alert('Redirecting to application form...'));
  if (btnOutline) btnOutline.addEventListener('click', () => alert('Opening contact page...'));
});
