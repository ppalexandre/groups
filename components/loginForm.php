<link href="../css/loginForm.css" rel="stylesheet" type="text/css">
<div id="mainContent" class="flex-grow-1 bg-body-secondary" data-bs-theme="dark">
  <div id="loginForm" class="bg-body-tertiary container p-3 container" data-bs-theme="dark">
    <form onsubmit="submitForm(); return false" class="mainForm d-flex flex-column">
      <legend>Login</legend>
      <label for="username">Username:</label>
      <div class="input-group mb-3 container align-self-center">
        <input type="text" class="form-control" id="username" name="username" placeholder="Username"
          aria-label="Username" maxlength="30" required>
      </div>
      <label for="password">Password:</label>
      <div class="input-group mb-3 container align-self-center">
        <input type="password" class="form-control" id="password" name="password" placeholder="Password"
          aria-label="Password" maxlength="100" required>
      </div>
      <div class="d-flex justify-content-evenly">
        <button id="loginButton" class="btn btn-primary button" type="submit">Submit</button>
        <button class="btn btn-link button" tabindex="-1">
          <a href="../pages/register.php">Sign Up</a>
        </button>
      </div>
    </form>
    <div id="errorBox"></div>
  </div>
</div>
