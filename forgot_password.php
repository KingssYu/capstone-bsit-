<?php
include './connection/connections.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password</title>

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Arial', sans-serif;
    }

    body {
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: #f4f4f9;
    }

    .form-container {
      background-color: #fff;
      padding: 20px 40px;
      border-radius: 10px;
      box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }

    .form-container h2 {
      margin-bottom: 20px;
      font-size: 24px;
      color: #333;
    }

    .input-group {
      margin-bottom: 15px;
      text-align: left;
    }

    .input-group label {
      display: block;
      margin-bottom: 5px;
      font-size: 14px;
      color: #555;
    }

    .input-group input {
      width: 100%;
      padding: 10px;
      font-size: 14px;
      border: 1px solid #ddd;
      border-radius: 5px;
      transition: border 0.3s ease;
    }

    .input-group input:focus {
      border-color: #007bff;
      outline: none;
    }

    .login-button {
      width: 100%;
      padding: 12px;
      background-color: #007bff;
      color: #fff;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .login-button:hover {
      background-color: #0056b3;
    }

    p {
      margin-top: 10px;
      font-size: 14px;
    }

    .success-message {
      color: green;
      font-size: 16px;
      margin-top: 10px;
    }
  </style>
</head>

<body>
  <div class="form-container">
    <h2>Enter your email to Generate Password</h2>
    <form action="forgot_password_process.php" method="post">
      <div class="input-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" required>

        <input type="hidden" name="forgot_password" value="1">

      </div>
      <button type="submit" class="login-button">Proceed</button>
    </form>
  </div>
</body>

</html>