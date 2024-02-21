document.addEventListener("DOMContentLoaded", function() {
    const usernameInput = document.querySelector('input[name="username"]');
    const emailInput = document.querySelector('input[name="email"]');
    const passwordInput = document.querySelector('input[name="password"]');
    const usernameError = document.getElementById("username-error");
    const emailError = document.getElementById("email-error");
    const passwordError = document.getElementById("password-error");
   
    usernameInput.addEventListener("keyup", function() {
        const username = this.value;
        if (username.length < 8 || !/^[a-zA-Z0-9_]+$/.test(username)) {
            usernameError.textContent = "No special characters allowed and minimum length should be 8 characters";
        } else {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4) {
                    console.log("Response Text:", this.responseText); 
                    if (this.status == 200) {
                        if (this.responseText === "exists") {
                            usernameError.textContent = "Username already exists!";
                        } else if (this.responseText === "accepted") {
                            usernameError.textContent = "Accepted";
                        } else if (this.responseText === "special_characters_not_allowed") {
                            usernameError.textContent = "No special characters allowed and minimum length should be 8 characters";
                        } else {
                            usernameError.textContent = "Unexpected response: " + this.responseText; 
                        }
                    } else {
                        usernameError.textContent = "Error: " + this.status;
                    }
                }
            };
            xhttp.open("POST", "check.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("username=" + username);
        }
    });    
  
   emailInput.addEventListener("keyup", function() {
        const email = this.value;
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            emailError.textContent = "Invalid email address.";
        } else {
            emailError.textContent = "";
        }
    });

  

    passwordInput.addEventListener("keyup", function() {
        const password = this.value;
        if (password.length < 8 || !/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$/.test(password)) {
            passwordError.textContent = "Password must be at least 8 characters long and contain at least one letter and one digit.";
        } else {
            passwordError.textContent = "";
        }
    });
});
