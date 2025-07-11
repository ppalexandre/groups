<link href="../css/navbar.css" rel="stylesheet" type="text/css">
<nav class="navbar navbar-expand-sm bg-body-tertiary" data-bs-theme="dark" id="navbar">
  <div class="container-fluid">
    <button id="toggleSidebarButton" onclick="toggleSidebar()">
      <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="white" class="bi bi-arrow-right-circle-fill" viewBox="0 0 16 16" id="collapseIcon">
        <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0m3.5 7.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
      </svg>
    </button>

    <a class="navbar-brand" href="https://github.com/ppalexandre/groups">
      <img src="../imgs/github-white.png" height="35" width="35">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse bg-body-tertiary" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="../pages/tasks.php">Home</a>
        </li>
      </ul>


      <div>
        <div id="navbarUserName"></div>
      </div>

      <button class="btn btn-outline-danger me-2" onclick="sendLogoutRequest()">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-dash-fill" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M11 7.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5"/>
          <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
        </svg>
        Logout
      </button>
    </div>
  </div>
</nav>
