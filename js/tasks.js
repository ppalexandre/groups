let lastUpdatedTimestamp = 0;
let storedTasks = [];
let currentlyDisplayedTaskId = 0;

async function requestAvailableTasks(){
    var availableTasksDataFetch = await fetch(`../php/requestAvailableTasks.php?timestamp=${lastUpdatedTimestamp}`, {
        method: 'GET',
        headers: {'Content-type': 'text/plain'}
    })
        .catch((error) => console.error('ERROR:', error));
    let availableTasksData = await availableTasksDataFetch.text();

    if (availableTasksData != ""){
        availableTasksData = JSON.parse(availableTasksData); 
        storeTaskData(availableTasksData);
        for (let i = 0; i < storedTasks.length; i++){
            displaySideTask(storedTasks[i]);
        }
        displayMainTask(storedTasks[0]);
        let lastUpdatedTimestamp = Date.now();
    }
}

function storeTaskData(taskData){
    taskData = taskData.taskList;

    for (let i = 0; i < taskData.length; i++){
        let taskId = taskData[i].taskId;
        let taskTitle = taskData[i].taskTitle;
        let taskBody = taskData[i].taskBody;
        let taskCreationDate = new Date(taskData[i].taskCreationDate);
        let taskCompletionDate = new Date(taskData[i].taskCompletionDate);
        let referenceFileName = taskData[i].referenceFileName;
        let referenceFileSize = taskData[i].referenceFileSize;
        let referenceFileMimeType = taskData[i].referenceFileMimeType;
        let sentTaskStatus = taskData[i].sentTaskStatus;
        let sentTaskTimestamp = new Date(taskData[i].sentTaskTimestamp);

        let existingTaskIndex = null;
        for (let j = 0; j < storedTasks.length; j++){
            if(storedTasks[j].taskId === taskId){
                existingTaskIndex = j;
                break;
            }
        }
        
        if (existingTaskIndex != null){
            storedTasks[existingTaskIndex].taskTitle = taskTitle;
            storedTasks[existingTaskIndex].taskBody = taskBody;
            storedTasks[existingTaskIndex].taskCreationDate = taskCreationDate;
            storedTasks[existingTaskIndex].taskCompletionDate = taskCompletionDate;
            storedTasks[existingTaskIndex].referenceFileName = referenceFileName;
            storedTasks[existingTaskIndex].referenceFileSize = referenceFileSize;
            storedTasks[existingTaskIndex].referenceMimeType = referenceMimeType;
            storedTasks[existingTaskIndex].sentTaskStatus = sentTaskStatus;
            storedTasks[existingTaskIndex].sentTaskTimestamp = sentTaskTimestamp;
        }
        else{
            const task = {
                taskId: taskId,
                taskTitle: taskTitle,
                taskBody: taskBody,
                taskCreationDate: taskCreationDate,
                taskCompletionDate: taskCompletionDate,
                referenceFileName: referenceFileName,
                referenceFileSize: referenceFileSize,
                referenceFileMimeType: referenceFileMimeType,
                sentTaskStatus: sentTaskStatus,
                sentTaskTimestamp: sentTaskTimestamp
            };
            storedTasks.push(task);
        }
    }
}

