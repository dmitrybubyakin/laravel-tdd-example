# TDD Test App

## Contact Form

Fields:
 - name - required
 - email - required
 - phone - required
 - attachments - optional, limit - 3, allowed extensions: doc, docx, pdf, max file size - 10MB
 - notes - optional, max 5K chars

Notes:
 - refreshing a page shouldn't reset the form
 - automatic purge of temp files
 - the form should be protected by Google Recaptcha
 - store the form submissions in Google Sheets
 - admin (admin@app.test) and the form submitter should be notified once the form is submitted
