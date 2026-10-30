<?php
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Symptom Guidance';
$result = null;
$error = '';
$storageWarning = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $emergencyFlags = ['breathing','chest_pain','unconscious','stroke','severe_bleeding'];
    $symptom = post_text('symptom', 80);
    $duration = post_text('duration', 40);
    $severity = post_text('severity', 30);
    $warningSigns = post_text('warning_signs', 3);
    $allowedSymptoms = ['fever','cough','headache','stomach','skin','other'];
    $allowedDurations = ['today','one_two_days','several_days','getting_worse'];
    $allowedSeverities = ['mild','moderate','severe'];
    $redFlags = $_POST['red_flags'] ?? [];
    if (
        $symptom === null || !in_array($symptom, $allowedSymptoms, true) ||
        $duration === null || !in_array($duration, $allowedDurations, true) ||
        $severity === null || !in_array($severity, $allowedSeverities, true) ||
        !in_array($warningSigns, ['yes','no'], true) ||
        !is_array($redFlags) ||
        array_filter($redFlags, static fn($flag): bool => !is_string($flag) || !in_array($flag, $emergencyFlags, true)) ||
        ($warningSigns === 'yes' && count($redFlags) === 0) ||
        ($warningSigns === 'no' && count($redFlags) > 0)
    ) {
        $error = 'Please choose valid answers and explicitly state whether any warning signs apply. Unrecognized or missing answers cannot be assessed.';
    } else {
        $urgent = $warningSigns === 'yes';
        if ($urgent) {
            $result = ['level'=>'Urgent warning','class'=>'danger','text'=>'These warning signs may indicate a medical emergency. Contact local emergency services or go to the nearest health facility now. Do not wait for this website or an online reply.'];
        } elseif ($severity === 'severe') {
            $result = ['level'=>'Seek prompt medical care','class'=>'warning','text'=>'Because you reported severe symptoms, please contact a qualified health professional or visit a health facility promptly.'];
        } elseif ($duration === 'several_days' || $duration === 'getting_worse') {
            $result = ['level'=>'Arrange professional advice','class'=>'warning','text'=>'Symptoms that persist or get worse should be assessed by a qualified health professional. Please arrange medical advice.'];
        } else {
            $result = ['level'=>'General guidance only','class'=>'info','text'=>'This form cannot identify the cause of your symptom. Monitor how you feel, use reliable health information, and contact a qualified health professional if symptoms persist, worsen, or concern you.'];
        }
        try {
            $stmt = db()->prepare('INSERT INTO symptom_checks (symptom, duration, severity, guidance_level) VALUES (?, ?, ?, ?)');
            $stmt->execute([$symptom, $duration, $severity, $result['level']]);
        } catch (PDOException $ex) {
            error_log('Ubuntu Health AI could not save a symptom check: ' . $ex->getMessage());
            $storageWarning = 'Your general guidance is shown, but this request could not be saved.';
        } catch (RuntimeException $ex) {
            $storageWarning = 'Your general guidance is shown, but database storage is unavailable.';
        }
    }
}
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-heading"><span class="eyebrow">SYMPTOM GUIDANCE</span><h1>What are you experiencing?</h1><p>This tool offers general next-step information only. It does not diagnose or rule out illness.</p></section>
<?php if ($error): ?><div class="result-panel danger" role="alert"><?= e($error) ?></div><?php endif; ?>
<?php if ($storageWarning): ?><div class="result-panel warning" role="status"><?= e($storageWarning) ?></div><?php endif; ?>
<?php if ($result): ?><section class="result-panel <?= e($result['class']) ?>"><strong><?= e($result['level']) ?></strong><p><?= e($result['text']) ?></p><a href="appointments.php" class="btn btn-primary">Request an appointment</a></section><?php endif; ?>
<form class="form-card" method="post">
<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<label for="symptom">Main symptom (do not include your full name or ID)</label>
<select name="symptom" id="symptom" required><option value="">Choose a general category</option><option value="fever">Fever / feeling hot</option><option value="cough">Cough / sore throat</option><option value="headache">Headache</option><option value="stomach">Stomach discomfort</option><option value="skin">Skin concern</option><option value="other">Other</option></select>
<label for="duration">How long has it been happening?</label>
<select name="duration" id="duration" required><option value="">Choose duration</option><option value="today">Started today</option><option value="one_two_days">1–2 days</option><option value="several_days">Several days</option><option value="getting_worse">Getting worse</option></select>
<label for="severity">How would you describe it?</label>
<select name="severity" id="severity" required><option value="">Choose severity</option><option value="mild">Mild</option><option value="moderate">Moderate</option><option value="severe">Severe</option></select>
<label for="warning_signs">Do any of these warning signs apply?</label>
<select name="warning_signs" id="warning_signs" required><option value="">Choose yes or no</option><option value="yes">Yes, at least one applies</option><option value="no">No, none apply</option></select>
<fieldset><legend>If yes, select all that apply</legend>
<label class="check"><input type="checkbox" name="red_flags[]" value="breathing"> Serious difficulty breathing</label>
<label class="check"><input type="checkbox" name="red_flags[]" value="chest_pain"> Severe or persistent chest pain</label>
<label class="check"><input type="checkbox" name="red_flags[]" value="unconscious"> Unconsciousness or cannot be woken</label>
<label class="check"><input type="checkbox" name="red_flags[]" value="stroke"> Sudden face/arm weakness or trouble speaking</label>
<label class="check"><input type="checkbox" name="red_flags[]" value="severe_bleeding"> Severe bleeding</label>
</fieldset>
<button class="btn btn-primary" type="submit">Get general guidance</button>
<p class="form-note">If a warning sign is happening now, seek urgent help immediately rather than waiting to submit this form.</p>
</form>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
