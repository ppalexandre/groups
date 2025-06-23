async function submitForm(){
    let userName = document.getElementById("username").value;
    let password = document.getElementById("password").value;
    let repassword = document.getElementById("repassword").value;

    if(password === repassword){

        if (userName != "" && password != ""){
            let formData = new FormData();
            formData.append("userName", userName);
            formData.append("password", password);
            var createUserRequest = await fetch('../php/handleRegister.php', {
                method: 'POST',
                body: formData
            })
            .catch((error) => console.error('ERROR:', error));
        }
        let response = await createUserRequest.text();
        if (response != ""){
            response = JSON.parse(response); 
            if (response.login === true){
                window.location.href = "../pages/tasks.php";
            }
            let errorBox = document.getElementById("errorBox");
            errorBox.innerText = response.responseMessage;
        }
        clearForm();
    }
    else{
        let errorBox = document.getElementById("errorBox");
        errorBox.innerText = "Passwords don't match!";
    }
} 

function clearForm() {
    let usernameElement = document.getElementById("username");
    let passwordElement = document.getElementById("password");
    let repasswordElement = document.getElementById("repassword");
    usernameElement.value = "";
    passwordElement.value = "";
    repasswordElement.value = "";
}
