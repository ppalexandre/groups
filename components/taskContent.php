<link href="../css/taskContent.css" rel="stylesheet" type="text/css">
<div id="taskContent" class="d-flex flex-grow-1 bg-body-secondary" data-bs-theme="dark">
  <div id="centerContainer" class="bg-body-secondary" data-bs-theme="dark">
    <div id="task">
      <div id="taskTitle">
      </div>
      <div id="taskDate">
      </div>
      <div id="taskBody">
      </div>

      <div id="referenceFileTitle">
        Reference File:
      </div>
      <div id="referenceFileContainer" class="bg-body-tertiary" data-bs-theme="dark">
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
    <div id="taskCreationFormContainer" class="bg-body-secondary" data-bs-theme="dark">
      <form onsubmit="submitNewTask(); return false" id="taskCreationForm" class="d-flex flex-column">
        <div id="taskCreationFormTitle">Create New Task</div>

        <label for="formTaskTitle">Task Title:</label>
        <div class="input-group mb-3 container align-self-center">
          <input type="text" class="form-control" id="formTaskTitle" name="taskTitle" placeholder="Task Title"
            aria-label="Task Title" maxlength="100" required>
        </div>

        <label for="formTaskBody">Task Body:</label>
        <div class="input-group mb-3 container align-self-center">
          <textarea class="form-control" id="formTaskBody" name="taskBody" placeholder="Task Body"
            aria-label="Task Body" maxlength="2000" required rows="3"></textarea>
        </div>

        <label for="formDeadlineDate">Deadline Date:</label>
        <div class="input-group mb-3 container align-self-center">
          <input type="datetime-local" class="form-control" id="formDeadlineDate" name="deadlineDate" aria-label="Deadline Date" required>
        </div>

        <label for="formReferenceFile">Reference File (optional):</label>
        <div class="input-group mb-3 container align-self-center">
          <input type="file" class="form-control" id="formReferenceFile" name="referenceFile" aria-label="Reference File">
        </div>

        <!--<label for="formGroupId">Group Id:</label>
        <div class="input-group mb-3 container align-self-center">
          <input type="number" class="form-control" id="formGroupId" name="groupId"
            aria-label="Group Id" min="1" max="9999999" required>
        </div>-->

        <div class="d-flex justify-content-evenly align-self-start">
          <button id="taskCreationSubmitButton" class="btn btn-primary button" type="submit">Create New Task</button>
        </div>
      </form>
      <div id="errorBox"></div>
    </div>
  </div>
</div>
