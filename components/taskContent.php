<link href="../css/taskContent.css" rel="stylesheet" type="text/css">
<div id="taskContent" class="d-flex flex-grow-1 bg-body-secondary" data-bs-theme="dark">
  <div class="d-flex flex-column">
    <nav class="navbar navbar-expand-lg bg-body-tertiary bg-opacity-50" data-bs-theme="dark" id="topbar">
      <div class="container-fluid">

      </div>
    </nav>
    <div id="centerContainer" class="bg-body-secondary" data-bs-theme="dark">
      <div id="task" class="d-flex">
        <div id="taskCenter">

          <div id="taskTitle">
          </div>
          <div id="taskDate">
          </div>
          <div id="taskBody">
          </div>

          <div id="referenceFileTitle">
            Reference File:
          </div>
          <div id="referenceFileContainer" class="bg-body-tertiary d-flex" data-bs-theme="dark">
            <div id="referenceFileIcon">

            </div>
            <div id="referenceFileName">

            </div>
            <div id="referenceFileSize">

            </div>
          </div>

          <form action="POST" id="taskFileUploadContainer" enctype="multipart/form-data">
            <label for="taskFileUpload" id="taskFileUploadLabel">File Upload</label>
            <input type="file" id="taskFileUpload">
          </form>

          <div id="taskSendContainer">
            <div id="taskStatus">
              Not sent
            </div>

            <button id="taskSendButton" class="btn btn-primary me-3" type="button" onclick="taskFileUpload()">
              Send Task
            </button>
          </div>
        </div>
        <div id="taskSide">
        </div>
      </div>
    </div>
  </div>
</div>
