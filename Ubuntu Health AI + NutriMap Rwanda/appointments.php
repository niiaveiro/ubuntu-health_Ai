<?php
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Appointment Request';
$success = false; $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 verify_csrf();
 $name = post_text('name', 100);
 $email = post_text('email', 190);
 $phone = post_text('phone', 40);
 $facility = post_text('facility', 150);
 $reason = post_text('reason', 500);
 if ($name === null || $name === '' || $email === null || !filter_var($email, FILTER_VALIDATE_EMAIL) || $phone === null || $facility === null || $facility === '' || $reason === null || $reason === '') {
  $error = 'Please enter your name, a valid email address, facility, and reason for the request.';
 } else {
  try {
   $stmt=db()->prepare('INSERT INTO appointment_requests (name, email, phone, facility, reason) VALUES (?, ?, ?, ?, ?)');
   $stmt->execute([$name,$email,$phone,$facility,$reason]);
   $success=true;
  } catch(PDOException $ex) { error_log('Ubuntu Health AI could not save an appointment request: ' . $ex->getMessage()); $error='The request could not be saved because of a database error. Please try again later or contact the facility directly.'; }
  catch(RuntimeException $ex) { $error='The request could not be saved because database configuration or connection is unavailable.'; }
 }
}
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-heading"><span class="eyebrow">ACCESS TO CARE</span><h1>Request an appointment</h1><p>Send a request for follow-up. This prototype does not contact a clinic automatically or confirm an appointment.</p></section>
<?php if($success): ?><div class="result-panel info"><strong>Request saved</strong><p>Your demo request has been saved. Contact the selected facility directly to confirm availability. Avoid sending urgent cases through this form.</p></div><?php endif; ?>
<?php if($error): ?><div class="result-panel danger"><?= e($error) ?></div><?php endif; ?>
<form class="form-card" method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<div class="form-row"><div><label for="name">Name</label><input id="name" name="name" maxlength="100" required></div><div><label for="email">Email address</label><input id="email" name="email" type="email" maxlength="190" required></div></div>
<label for="phone">Phone (optional)</label><input id="phone" name="phone" maxlength="40">
<label for="facility">Health facility you want to contact</label><input id="facility" name="facility" maxlength="150" placeholder="Enter a facility name" required>
<label for="reason">Reason for the request (avoid highly sensitive details)</label><textarea id="reason" name="reason" rows="4" maxlength="500" required></textarea>
<button class="btn btn-primary" type="submit">Submit request</button><p class="form-note">This demo stores submitted contact details in your local database. Use only test data during development.</p></form>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