function displaySideTask(storedTask){
    let sideTasksContainer = document.getElementById("sideTasksContainer");

    let taskId = storedTask.taskId;
    let taskTitle = storedTask.taskTitle;
    let taskCreationDate = storedTask.taskCreationDate;
    let taskCompletionDate = storedTask.taskCompletionDate;
    let sentTaskStatus = storedTask.sentTaskStatus;
    let sentTaskTimestamp = storedTask.sentTaskTimestamp;

    let sideTaskDiv = document.getElementById("task" + taskId);
    if(sideTaskDiv === null){
        sideTaskDiv = document.createElement("div");
        sideTaskDiv.id = "task" + taskId;
        sideTaskDiv.className = "sideTask";
        var sideTaskTitleDiv = document.createElement("div");
        sideTaskTitleDiv.className = "sideTaskTitle";
        var sideTaskDateDiv = document.createElement("div");
        sideTaskDateDiv.className = "sideTaskDate";

        sideTasksContainer.appendChild(sideTaskDiv);
        sideTaskDiv.appendChild(sideTaskTitleDiv);
        sideTaskDiv.appendChild(sideTaskDateDiv);
        sideTaskDiv.addEventListener('click', function(){
            displayMainTask(storedTask);
        }); 
        sideTaskDiv.style.cursor = "pointer";
    }
    else{
        var sideTaskTitleDiv = sideTaskDiv.children.getElementsByClassName("sideTaskTitle")[0];
        var sideTaskDateDiv = sideTaskDiv.children.getElementsByClassName("sideTaskDate")[0];
    }

    formattedTaskCreationDate = taskCreationDate.toLocaleDateString();
    formattedTaskCreationTime = taskCreationDate.toLocaleTimeString();

    sideTaskTitleDiv.innerText = taskTitle;
    sideTaskDateDiv.innerText = `Posted: ${formattedTaskCreationDate} ${formattedTaskCreationTime}`
    sideTaskDateDiv.innerHTML += ` <br> `
    if(sentTaskStatus){
        formattedSentTaskDate = sentTaskTimestamp.toLocaleDateString();
        formattedSentTaskTime = sentTaskTimestamp.toLocaleTimeString();
        sideTaskDateDiv.innerText += `Sent: ${formattedSentTaskDate} ${formattedSentTaskTime}`;
    }
    else{
        formattedTaskCompletionDate = taskCompletionDate.toLocaleDateString();
        formattedTaskCompletionTime = taskCompletionDate.toLocaleTimeString();
        sideTaskDateDiv.innerText += `Deadline: ${formattedTaskCompletionDate} ${formattedTaskCompletionTime}`;
    }
    handleSideTaskCompletion(sentTaskStatus, taskId);
}


function displayMainTask(storedTask){
    let centerContainer = document.getElementById("centerContainer");
    let taskDiv = document.getElementById("task");
    let taskTitleDiv = document.getElementById("taskTitle");
    let taskDateDiv = document.getElementById("taskDate");
    let taskBodyDiv = document.getElementById("taskBody");
    let referenceFileContainerDiv = document.getElementById("referenceFileContainer");
    let referenceFileNameDiv = document.getElementById("referenceFileName");
    let referenceFileSizeDiv = document.getElementById("referenceFileSize");
    let referenceFileIconDiv = document.getElementById("referenceFileIcon");

    let taskId = storedTask.taskId;
    let taskTitle = storedTask.taskTitle;
    let taskBody = storedTask.taskBody;
    let taskCreationDate = storedTask.taskCreationDate;
    let taskCompletionDate = storedTask.taskCompletionDate;
    let referenceFileName = storedTask.referenceFileName;
    let referenceFileSize = storedTask.referenceFileSize;
    let referenceFileMimeType = storedTask.referenceFileMimeType;
    let sentTaskStatus = storedTask.sentTaskStatus;
    let sentTaskTimestamp = storedTask.sentTaskTimestamp;

    let formattedReferenceFileSize = formatFileSize(referenceFileSize);
    let formattedTaskCreationDate = taskCreationDate.toLocaleDateString();
    let formattedTaskCreationTime = taskCreationDate.toLocaleTimeString();
    let formattedTaskCompletionDate = taskCompletionDate.toLocaleDateString();
    let formattedTaskCompletionTime = taskCompletionDate.toLocaleTimeString();

    taskTitleDiv.innerText = taskTitle;
    taskTitleDiv.innerHTML += "<br>";
    taskBodyDiv.innerText = taskBody;
    taskDateDiv.innerText = `Posted: ${formattedTaskCreationDate} ${formattedTaskCreationTime}     Deadline: ${formattedTaskCompletionDate} ${formattedTaskCompletionTime}`;

    referenceFileContainerDiv.addEventListener('click', function(){
        referenceFileDownload(taskId, referenceFileName);
    }); 
    referenceFileContainerDiv.style.cursor = "pointer";
    referenceFileNameDiv.innerText = referenceFileName;
    referenceFileSizeDiv.innerText = formattedReferenceFileSize;
    referenceFileIconDiv.innerText = referenceFileMimeType;

    changeTaskStatusDisplay(sentTaskStatus, sentTaskTimestamp);
    currentlyDisplayedTaskId = taskId;
    handleSideTaskCompletion(sentTaskStatus, taskId);
    highlightSideTask(taskId);
}


