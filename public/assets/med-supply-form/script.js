document.getElementById("file-upload").addEventListener("change", function () {
  const fileName = this.files[0].name;
  document.getElementById("file-name").textContent = fileName;
});

document.getElementById("signup").addEventListener("submit", function (event) {
  // Email validation
  const email = document.getElementById("email").value;
  const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
  if (!emailPattern.test(email)) {
    alert("Please enter a valid email address.");
    event.preventDefault();
    return false;
  }

  // Phone number validation (assuming Eimart phone numbers follow a specific pattern)
  const phone = document.getElementById("phone").value;
  const phonePattern = /^\d{9}$/; // 9 digits following the +971
  if (!phonePattern.test(phone)) {
    alert("Please enter a valid phone number (9 digits after +971).");
    event.preventDefault();
    return false;
  }

  // Password validation
  const password = document.getElementById("password").value;
  const confirmPassword = document.getElementById(
    "password-confirmation"
  ).value;
  if (password.length < 8) {
    alert("Password must be at least 8 characters long.");
    event.preventDefault();
    return false;
  }
  if (password !== confirmPassword) {
    alert("Passwords do not match.");
    event.preventDefault();
    return false;
  }

  // Additional validation can go here
});
