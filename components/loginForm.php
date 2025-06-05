<link rel="stylesheet" href="../css/Login.css">
<div id="mainContent" class="d-flex flex-grow-1 bg-body-secondary" data-bs-theme="dark">
    <div id="loginForm" class="bg-body-tertiary container p-3" data-bs-theme="dark">
        <form onsubmit="submitForm(); return false" class="mainForm d-flex flex-column">
            <legend>Login</legend>
            <label for="username">Username:</label>
            <div class="input-group mb-3 container align-self-center">
                <input type="text" class="form-control fs-5" id="username" name="username" placeholder="Username"
                    aria-label="Username" maxlength="30" required>
            </div>
            <label for="Password">Password:</label>
            <div class="input-group mb-3 container align-self-center">
                <input type="password" class="form-control fs-5" id="password" name="password" placeholder="Password"
                    aria-label="Password" maxlength="100" required>
            </div>
            <div class="d-flex justify-content-evenly">
                <button id="loginButton" class="btn btn-primary button" type="submit">Submit</button>

                </button>
                <button class="btn btn-primary button">
                    <a href="/register/">Sign Up</a>
                </button>
            </div>
            <div id="errorBox"></div>
        </form>
    </div>
</div>