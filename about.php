<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SkillPro Institute</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="about.css" />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap"
      rel="stylesheet"
    />
  </head>
  <body>
    <!-- Navbar -->
    <header class="navbar">
      <div class="logo">
        <img src="images/graduate.png" alt="logo" />
        <h2>SkillPro Institute</h2>
      </div>
      <nav class="nav-links" id="navLinks">
        <a href="index.html">Home</a>
        <a href="course.html">Courses</a>
        <a href="event.html">Events</a>
        <a href="about.php">About</a>
        <a href="contact.html">Contact</a>
        <button onclick="login()">Login</button>
      </nav>
      <div class="menu-toggle" onclick="toggleMenu()">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </header>

    <section class="about-section">
      <div class="about-header">
        <h2>About SkillPro Institute</h2>
        <p>
          Empowering individuals with industry-relevant skills for over 15
          years.
        </p>
      </div>

      <div class="about-grid">
        <div class="about-left">
          <div class="stats">
            <div><strong>5,000+</strong><span>Students Graduated</span></div>
            <div><strong>25+</strong><span>Courses Offered</span></div>
            <div><strong>100+</strong><span>Industry Partners</span></div>
            <div><strong>15+</strong><span>Years of Experience</span></div>
          </div>

          <div class="mission-vision">
            <div class="card">
              <h3>Our Mission</h3>
              <p>
                To provide accessible, high-quality vocational education that
                bridges the gap between academic learning and industry
                requirements. We aim to develop skilled professionals who
                contribute meaningfully to Sri Lanka’s economic growth and their
                personal career aspirations.
              </p>
            </div>
            <div class="card">
              <h3>Our Vision</h3>
              <p>
                To be the leading vocational training institute in South Asia,
                recognized for excellence in education, innovation in training
                methodologies, and the success of our graduates across diverse
                industries.
              </p>
            </div>
          </div>
        </div>

        <div class="about-right">
          <img src="images/skillPro.jpg" alt="SkillPro Institute Campus" />
          <img
            src="images/graduation.jpg"
            alt="SkillPro Institute Campus graduation"
          />
        </div>
      </div>
    </section>
    <section class="timeline-section">
      <h2>Our Journey</h2>
      <div class="timeline">
        <div class="timeline-item">
          <div class="year">2009</div>
          <div class="content">
            <h3>SkillPro Institute Founded</h3>
            <p>
              Started with a vision to provide quality vocational education.
            </p>
          </div>
        </div>
        <div class="timeline-item">
          <div class="year">2012</div>
          <div class="content">
            <h3>Kandy Branch Opened</h3>
            <p>Expanded to serve the Central Province.</p>
          </div>
        </div>
        <div class="timeline-item">
          <div class="year">2015</div>
          <div class="content">
            <h3>Industry Partnership Program</h3>
            <p>Established connections with 50+ leading companies.</p>
          </div>
        </div>
        <div class="timeline-item">
          <div class="year">2018</div>
          <div class="content">
            <h3>Matara Branch Opened</h3>
            <p>Extended services to the Southern Province.</p>
          </div>
        </div>
        <div class="timeline-item">
          <div class="year">2020</div>
          <div class="content">
            <h3>Online Learning Platform</h3>
            <p>Launched comprehensive digital learning system.</p>
          </div>
        </div>
      </div>
    </section>

    
      
      <section class="leadership-section">
  <h2>Leadership Team</h2>
  <div class="team-grid">
    <?php
    // ===== Database Connection =====
    $host = "localhost";
    $user = "root";
    $pass = "";
    $dbname = "skillpro";

    $conn = new mysqli($host, $user, $pass, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // ===== Fetch Leadership Team =====
    $sql = "SELECT * FROM leadership_team";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "
            <div class='team-card'
                 data-name='{$row['name']}'
                 data-title='{$row['title']}'
                 data-qual='{$row['qualification']}'
                 data-exp='{$row['experience']}'
                 data-bio='{$row['bio']}'>
              <h3>{$row['name']}</h3>
              <p class='title'>{$row['title']}</p>
              <p class='qual'>{$row['qualification']}</p>
              <p class='exp'>{$row['experience']}</p>
            </div>
            ";
        }
    } else {
        echo '<p>No team members found.</p>';
    }

    $conn->close();
    ?>
  </div>
</section>


<!-- Popup Modal -->
<div id="popupModal" class="popup-modal">
  <div class="popup-content">
    <span class="close-btn">&times;</span>
    <h3 id="popupName"></h3>
    <p><strong>Title:</strong> <span id="popupTitle"></span></p>
    <p><strong>Qualification:</strong> <span id="popupQual"></span></p>
    <p><strong>Experience:</strong> <span id="popupExp"></span></p>
    <p><strong>Bio:</strong> <span id="popupBio"></span></p>
  </div>
</div>

    </section>

    <section class="branch-wrap">
      <div class="branches-section">
        <h2>Our Branches</h2>
        <p class="subtitle">
          Choose from our conveniently located campuses across Sri Lanka.
        </p>
        <div id="branchContainer" class="branch-grid"></div>
      </div>
    </section>

    <section class="promo-banner">
      <div class="banner-content">
        <div class="icon">↑</div>
        <h1>Shape Your Future with SkillPro Institute</h1>
        <p>
          Join Sri Lanka’s leading vocational training institute and unlock your
          career potential.
        </p>
        <div class="highlights">
          <div class="highlight">15+ Years of Excellence</div>
          <div class="highlight">95% Job Placement Rate</div>
          <div class="highlight">Industry-Standard Training</div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
      <div class="footer-grid">
        <div class="footer-column">
          <h3>SkillPro Institute</h3>
          <p>
            Empowering futures through quality vocational education across Sri
            Lanka.
          </p>
        </div>
        <div class="footer-column">
          <h4>Quick Links</h4>
          <ul>
            <li><a href="course.html">Our Courses</a></li>
            <li><a href="event.html">Events</a></li>
            <li><a href="about.html">About Us</a></li>
            <li><a href="contact.html">Contact</a></li>
          </ul>
        </div>
        <div class="footer-column">
          <h4>Our Branches</h4>
          <ul>
            <li>📍 Colombo Campus</li>
            <li>📍 Kandy Campus</li>
            <li>📍 Matara Campus</li>
          </ul>
        </div>
        <div class="footer-column">
          <h4>Contact Info</h4>
          <p>📞 +94 11 234 5678</p>
          <p>✉️ info@skillpro.lk</p>
        </div>
      </div>
      <div class="footer-bottom">
        <p>© 2025 SkillPro Institute. All rights reserved.</p>
      </div>
    </footer>

    <script src="script.js"></script>
    <script src="course.js"></script>
    <script>
document.querySelectorAll(".team-card").forEach(card => {
  card.addEventListener("click", () => {
    document.getElementById("popupName").innerText = card.dataset.name;
    document.getElementById("popupTitle").innerText = card.dataset.title;
    document.getElementById("popupQual").innerText = card.dataset.qual;
    document.getElementById("popupExp").innerText = card.dataset.exp;
    document.getElementById("popupBio").innerText = card.dataset.bio;

    document.getElementById("popupModal").style.display = "block";
  });
});

document.querySelector(".close-btn").onclick = () => {
  document.getElementById("popupModal").style.display = "none";
};

window.onclick = (event) => {
  if (event.target == document.getElementById("popupModal")) {
    document.getElementById("popupModal").style.display = "none";
  }
};
</script>
  </body>
</html>
