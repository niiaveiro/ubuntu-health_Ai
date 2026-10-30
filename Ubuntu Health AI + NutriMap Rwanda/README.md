# Ubuntu Health AI

A PHP + MySQL starter web application for health education and healthcare navigation.

## Included
- Responsive home dashboard
- Rule-based symptom guidance (not a diagnosis)
- Health information chatbot with safe, curated answers
- Appointment request form
- Health education articles
- Contact / feedback form
- MySQL schema
- Dataset source registry with provenance, access status, and limitations (`data/dataset_registry.csv`)
- Prepared statements, input validation, and CSRF token on forms

## Requirements
- XAMPP (Apache + PHP + MySQL/MariaDB)
- A modern web browser

## Installation
1. Place this project folder at `C:\xampp\htdocs\Ubuntu_Health_AI`.
2. Start **Apache** and **MySQL** in XAMPP.
3. Open `http://localhost/phpmyadmin`.
4. Create a dedicated MySQL/MariaDB user for this application with access only to the `ubuntu_health_ai` database. Do not use the MySQL `root` account for the application.
5. Import `C:\xampp\htdocs\Ubuntu_Health_AI\database\ubuntu_health_ai.sql` using phpMyAdmin.
6. Configure these Apache environment variables in `C:\xampp\apache\conf\httpd.conf`, replacing the example values with the credentials for your dedicated database user:

   ```apache
   SetEnv UBUNTU_HEALTH_DB_HOST "127.0.0.1"
   SetEnv UBUNTU_HEALTH_DB_NAME "ubuntu_health_ai"
   SetEnv UBUNTU_HEALTH_DB_USER "ubuntu_health_app"
   SetEnv UBUNTU_HEALTH_DB_PASS "replace-with-your-database-password"
   ```

   `UBUNTU_HEALTH_DB_PASS` must be explicitly set; an empty password is accepted only when configured as an empty value. Restart Apache after changing its configuration. Keep real credentials out of source control and restrict access to the Apache configuration file.
7. Open `http://localhost/Ubuntu_Health_AI/`.

The application intentionally does not fall back to default database credentials. Missing or failed database configuration is logged by PHP and shown to users as a generic storage/connection message.

## Important safety note
This prototype provides general health education and basic navigation only. The symptom guidance is rule-based and does not diagnose or rule out illness; missing or invalid answers, including an unanswered warning-sign question, are rejected rather than interpreted as negative. The chatbot uses prepared responses, not a medically validated AI system. Neither feature prescribes medicine or replaces a qualified health professional. Emergency warnings are general and should not delay seeking urgent help. Avoid entering highly sensitive personal information.

The chatbot stores only a response category, not the submitted question text. Appointment requests and contact messages contain personal information and are stored in the local database. Use fictional test data during development.

## Rwanda data foundation status

The source registry records candidate Rwanda health, nutrition, agriculture, and geographic sources, along with what has and has not been verified. No survey microdata, nutrition indicator values, or boundary files have been imported. A source appearing in the registry does not mean its data are licensed, approved, or ready for analysis; check its access conditions, metadata, sampling design, and geographic reporting level before use.

## Before real-world deployment
- Add secure authentication and role-based access for clinicians/admins.
- Use HTTPS, secure session settings, rate limiting, audit logging, backups, and security testing.
- Obtain appropriate consent and review Rwanda's applicable health-data and privacy requirements.
- Have qualified clinicians review all medical content and triage rules.
- Connect only to verified health facilities and appointment workflows.
- Do not advertise the rule-based chatbot as a clinically validated AI system.
