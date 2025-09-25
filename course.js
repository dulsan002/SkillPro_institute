const container = document.getElementById("courseContainer");
const searchInput = document.getElementById("searchCourse");
const filterCategory = document.getElementById("filterCategory");
const filterDuration = document.getElementById("filterDuration");
const filterLocation = document.getElementById("filterLocation");

let allCourses = [];

// Create a course box element
function createCourseBox(course) {
  const box = document.createElement("div");
  box.className = "course-box";
  box.innerHTML = `
    <h3>${course.title}</h3>
    <p class="category">${course.category}</p>
    <p class="details">📅 Duration: ${course.duration}</p>
    <p class="details">📍 Location: ${course.location}</p>
    <p class="details">📝 ${course.description}</p>
    <div class="btn_wrapper">
      <button class="enroll-btn">Enroll</button>
      <button class="learn-btn">Learn More</button>
    </div>
  `;

  // Enroll button redirects to login
  box.querySelector(".enroll-btn").addEventListener("click", () => {
    window.location.href = "login.html";
  });

  // Learn More button goes to course-details.html with ID
  box.querySelector(".learn-btn").addEventListener("click", () => {
    window.location.href = `course-details.html?id=${course.id}`;
  });

  return box;
}

// Render courses inside the container
function renderCourses(courses) {
  if (!container) return;
  container.innerHTML = "";
  courses.forEach(course => {
    container.appendChild(createCourseBox(course));
  });
}

// Filter courses based on inputs
function filterCourses() {
  const term = searchInput ? searchInput.value.toLowerCase() : "";
  const category = filterCategory ? filterCategory.value : "";
  const duration = filterDuration ? filterDuration.value.toLowerCase() : "";
  const location = filterLocation ? filterLocation.value.toLowerCase() : "";

  const filtered = allCourses.filter(course => {
    const matchTitle = course.title.toLowerCase().includes(term);
    const matchCategory = category === "" || course.category === category;
    const matchDuration = duration === "" || course.duration.toLowerCase().includes(duration);
    const matchLocation = location === "" || course.location.toLowerCase().includes(location);
    return matchTitle && matchCategory && matchDuration && matchLocation;
  });

  renderCourses(filtered);
}

// Load courses from server
function loadCourses() {
  fetch("get_courses.php")
    .then(res => res.json())
    .then(data => {
      allCourses = data;
      renderCourses(allCourses); // initial render
    })
    .catch(err => {
      if (container) container.innerHTML = "<p>⚠️ Failed to load courses.</p>";
      console.error(err);
    });
}

// Attach event listeners only if elements exist
if (searchInput) searchInput.addEventListener("input", filterCourses);
if (filterCategory) filterCategory.addEventListener("change", filterCourses);
if (filterDuration) filterDuration.addEventListener("change", filterCourses);
if (filterLocation) filterLocation.addEventListener("change", filterCourses);

// Load courses on page ready
document.addEventListener("DOMContentLoaded", loadCourses);
