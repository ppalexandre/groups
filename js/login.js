async function submitForm(){
    let userName = document.getElementById("username").value;
    let password = document.getElementById("password").value;

    if (userName != "" && password != ""){
        let formData = new FormData();
        formData.append("userName", userName);
        formData.append("password", password);
        var loginRequest = await fetch('../php/handleLogin.php', {
            method: 'POST',
            body: formData
        })
        .catch((error) => console.error('ERROR:', error));
        let response = await loginRequest.text();

        if (response != ""){
            response = JSON.parse(response); 
            if (response.login == true){
                window.location.href = "../pages/tasks.php";
            }
            let errorBox = document.getElementById("errorBox");
            errorBox.innerText = response.responseMessage;
        }
        clearForm();
    }
} 

function clearForm(){
    let usernameElement = document.getElementById("username");
    let passwordElement = document.getElementById("password");
    usernameElement.value = "";
    passwordElement.value = "";
}

