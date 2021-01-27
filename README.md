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

```js
function doPost(request) {
  var data = JSON.parse(request.postData.contents)

  return ContentService.createTextOutput(JSON.stringify(appendFormData(data)))
}

function appendFormData(data) {
  var sheet = SpreadsheetApp.getActiveSpreadsheet().getSheetByName(data.Form)

  if (sheet.getLastRow() === 0) {
    sheet.appendRow(['Date', ...Object.keys(data)])
  }

  var headings = sheet.getRange('A1:1').getValues()[0]
  var row = []

  data['Date'] = new Date()

  headings.forEach(function (heading) {
    row.push(data[heading] || null)
  })

  sheet.appendRow(row)

  return {
    lastRow: sheet.getLastRow(),
  }
}
```
