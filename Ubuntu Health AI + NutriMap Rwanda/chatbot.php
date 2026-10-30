<?php
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Health Assistant';
$answers = [
 'fever' => 'Fever can have many causes. Rest, drink fluids if you can, and monitor how you feel. Seek professional advice if it persists, worsens, or you are worried. Get urgent help for severe symptoms.',
 'water' => 'Water needs vary with age, activity, climate, pregnancy, and health conditions. Drink regularly and more when hot or active unless a clinician has told you to limit fluids.',
 'sleep' => 'A regular sleep schedule, a calm bedtime routine, and reducing caffeine late in the day can support sleep. Ongoing sleep problems may be worth discussing with a health professional.',
 'appointment' => 'You can use the Appointment Request page in this prototype to send a request. It is not a confirmed booking; contact the facility directly to confirm availability.',
 'emergency' => 'If someone has severe difficulty breathing, severe chest pain, unconsciousness, stroke-like signs, or severe bleeding, contact local emergency services or go to the nearest health facility immediately.',
 'medicine' => 'I cannot recommend a medicine or dose for your personal situation. Ask a qualified health professional or pharmacist, especially for children, pregnancy, allergies, or other medicines.',
 'pregnancy' => 'Pregnancy-related questions are best discussed with a qualified maternity professional. Severe pain, heavy bleeding, fainting, or other urgent concerns require immediate medical attention.',
 'default' => 'I can share general health education, but I cannot diagnose or prescribe treatment. Try asking about fever, water, sleep, appointments, medicine safety, pregnancy, or emergency warning signs.'
];
$answer = null;
$inputError = '';
$storageWarning = '';
$answerCategory = 'general';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 verify_csrf();
 $submittedQuestion = post_text('question', 500);
 if ($submittedQuestion === null || $submittedQuestion === '') {
  $inputError = 'Please enter a valid question using standard text.';
 } else {
  $question = mb_strtolower($submittedQuestion);
  if (preg_match('/breath|chest pain|unconscious|stroke|bleed/', $question)) { $answerCategory = 'emergency'; $answer = $answers['emergency']; }
  elseif (str_contains($question,'fever') || str_contains($question,'temperature')) { $answerCategory = 'fever'; $answer = $answers['fever']; }
  elseif (str_contains($question,'water') || str_contains($question,'hydration')) { $answerCategory = 'hydration'; $answer = $answers['water']; }
  elseif (str_contains($question,'sleep')) { $answerCategory = 'sleep'; $answer = $answers['sleep']; }
  elseif (str_contains($question,'appointment') || str_contains($question,'doctor')) { $answerCategory = 'appointment'; $answer = $answers['appointment']; }
  elseif (str_contains($question,'medicine') || str_contains($question,'drug') || str_contains($question,'dose')) { $answerCategory = 'medicine'; $answer = $answers['medicine']; }
  elseif (str_contains($question,'pregnan')) { $answerCategory = 'pregnancy'; $answer = $answers['pregnancy']; }
  else { $answer = $answers['default']; }
  try {
   $stmt = db()->prepare('INSERT INTO chatbot_logs (question, answer_category) VALUES (?, ?)');
   $stmt->execute(['', $answerCategory]);
  } catch (PDOException $ex) {
   error_log('Ubuntu Health AI could not save a chatbot category: ' . $ex->getMessage());
   $storageWarning = 'Your response is shown, but this interaction could not be saved.';
  } catch (RuntimeException $ex) {
   $storageWarning = 'Your response is shown, but database storage is unavailable.';
  }
 }
}
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-heading"><span class="eyebrow">HEALTH ASSISTANT</span><h1>Ask a health question</h1><p>Ask general questions about prevention and finding care. Please do not share private medical records or identifying information.</p></section>
<?php if ($inputError): ?><div class="result-panel danger" role="alert"><?= e($inputError) ?></div><?php endif; ?>
<?php if ($storageWarning): ?><div class="result-panel warning" role="status"><?= e($storageWarning) ?></div><?php endif; ?>
<div class="chat-layout"><div class="chat-card"><div class="chat-top"><div class="assistant-avatar">✧</div><div><strong>Ubuntu Health Assistant</strong><small>Education support · Not a clinician</small></div><span class="online-dot"></span></div>
<?php if ($answer): ?><div class="chat-answer"><strong>Assistant</strong><p><?= e($answer) ?></p></div><?php else: ?><div class="chat-welcome"><div class="assistant-avatar big">✧</div><h2>Hello! How can I help?</h2><p>Try one of the sample questions below, or type a general health question.</p></div><?php endif; ?>
<form method="post" class="chat-form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><label for="question">Your question</label><textarea id="question" name="question" rows="3" maxlength="500" placeholder="Example: What can help me build a healthy sleep routine?" required></textarea><button class="btn btn-primary" type="submit">Send question</button></form>
</div><aside class="suggestions"><h3>Try asking about</h3><p>Common health topics</p><?php foreach (['How can I improve sleep?','What should I know about fever?','How do I request an appointment?','Is it safe to choose medicine myself?'] as $q): ?><button class="suggestion" type="button" onclick="document.getElementById('question').value=<?= json_encode($q) ?>;document.getElementById('question').focus()"><?= e($q) ?> <span>↗</span></button><?php endforeach; ?><div class="mini-warning">This assistant uses a small set of prepared responses. It is not a medically validated AI system.</div></aside></div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
