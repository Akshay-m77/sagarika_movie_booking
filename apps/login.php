<?php
// Start the session to access stored CSRF token
session_start();

// Generate CSRF token if it doesn't exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));  // Securely generate a token
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="../images/logo.png">
   
  <title>Login</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f0f0f0;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .login-container {
      background: #ffffff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      width: 400px;
      /* height: 400px; */
      text-align: center;
      overflow: hidden
    }
    h1 {
      font-size: 1.5em;
      margin-bottom: 20px;
    }
    input[type="text"],
    input[type="password"] {
      width: 90%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 1em;
    }
    button {
      background-color: #007BFF;
      color: white;
      border: none;
      padding: 10px;
      border-radius: 4px;
      width: 95%;
      font-size: 1em;
      cursor: pointer;
    }
    button:hover {
      background-color: #0056b3;
    }
    .error {
      color: red;
      font-size: 0.9em;
    }
    .login-image {
  width: 100px;
  height: 100px;
  margin: 5px auto;
  border-radius: 50%;
}
/* Mobile */
@media (max-width: 767px) {
  .login-container {
    margin: 20px 20px;
    padding: 20px;
  }
}

/* Tablet */
@media (min-width: 768px) and (max-width: 1023px) {
  .login-container {
    margin: 30px auto;
    padding: 15px;
  }
}

/* Desktop */
@media (min-width: 1024px) {
  .login-container {
    margin: 40px auto;
    padding: 20px;
  }
}

  </style>
</head>
<body>
  <div class="login-container">
  <img src="../images/logo.png" alt="Login Image" class="login-image">
 
    <h1>Login</h1>
    <form action="login_handler.php" method="POST" onsubmit="return validateForm()">
      <!-- CSRF Token -->
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
      
      <!-- Username -->
      <input type="text" id="name" name="name" placeholder="Username" required>
      <span id="nameError" class="error"></span>
      
      <!-- Password -->
      <input type="password" id="password" name="password" placeholder="Password" required>
      <span id="passwordError" class="error"></span>
      
      <!-- Submit Button -->
      <button type="submit">Login</button>
    </form>
  </div>
  
  <script>
    function validateForm() {
      const name = document.getElementById('name').value;
      const password = document.getElementById('password').value;
      const nameError = document.getElementById('nameError');
      const passwordError = document.getElementById('passwordError');

      let isValid = true;

      // Reset error messages
      nameError.textContent = '';
      passwordError.textContent = '';


      // Validate password
      if (password.length < 4) {
        passwordError.textContent = 'Password must be at least 4 characters.';
        isValid = false;
      }

      return isValid;
    }
  </script>
</body>
</html>