async function referenceFileDownload(taskId, referenceFileName){
    fetch(`../php/requestReferenceFile.php?taskId=${taskId}`, {
        method: 'GET',
        headers: {'Content-type': 'text/plain'}
    })
        .then(response => response.blob())
        .then(blob => {
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', referenceFileName);
            document.body.appendChild(link);
            link.click();
            link.remove();
        })
        .catch((error) => console.error('ERROR:', error));
}

async function taskFileUpload(){
    let taskId = currentlyDisplayedTaskId;
    let file = document.getElementById("taskFileUpload").files[0];
    if (file != undefined && file != ""){
        let formData = new FormData();
        formData.append("file", file);
        formData.append("taskId", taskId);
        let taskFileUploadFetch = await fetch('../php/handleTaskCompletion.php', {
            method: 'POST',
            body: formData
        })
            .catch((error) => console.error('ERROR:', error));
        let response = await taskFileUploadFetch.text();
        if (response != ""){
            response = JSON.parse(response);
            storeTaskStatus(response.taskStatus, taskId);
            changeTaskStatusDisplay(response.taskStatus, response.taskTimestamp);
        }
    }
} 

function formatFileSize(fileSize){
    if(fileSize < 1000){
        fileSize += "B"; 
    }
    else if(fileSize < 1000000){
        fileSize = fileSize / 1000; 
        fileSize = Math.trunc(fileSize * 100) / 100;
        fileSize += "KB"; 
    }
    else if(fileSize < 1000000000){
        fileSize = fileSize / 1000000; 
        fileSize = Math.trunc(fileSize * 100) / 100;
        fileSize += "MB"; 
    }
    else{
        fileSize = fileSize / 1000000000; 
        fileSize = Math.trunc(fileSize * 100) / 100;
        fileSize += "GB"; 
    }
    return fileSize;
}

function storeTaskStatus(taskStatus, taskId){
    for (let i = 0; i < storedTasks.length; i++){
        if(storedTasks[i].taskId === taskId){
            storedTasks[i].sentTaskStatus = taskStatus;
            return true;
        }
    }
    return false;
}

function highlightSideTask(taskId){
    let lastHighlightedDiv = document.getElementsByClassName("sideTask sideTaskHighlight")[0];
    if (lastHighlightedDiv != null){
        lastHighlightedDiv.className = "sideTask";
    }

    let sideTaskDiv = document.getElementById("task" + taskId);
    sideTaskDiv.className = "sideTask sideTaskHighlight";
}

function handleSideTaskCompletion(taskStatus, taskId){
    let sideTaskDiv = document.getElementById("task" + taskId);
    sideTaskDiv.style.borderLeft = "3px solid #77aa77";
}

function changeTaskStatusDisplay(taskStatus){
    let taskStatusDiv = document.getElementById("taskStatus");
    let taskSendButtonDiv = document.getElementById("taskSendButton");
    if (taskStatus === "1" || taskStatus === true){
        taskStatusDiv.innerText = "Task sent"; 
        taskSendButtonDiv.innerText = "Send task again"; 
    }
    else if (taskStatus === "0" || taskStatus === false){
        taskSendButtonDiv.innerText = "Not sent"; 
        taskStatusDiv.innerText = "Send task"; 
    }
}

function spinIcon(iconId){
    let iconElement = document.getElementById(iconId);
    let iconSpin = iconElement.getAttribute("data-spin");

    if (iconSpin === null){
        iconElement.style.transition = "all 0.3s linear";
    }

    if (iconSpin == "true"){
        iconElement.setAttribute("data-spin", "false");
        iconElement.setAttribute("transform", "rotate(0)");
    }
    else{
        iconElement.setAttribute("data-spin", "true");
        iconElement.setAttribute("transform", "rotate(180)");
    }
}

requestAvailableTasks();
