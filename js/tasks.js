let lastUpdatedTimestamp = 0;
let storedTasks = [];
let currentlyDisplayedTaskId = 0;
let currentlyDisplayedTaskStatus = false;
let currentlyDisplayedReferenceFileName = "";
let currentlyDisplayedGroupId = 1; // temporary until i properly add groups

async function requestAvailableTasks(focusOnTask){
    if (focusOnTask == null){
        focusOnTask = false;
    }
    var availableTasksDataFetch = await fetch(`../php/requestAvailableTasks.php?timestamp=${lastUpdatedTimestamp}`, {
        method: 'GET',
        headers: {'Content-type': 'text/plain'}
    })
        .catch((error) => console.error('ERROR:', error));
    let availableTasksData = await availableTasksDataFetch.text();

    if (availableTasksData != ""){
        availableTasksData = JSON.parse(availableTasksData); 
        if(availableTasksData.taskList != null){
            storeTaskData(availableTasksData);

            for (let i = 0; i < storedTasks.length; i++){
                displaySideTask(storedTasks[i]);
            }

            if(focusOnTask === true){
                displayMainTask(storedTasks.at(-1));
            }
        }
        let lastUpdatedTimestamp = Date.now();
    }
}

function storeTaskData(taskData){
    taskData = taskData.taskList;

    for (let i = 0; i < taskData.length; i++){
        let taskId = taskData[i].taskId;
        let taskTitle = taskData[i].taskTitle;
        let taskBody = taskData[i].taskBody;
        let taskCreationDate = convertToLocalDate(new Date(taskData[i].taskCreationDate));
        let taskDeadlineDate = convertToLocalDate(new Date(taskData[i].taskDeadlineDate));
        let referenceFileStatus = taskData[i].referenceFileStatus;
        let referenceFileName = "";
        let referenceFileSize = "";
        let referenceFileMimeType = "";
        if (referenceFileStatus){
            referenceFileName = taskData[i].referenceFileName;
            referenceFileSize = taskData[i].referenceFileSize;
            referenceFileMimeType = taskData[i].referenceFileMimeType;
        }
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
            storedTasks[existingTaskIndex].taskDeadlineDate = taskDeadlineDate;
            storedTasks[existingTaskIndex].referenceFileStatus = referenceFileStatus;
            storedTasks[existingTaskIndex].referenceFileName = referenceFileName;
            storedTasks[existingTaskIndex].referenceFileSize = referenceFileSize;
            storedTasks[existingTaskIndex].referenceFileMimeType = referenceFileMimeType;
            storedTasks[existingTaskIndex].sentTaskStatus = sentTaskStatus;
            storedTasks[existingTaskIndex].sentTaskTimestamp = sentTaskTimestamp;
        }
        else{
            const task = {
                taskId: taskId,
                taskTitle: taskTitle,
                taskBody: taskBody,
                taskCreationDate: taskCreationDate,
                taskDeadlineDate: taskDeadlineDate,
                referenceFileStatus: referenceFileStatus,
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
    let sidebarMainContainer = document.getElementById("sidebarMainContainer");

    let taskId = storedTask.taskId;
    let taskTitle = storedTask.taskTitle;
    let taskCreationDate = storedTask.taskCreationDate;
    let taskDeadlineDate = storedTask.taskDeadlineDate;
    let sentTaskStatus = storedTask.sentTaskStatus;
    let sentTaskTimestamp = storedTask.sentTaskTimestamp;

    let sideTaskDiv = document.getElementById("task" + taskId);
    if(sideTaskDiv === null){
        sideTaskDiv = document.createElement("div");
        sideTaskDiv.id = "task" + taskId;
        sideTaskDiv.className = "sideTask sidebarButton";
        var sideTaskTitleDiv = document.createElement("div");
        sideTaskTitleDiv.className = "sideTaskTitle";
        var sideTaskDateDiv = document.createElement("div");
        sideTaskDateDiv.className = "sideTaskDate";

        sidebarMainContainer.appendChild(sideTaskDiv);
        sideTaskDiv.appendChild(sideTaskTitleDiv);
        sideTaskDiv.appendChild(sideTaskDateDiv);
        sideTaskDiv.addEventListener('click', function(){
            displayMainTask(storedTask);
        }); 
    }
    else{
        var sideTaskTitleDiv = sideTaskDiv.getElementsByClassName("sideTaskTitle")[0];
        var sideTaskDateDiv = sideTaskDiv.getElementsByClassName("sideTaskDate")[0];
    }

    formattedTaskCreationDate = taskCreationDate.toLocaleDateString();
    formattedTaskCreationTime = taskCreationDate.toLocaleTimeString();

    sideTaskTitleDiv.innerText = taskTitle;
    sideTaskDateDiv.innerText = `Posted: ${formattedTaskCreationDate} ${formattedTaskCreationTime}`
    sideTaskDateDiv.innerHTML += ` <br> `
    if(sentTaskStatus == 1){
        formattedSentTaskDate = sentTaskTimestamp.toLocaleDateString();
        formattedSentTaskTime = sentTaskTimestamp.toLocaleTimeString();
        sideTaskDateDiv.innerText += `Sent: ${formattedSentTaskDate} ${formattedSentTaskTime}`;
    }
    else{
        formattedTaskDeadlineDate = taskDeadlineDate.toLocaleDateString();
        formattedTaskDeadlineTime = taskDeadlineDate.toLocaleTimeString();
        sideTaskDateDiv.innerText += `Deadline: ${formattedTaskDeadlineDate} ${formattedTaskDeadlineTime}`;
    }
    handleSideTaskStatus(sentTaskStatus, taskId);
}


function displayMainTask(storedTask){
    let centerContainer = document.getElementById("centerContainer");
    let taskDiv = document.getElementById("task");
    let taskTitleDiv = document.getElementById("taskTitle");
    let taskDateDiv = document.getElementById("taskDate");
    let taskBodyDiv = document.getElementById("taskBody");
    let referenceFileTitleDiv = document.getElementById("referenceFileTitle");
    let referenceFileContainerDiv = document.getElementById("referenceFileContainer");
    let referenceFileNameDiv = document.getElementById("referenceFileName");
    let referenceFileSizeDiv = document.getElementById("referenceFileSize");
    let referenceFileIconDiv = document.getElementById("referenceFileIcon");

    let taskId = storedTask.taskId;
    let taskTitle = storedTask.taskTitle;
    let taskBody = storedTask.taskBody;
    let taskCreationDate = storedTask.taskCreationDate;
    let taskDeadlineDate = storedTask.taskDeadlineDate;
    let referenceFileStatus = storedTask.referenceFileStatus;
    let referenceFileName = storedTask.referenceFileName;
    let referenceFileSize = storedTask.referenceFileSize;
    let referenceFileMimeType = storedTask.referenceFileMimeType;
    let sentTaskStatus = storedTask.sentTaskStatus;
    let sentTaskTimestamp = storedTask.sentTaskTimestamp;

    let formattedTaskDeadlineDate = taskDeadlineDate.toLocaleDateString();
    let formattedTaskDeadlineTime = taskDeadlineDate.toLocaleTimeString();

    taskTitleDiv.innerText = taskTitle;
    taskTitleDiv.innerHTML += "<br>";
    taskBodyDiv.innerText = taskBody;
    taskDateDiv.innerText = `Posted: ${formattedTaskCreationDate} ${formattedTaskCreationTime}     Deadline: ${formattedTaskDeadlineDate} ${formattedTaskDeadlineTime}`;

    if(referenceFileStatus == true){
        let formattedReferenceFileSize = formatFileSize(referenceFileSize);

        referenceFileTitleDiv.style.display = "inline-block";
        referenceFileContainerDiv.style.display = "flex";
        referenceFileContainerDiv.style.cursor = "pointer";
        referenceFileNameDiv.innerText = referenceFileName;
        referenceFileSizeDiv.innerText = formattedReferenceFileSize;
        referenceFileIconDiv.innerText = referenceFileMimeType;
        currentlyDisplayedReferenceFileName = referenceFileName;
    }
    else{
        referenceFileTitleDiv.style.display = "none";
        referenceFileContainerDiv.style.display = "none";
    }

    currentlyDisplayedTaskId = taskId;

    currentlyDisplayedTaskStatus = sentTaskStatus;
    changeTaskStatusDisplay(sentTaskStatus, sentTaskTimestamp);
    handleSideTaskStatus(sentTaskStatus, taskId);

    highlightSidebarButton("task" + taskId);
    displayMainTaskDiv();
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

function taskSendButton(){
    if(currentlyDisplayedTaskStatus == false){
        taskFileUpload();
    }
    else{
        taskFileDeletion();
    }
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
            if(response.errorMessage != null){
                window.alert(response.errorMessage);
            }
            else{
                storeTaskStatus(response.taskStatus, taskId);
                currentlyDisplayedTaskStatus = response.taskStatus;
                changeTaskStatusDisplay(response.taskStatus, response.taskTimestamp);
                handleSideTaskStatus(response.taskStatus, taskId);
            }
        }
    }
} 

async function taskFileDeletion(){
    let taskId = currentlyDisplayedTaskId;
    let formData = new FormData();
    formData.append("taskId", taskId);
    let taskFileUploadFetch = await fetch('../php/handleSentTaskDeletion.php', {
        method: 'POST',
        body: formData
    })
        .catch((error) => console.error('ERROR:', error));
    let response = await taskFileUploadFetch.text();
    if (response != ""){
        response = JSON.parse(response);
        if(response.errorMessage != null){
            window.alert(response.errorMessage);
        }
        else{
            storeTaskStatus(response.taskStatus, taskId);
            currentlyDisplayedTaskStatus = response.taskStatus;
            changeTaskStatusDisplay(response.taskStatus, response.taskTimestamp);
            handleSideTaskStatus(response.taskStatus, taskId);
        }
    }
} 

function convertToLocalDate(dateUTC){
    let dateLocal = new Date(dateUTC);
    dateLocal.setMinutes(dateUTC.getMinutes() - dateUTC.getTimezoneOffset());
    return dateLocal;
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

function highlightSidebarButton(id){
    unhighlightSidebarButtons();
    let sidebarButtonClassList = document.getElementById(id).classList;
    sidebarButtonClassList.remove("sidebarButton");
    sidebarButtonClassList.add("sidebarButtonHighlight");
}

function unhighlightSidebarButtons(){
    try{
        const highlightedDivs = document.querySelectorAll(".sidebarButtonHighlight");
        for (let i = 0; i < highlightedDivs.length; i++){
            highlightedDivs[i].classList.remove("sidebarButtonHighlight");
            highlightedDivs[i].classList.add("sidebarButton");
        }
    }
    catch{
        console.log("ERROR: No button to unhighlight");
    }
}


function handleSideTaskStatus(taskStatus, taskId){
    let sideTaskDiv = document.getElementById("task" + taskId);
    if(taskStatus == 1){
        sideTaskDiv.style.borderLeft = "3px solid #77aa77";
    }
    else{
        sideTaskDiv.style.borderLeft = "none";
    }
}

function changeTaskStatusDisplay(taskStatus){
    let taskStatusDiv = document.getElementById("taskStatus");
    let taskSendButtonDiv = document.getElementById("taskSendButton");
    if (taskStatus === "1" || taskStatus === true){
        taskStatusDiv.innerText = "Task sent"; 
        taskSendButtonDiv.innerText = "Unsend Task"; 
    }
    else if (taskStatus === "0" || taskStatus === false){
        taskStatusDiv.innerText = "Not sent"; 
        taskSendButtonDiv.innerText = "Send Task"; 
    }
}

function displayMainTaskDiv(){
    let taskCreationFormContainer = document.getElementById("taskCreationFormContainer");
    taskCreationFormContainer.style.display = "none";
    let mainTaskDiv = document.getElementById("task");
    mainTaskDiv.style.display = "block";
}

function displayTaskCreationForm(){
    let mainTaskDiv = document.getElementById("task");
    mainTaskDiv.style.display = "none";
    let taskCreationFormContainer = document.getElementById("taskCreationFormContainer");
    taskCreationFormContainer.style.display = "block";
    highlightSidebarButton("sidebarTopButton");
}

async function getAllGroupMemberIds(groupId){
    var groupMembersIdsFetch = await fetch(`../php/requestAllGroupMembers.php?groupId=${groupId}`, {
        method: 'GET',
        headers: {'Content-type': 'text/plain'}
    })
        .catch((error) => console.error('ERROR:', error));
    let response = await groupMembersIdsFetch.text();

    if (response != ""){
        response = JSON.parse(response);
        return response.userIds;
    }
}

async function submitNewTask(){
    let formTaskTitleDiv = document.getElementById("formTaskTitle"); 
    let formTaskBodyDiv = document.getElementById("formTaskBody"); 
    let formDeadlineDateDiv = document.getElementById("formDeadlineDate"); 
    let formReferenceFileDiv = document.getElementById("formReferenceFile");  

    let taskTitle = formTaskTitleDiv.value;
    let taskBody = formTaskBodyDiv.value;
    let taskDeadlineDate = formDeadlineDateDiv.value;

    taskDeadlineDate = new Date(taskDeadlineDate).getTime();
    let currentTimestamp = Date.now();
    if (taskDeadlineDate < currentTimestamp){
        window.alert("The chosen deadline date is in the past, please choose a different date.");
        return false;
    }
    taskDeadlineDate = taskDeadlineDate / 1000;

    let file = formReferenceFileDiv.files[0];

    let groupId = currentlyDisplayedGroupId;
    let sentTaskUserIds = await getAllGroupMemberIds(groupId);
    sentTaskUserIds = JSON.stringify(sentTaskUserIds);

    if (taskTitle != null && taskBody != null && taskDeadlineDate != null && groupId != null && sentTaskUserIds != null){
        let formData = new FormData();
        formData.append("taskTitle", taskTitle);
        formData.append("taskBody", taskBody);
        formData.append("taskDeadlineDate", taskDeadlineDate);
        formData.append("groupId", groupId);
        formData.append("sentTaskUserIds", sentTaskUserIds);

        if(file != undefined && file != ""){
            formData.append("file", file);
        }

        formTaskTitleDiv.value = "";
        formTaskBodyDiv.value = "";
        formDeadlineDateDiv.value = "";
        formReferenceFileDiv.value = "";

        let taskCreationFetch = await fetch('../php/handleTaskCreation.php', {
            method: 'POST',
            body: formData
        })
            .catch((error) => console.error('ERROR:', error));
        let response = await taskCreationFetch.text();
        if (response != ""){
            response = JSON.parse(response);
            if(response.errorMessage != ""){
                window.alert(response.errorMessage);
            }
            requestAvailableTasks(false);
        }
    }
} 

function spinIcon(iconId){
    let iconElement = document.getElementById(iconId);
    let iconSpin = iconElement.getAttribute("data-spin");

    if (iconSpin === null){
        iconElement.style.transition = "all 0.35s linear";
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

function toggleSidebar(){
    spinIcon("collapseIcon");
    let sidebarDiv = document.getElementById("sidebar");
    if(sidebarDiv.style.minWidth == "0px"){
        sidebarDiv.style.minWidth = "300px";
        sidebarDiv.style.width = "300px";
    }
    else{
        sidebarDiv.style.minWidth = "0px";
        sidebarDiv.style.width = "0px";
    }
}

requestAvailableTasks(true);

let referenceFileContainerDiv = document.getElementById("referenceFileContainer");
referenceFileContainerDiv.addEventListener('click', function(){
    referenceFileDownload(currentlyDisplayedTaskId, currentlyDisplayedReferenceFileName);
}); 

let sidebarTopButton = document.getElementById("sidebarTopButton");
sidebarTopButton.addEventListener('click', function(){
    displayTaskCreationForm();
}); 

setInterval(requestAvailableTasks, 10000);
