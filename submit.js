function validateInput(){
    const userInput = document.getElementById("username").value;
    const passInput = document.getElementById("password").value;

    const correctUN = "Aron";
    const correctPSW = "12345678";

    if(userInput == correctUN && passInput == correctPSW){
        window.location.href = "index.html";
    }
    else{
        window.alert("Incorrect Password");
    }
}