<?php
// Start the session
session_start();

// Check if employee is logged in
if (!isset($_SESSION['employee'])) {
  header("Location: login.php");
  exit();
}

// Get employee data from session
$employee = $_SESSION['employee'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title><?php echo $employee['first_name'] . ' ' . $employee['last_name']; ?>'s Dashboard</title>
  <link rel="stylesheet" href="employee_styles/employee_dashboard.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

</head>

<body>
  <?php include './employee_navigation.php'; ?>

  <div class="employee-greeting">
    <h1>Change your Password</h1>

  </div>
  </div>

  <div class="employee-details-container">
    <form action="change_password_process.php" method="POST">
      <div class="mb-3">
        <label for="currentPassword" class="form-label">Current Password</label>
        <div class="input-group">
          <input type="password" class="form-control" id="currentPassword" name="current_password" required>
          <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#currentPassword">Show</button>
        </div>
      </div>
      <div class="mb-3">
        <label for="newPassword" class="form-label">New Password</label>
        <div class="input-group">
          <input type="password" class="form-control" id="newPassword" name="new_password" required>
          <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#newPassword">Show</button>
        </div>
      </div>
      <div class="mb-3">
        <label for="confirmPassword" class="form-label">Confirm New Password</label>
        <div class="input-group">
          <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required>
          <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#confirmPassword">Show</button>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Change Password</button>
    </form>
  </div>


</body>

</html>

<!-- Add Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Add Bootstrap JS and Popper.js for Modal functionality -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
<script>
  // JavaScript to toggle password visibility
  document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function() {
      const target = document.querySelector(this.getAttribute('data-target'));
      const type = target.getAttribute('type') === 'password' ? 'text' : 'password';
      target.setAttribute('type', type);
      this.textContent = type === 'password' ? 'Show' : 'Hide';
    });
  });
</script>
<style>
  /* Directory Table */
  .directory-table {
    width: 100%;
    margin-top: 20px;
    border-collapse: collapse;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }

  .directory-table th,
  .directory-table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
  }

  .directory-table th {
    background-color: #f9f9f9;
    color: #333;
  }

  .directory-table td {
    background-color: #fff;
  }

  /* Profile image */
  .directory-table img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 10px;
  }

  .directory-table .name-cell {
    display: flex;
    align-items: center;
  }
</style>