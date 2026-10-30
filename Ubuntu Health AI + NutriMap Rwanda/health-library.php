<?php
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Health Library';
$articles = [
 ['title'=>'Everyday hydration','category'=>'PREVENTION','text'=>'Drink fluids regularly throughout the day. Needs vary with activity, weather, age, and health. If a clinician has advised fluid limits, follow their advice.'],
 ['title'=>'Building a healthy sleep routine','category'=>'WELLBEING','text'=>'Try a consistent sleep and wake time, a relaxing evening routine, and a comfortable, quiet sleeping space. Ask a professional for help when sleep problems continue.'],
 ['title'=>'When to seek urgent help','category'=>'SAFETY','text'=>'Severe difficulty breathing, severe chest pain, unconsciousness, sudden stroke-like symptoms, or severe bleeding need urgent medical attention. Do not wait for an online response.'],
 ['title'=>'Preventing the spread of infection','category'=>'PREVENTION','text'=>'Wash hands with soap, cover coughs and sneezes, improve ventilation where possible, and follow current guidance from qualified local health authorities.'],
 ['title'=>'Preparing for a clinic visit','category'=>'ACCESS TO CARE','text'=>'Write down your main concerns, when they began, allergies, and medicines you take. Share relevant information privately with the health professional. Do not delay emergency care to prepare notes.'],
 ['title'=>'Using medicines safely','category'=>'MEDICINE SAFETY','text'=>'Follow the instructions from a qualified professional and the medicine label. Do not share prescription medicines. Ask a pharmacist or clinician if you are unsure about interactions or side effects.']
];
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-heading"><span class="eyebrow">HEALTH LIBRARY</span><h1>Small steps. Better knowledge.</h1><p>Starter educational articles to support healthy decisions. They are not a substitute for personal medical advice.</p></section>
<div class="article-grid"><?php foreach($articles as $i=>$a): ?><article class="article-card"><div class="article-number"><?= str_pad((string)($i+1),2,'0',STR_PAD_LEFT) ?></div><span class="eyebrow"><?= e($a['category']) ?></span><h2><?= e($a['title']) ?></h2><p><?= e($a['text']) ?></p></article><?php endforeach; ?></div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
