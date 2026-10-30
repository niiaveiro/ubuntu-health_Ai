<?php
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Contact';
$sent = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = post_text('name', 100);
    $email = post_text('email', 190);
    $message = post_text('message', 1000);
    if ($name === null || $name === '' || $email === null || !filter_var($email, FILTER_VALIDATE_EMAIL) || $message === null || $message === '') {
        $error = 'Please complete all fields with a valid email address.';
    } else {
        try {
            $stmt = db()->prepare('INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)');
            $stmt->execute([$name, $email, $message]);
            $sent = true;
        } catch (PDOException $ex) {
            error_log('Ubuntu Health AI could not save a contact message: ' . $ex->getMessage());
            $error = 'Message could not be saved because of a database error. Please try again later.';
        } catch (RuntimeException $ex) {
            $error = 'Message could not be saved because database configuration or connection is unavailable.';
        }
    }
}
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-heading"><span class="eyebrow">GET IN TOUCH</span><h1>Contact the project team</h1><p>Use this form for project feedback, suggestions, or technical issues — not for urgent medical help. Avoid including private health details or identifying information you do not need to share.</p></section>
<?php if($sent): ?><div class="result-panel info"><strong>Message saved</strong><p>Thank you for your feedback.</p></div><?php endif; ?><?php if($error): ?><div class="result-panel danger"><?=e($error)?></div><?php endif;?>
<form class="form-card" method="post"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><label for="name">Name</label><input id="name" name="name" maxlength="100" required><label for="email">Email</label><input id="email" type="email" name="email" maxlength="190" required><label for="message">Message</label><textarea id="message" name="message" rows="5" maxlength="1000" required></textarea><button class="btn btn-primary">Send feedback</button></form>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
