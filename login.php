<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <link rel="stylesheet" href="css/login_page.css">
</head>
<body>
<div id="bg"></div>
    <form action="php/login_check.php" method="POST">
    <div class="form-field">
        <input type="text" name="username" placeholder="Username" required><br>
     </div>
        <div class="form-field">
        <input type="password" name="password" placeholder="Password" required><br>
        </div>
        <div class="form-field">
        <button class="btn" type="submit">Submit</button>
        </div>
    </form>
</body>
</html>
