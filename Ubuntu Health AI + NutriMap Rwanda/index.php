<?php
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Home';
require_once __DIR__ . '/includes/header.php';
?>
<section class="hero">
  <div class="hero-copy">
    <span class="eyebrow">CARE • KNOWLEDGE • COMMUNITY</span>
    <h1>Your health journey starts with <span>clear information.</span></h1>
    <p>Ubuntu Health AI helps you explore trusted health education, understand when to seek care, and request an appointment.</p>
    <div class="hero-actions">
      <a class="btn btn-primary" href="symptom-checker.php">Check symptoms</a>
      <a class="btn btn-light" href="health-library.php">Explore health library</a>
    </div>
    <p class="small-note">For education and navigation only — not a medical diagnosis.</p>
  </div>
  <div class="hero-card">
    <div class="pulse-icon">✚</div>
    <h2>Care begins with listening.</h2>
    <p>Get simple guidance, learn about prevention, and know when professional help matters.</p>
    <div class="status-line"><span></span> Health information, made approachable</div>
  </div>
</section>
<section class="section">
  <div class="section-heading"><div><span class="eyebrow">WHAT YOU CAN DO</span><h2>Support for your next step</h2></div></div>
  <div class="feature-grid">
    <a class="feature-card" href="symptom-checker.php"><div class="feature-icon">⌕</div><h3>Symptom guidance</h3><p>Answer a few questions and receive general next-step guidance, including warning signs.</p><span class="card-link">Start symptom check →</span></a>
    <a class="feature-card" href="chatbot.php"><div class="feature-icon">✧</div><h3>Health assistant</h3><p>Ask common health-education questions and get clear, cautious information.</p><span class="card-link">Ask a question →</span></a>
    <a class="feature-card" href="health-library.php"><div class="feature-icon">▤</div><h3>Health library</h3><p>Explore starter articles on prevention, hydration, sleep, and getting care.</p><span class="card-link">Read articles →</span></a>
    <a class="feature-card" href="appointments.php"><div class="feature-icon">＋</div><h3>Request an appointment</h3><p>Send a request to a health facility. This demo does not confirm a booking.</p><span class="card-link">Make a request →</span></a>
  </div>
</section>
<section class="notice-band"><div><span class="eyebrow">A SAFETY-FIRST APPROACH</span><h2>Technology can guide you. Health professionals provide care.</h2><p>If you think someone is in immediate danger, contact local emergency services or go to the nearest health facility. Do not wait for an online response.</p></div><a class="btn btn-dark" href="about.php">About the project</a></section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
