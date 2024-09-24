<!DOCTYPE html>
<html>
<head>
    <title>Send Email</title>
</head>
<body>
    <h1>Send Email Notification</h1>
    <form action="/send-email" method="POST">
        @csrf 
        <label for="email">Recipient Email:</label>
        <input type="email" id="email" name="email" required>

        <button type="submit">Send Email</button>
    </form>
</body>
</html>
