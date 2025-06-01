function displayTask(){
    let centerContainer = document.getElementById("centerContainer");
    let taskDiv = document.getElementById("task");
    let taskTitleDiv = document.getElementById("taskTitle");
    let taskDateDiv = document.getElementById("taskDate");
    let taskBodyDiv = document.getElementById("taskBody");

    // temporary variables
    let taskTitle = "Placeholder Title";
    let taskDate = "Placeholder Date";
    let taskBody = "Lorem ipsum dolor sit amet consectetur adipiscing elit quisque faucibus ex sapien vitae pellentesque sem placerat in id cursus mi pretium tellus duis convallis tempus leo eu aenean sed diam urna tempor pulvinar vivamus fringilla lacus nec metus bibendum egestas iaculis massa nisl malesuada lacinia integer nunc posuere ut hendrerit semper vel class aptent taciti sociosqu ad litora torquent per conubia nostra inceptos himenaeos orci varius natoque penatibus et magnis dis parturient montes nascetur ridiculus mus donec rhoncus eros lobortis nulla molestie mattis scelerisque maximus eget fermentum odio phasellus non purus est efficitur laoreet mauris pharetra vestibulum fusce dictum risus.";

    taskTitleDiv.innerText = taskTitle;
    taskTitleDiv.innerHTML += "<br>";
    taskBodyDiv.innerText = taskBody;
    taskDateDiv.innerText = taskDate;
}

async function taskFileUpload(){
    let file = document.getElementById("taskFileUpload").files[0];
    let formData = new FormData();
    formData.append("file", file);
    if (file != ""){
        fetch('../php/handleTaskCompletion.php', {
            method: 'POST',
            body: formData
        })
            .then((response) => response.text())
            .then((response) => console.log(response))
            .catch((error) => console.error('ERROR:', error));
    }
} 

displayTask();
