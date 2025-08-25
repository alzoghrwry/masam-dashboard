<form action="/login" method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>

<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
