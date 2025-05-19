<?php
require_once('classes/database.php');
$con = new database();

$sweetAlertConfig = "";

if (isset($_POST['register'])) {

  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
  $firstname = $_POST['first_name'];
  $lastname = $_POST['last_name'];

  $userID = $con->signupUser($username, $email, $password, $firstname, $lastname);

  if ($userID) {
    $sweetAlertConfig = "
        <script>
        Swal.fire({
          icon: 'success',
          title: 'Registration Successful',
          text: 'You have successfully registered as an admin.',
          confirmButtonText: 'OK'
        }).then(() => {
          window.location.href = 'login.php';
        });
        </script>
        ";
  } else {
    $sweetAlertConfig = "
         <script>
        Swal.fire({
          icon: 'error',
          title: 'Registration Failed',
          text: 'An error occurred during registration. Please try again.',
          confirmButtonText: 'OK'
        });
        </script>
    ";
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Registration</title>
  <link rel="stylesheet" href="./bootstrap-5.3.3-dist/css/bootstrap.css" />
  <link rel="stylesheet" href="./package/dist/sweetalert2.css" />
</head>

<body class="bg-light">
  <div class="container py-5">
    <h2 class="mb-4 text-center">Admin Registration</h2>
    <form id="registrationForm" method="POST" action="" class="bg-white p-4 rounded shadow-sm">
      <div class="mb-3">
        <label for="first_name" class="form-label">First Name</label>
        <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Enter your first name"
          required>
        <div class="invalid-feedback">First name is required.</div>
      </div>
      <div class="mb-3">
        <label for="last_name" class="form-label">Last Name</label>
        <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Enter your last name"
          required>
        <div class="invalid-feedback">Last name is required.</div>
      </div>
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" id="username" class="form-control" placeholder="Enter your username"
          required>
        <div class="invalid-feedback">Username is required.</div>
      </div>
      <div class="mb-3"> <!--email-->
        <label for="email" class="form-label">E-Mail</label>
        <input type="email" name="email" id="email" class="form-control" placeholder="Enter your E-Mail" required>
        <div class="invalid-feedback">E-Mail is required.</div>
      </div> <!--endofemail-->
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password"
          required>
        <div class="invalid-feedback">Password must be at least 6 characters long, include an uppercase letter, a
          number, and a special character.</div>
      </div>
      <button type="submit" id="registerButton" name="register" class="btn btn-primary w-100">Register</button>
    </form>
  </div>

  <script src="./bootstrap-5.3.3-dist/js/bootstrap.js"></script>
  <script src="./package/dist/sweetalert2.js"></script>
  <?php echo $sweetAlertConfig; ?>
  <script>
    const firstName = document.getElementById('first_name');
    const lastName = document.getElementById('last_name');
    const username = document.getElementById('username');
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const registerButton = document.getElementById('registerButton');

    function validateField(field, validationFn) {
      field.addEventListener('input', () => {
        if (validationFn(field.value)) {
          field.classList.remove('is-invalid');
          field.classList.add('is-valid');
        } else {
          field.classList.remove('is-valid');
          field.classList.add('is-invalid');
        }
      });
    }

    const isNotEmpty = (value) => value.trim() !== '';
    const isPasswordValid = (value) => {
      const passwordRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/;
      return passwordRegex.test(value);
    };

    function checkUsernameAvailability(usernameField) {
      usernameField.addEventListener('input', () => {
        const username = usernameField.value.trim();

        if (username === '') {
          usernameField.classList.remove('is-valid');
          usernameField.classList.add('is-invalid');
          usernameField.nextElementSibling.textContent = 'Username is required.';
          registerButton.disabled = true;
          return;
        }

        fetch('ajax/check_username.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `username=${encodeURIComponent(username)}`
        })
          .then(res => res.json())
          .then(data => {
            if (data.exists) {
              usernameField.classList.remove('is-valid');
              usernameField.classList.add('is-invalid');
              usernameField.nextElementSibling.textContent = 'Username is already taken.';
              registerButton.disabled = true;
            } else {
              usernameField.classList.remove('is-invalid');
              usernameField.classList.add('is-valid');
              usernameField.nextElementSibling.textContent = '';
              registerButton.disabled = false;
            }
          })
          .catch((error) => {
            console.error('Error:', error);
            registerButton.disabled = true;
          });
      });
    }

    function checkEmailAvailability(emailField) {
      emailField.addEventListener('input', () => {
        const email = emailField.value.trim();

        if (username === '') {
          emailField.classList.remove('is-valid');
          emailField.classList.add('is-invalid');
          emailField.nextElementSibling.textContent = 'E-Mail is required.';
          registerButton.disabled = true;
          return;
        }

        fetch('ajax/check_email.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `email=${encodeURIComponent(email)}`
        })
          .then(res => res.json())
          .then(data => {
            if (data.exists) {
              emailField.classList.remove('is-valid');
              emailField.classList.add('is-invalid');
              emailField.nextElementSibling.textContent = 'E-Mail is already taken.';
              registerButton.disabled = true;
            } else {
              emailField.classList.remove('is-invalid');
              emailField.classList.add('is-valid');
              emailField.nextElementSibling.textContent = '';
              registerButton.disabled = false;
            }
          })
          .catch((error) => {
            console.error('Error:', error);
            registerButton.disabled = true;
          });
      });
    }

    validateField(firstName, isNotEmpty);
    validateField(lastName, isNotEmpty);
    checkEmailAvailability(email);
    validateField(password, isPasswordValid);
    checkUsernameAvailability(username);

    document.getElementById('registrationForm').addEventListener('submit', function (e) {
      let isValid = true;

      [firstName, lastName, username, email, password].forEach((field) => {
        if (!field.classList.contains('is-valid')) {
          field.classList.add('is-invalid');
          isValid = false;
        }
      });

      if (!isValid) {
        e.preventDefault();
      }
    });
  </script>
</body>

</html>