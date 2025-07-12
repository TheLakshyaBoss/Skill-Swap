<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $name = $_POST["name"];
  $email = $_POST["email"];
  $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
  $availability = $_POST["availability"];
  $location = $_POST["location"];
  $visibility = $_POST["profile_visibility"];
  $skills_offered = $_POST["skills_offered"];
  $skills_wanted = $_POST["skills_wanted"];

  $pic = '';
  if ($_FILES["profile_pic"]["error"] == 0) {
      $filename = uniqid() . "-" . basename($_FILES["profile_pic"]["name"]);
      $target = "uploads/" . $filename;
      move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target);
      $pic = $target;
  }

  $stmt = $conn->prepare("INSERT INTO users (name, email, password, profile_pic, skills_offered, skills_wanted, availability, location, profile_visibility) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("sssssssss", $name, $email, $password, $pic, $skills_offered, $skills_wanted, $availability, $location, $visibility);
  $stmt->execute();

  echo "<script>alert('Signup successful!'); window.location='../index.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create an Account</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Parkinsans:wght@300..800&display=swap');

* {
  box-sizing: border-box;
  font-family: "Parkinsans", sans-serif;
}

body {
  margin: 0;
  padding: 50px 20px;
  background-color: #0d1117;
  color: #c9d1d9;
  display: flex;
  justify-content: center;
  align-items: start;
  min-height: 100vh;
}

.container {
  background-color: #161b22;
  padding: 3rem 2rem 2rem;
  border-radius: 12px;
  width: 100%;
  max-width: 600px;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.6);
  position: relative;
}

h1 {
  text-align: center;
  margin-bottom: 2.5rem;
  font-size: 2rem;
  font-weight: 600;
}

label {
  display: block;
  margin: 12px 0 6px;
  font-size: 0.95rem;
}

input, select {
  width: 100%;
  padding: 10px;
  background-color: #0d1117;
  color: #c9d1d9;
  border: 1px solid #30363d;
  border-radius: 6px;
  margin-bottom: 10px;
}

.tag-box {
  display: flex;
  flex-wrap: wrap;
  border: 1px solid #30363d;
  background: #0d1117;
  padding: 8px;
  border-radius: 6px;
  min-height: 44px;
  cursor: text;
  margin-bottom: 10px;
}

.tag-box input {
  border: none;
  background: transparent;
  color: #c9d1d9;
  padding: 6px;
  flex: 1;
  outline: none;
}

.tag {
  background: #238636;
  padding: 5px 10px;
  border-radius: 16px;
  margin: 4px;
  font-size: 14px;
}

.tag span {
  margin-left: 8px;
  cursor: pointer;
  font-weight: bold;
}

.form-row {
  display: flex;
  gap: 10px;
}

button {
  width: 100%;
  background-color: #238636;
  color: white;
  font-weight: bold;
  border: none;
  border-radius: 6px;
  padding: 12px;
  font-size: 1rem;
  margin-top: 15px;
  cursor: pointer;
  transition: 0.2s ease;
}

button:hover {
  background-color: #2ea043;
}

.profile-pic-wrapper {
  display: flex;
  justify-content: center;
  margin-bottom: 30px;
}

.profile-pic-box {
  text-align: center;
  justify-content: center;
}

.profile-pic-box img {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid #30363d;
  background: #0d1117;
  display: block;
  margin: 0 auto 10px;
}

.profile-pic-box input[type="file"] {
  font-size: 13px;
  background: transparent;
  color: #c9d1d9;
  border: none;
  text-align: center;
  display: block;
  margin: 0 auto;
}

a {
    color: #58a6ff;
    text-decoration: none;
    display: block;
    margin-top: 10px;
    text-align: center;
}

@media(max-width: 650px) {
  .container {
    padding-top: 3rem;
  }

  .profile-pic-box {
    position: static;
    margin-bottom: 20px;
  }

  .profile-pic-box img {
    width: 100px;
    height: 100px;
  }

  .profile-pic-box input[type="file"] {
    width: 100px;
  }
}
</style>
</head>
<body>

<form class="container" method="POST" enctype="multipart/form-data">

  <h1>Create an Account</h1>

  <div class="profile-pic-wrapper">
    <div class="profile-pic-box">
      <img id="preview" src="https://i.pinimg.com/474x/4c/dc/c9/4cdcc9e8902048b9e0db0b0f6298eeb9.jpg" alt="Profile Picture">
      <input type="file" name="profile_pic" accept="image/*" onchange="loadFile(event)">
    </div>
  </div>

    <label>Full Name</label>
    <input name="name" required>

    <label>Email</label>
    <input type="email" name="email" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <label>Skills Offered</label>

    <div class="tag-box" id="offeredBox">
      <input type="text" id="skillsOfferedInput" placeholder="Press Enter to add">
    </div>

    <input type="hidden" name="skills_offered" id="skillsOfferedHidden">

    <label>Skills Wanted</label>
    <div class="tag-box" id="wantedBox">
      <input type="text" id="skillsWantedInput" placeholder="Press Enter to add">
    </div>
    <input type="hidden" name="skills_wanted" id="skillsWantedHidden">

    <div class="form-row">
      <div style="flex:1;">
        <label>Availability</label>
        <select name="availability" required>
          <option value="weekend">Weekend</option>
          <option value="whole_week">Whole Week</option>
        </select>
      </div>
      <div style="flex:1;">
        <label>Location</label>
        <input name="location" required>
      </div>
    </div>

    <label>Profile Visibility</label>
    <select name="profile_visibility" required>
      <option value="public">Public</option>
      <option value="private">Private</option>
    </select>

    <button type="submit">Sign Up</button>

    <a href="login.php">Already have an account? Login</a>

  </form>

  <script>
    const loadFile = event => {
      const img = document.getElementById("preview");
      img.src = URL.createObjectURL(event.target.files[0]);
    };

    function tagInput(boxId, hiddenInputId) {
      const box = document.getElementById(boxId);
      const input = box.querySelector("input");
      const hidden = document.getElementById(hiddenInputId);
      const tags = [];

      input.addEventListener("keydown", function(e) {
        if (e.key === "Enter" && input.value.trim() !== "") {
          e.preventDefault();
          const tag = input.value.trim();
          if (!tags.includes(tag)) {
            tags.push(tag);
            const span = document.createElement("span");
            span.className = "tag";
            span.textContent = tag;
            const remove = document.createElement("span");
            remove.textContent = "×";
            remove.onclick = () => {
              tags.splice(tags.indexOf(tag), 1);
              box.removeChild(span);
              updateHidden();
            };
            span.appendChild(remove);
            box.insertBefore(span, input);
            input.value = "";
            updateHidden();
          }
        }
      });

      function updateHidden() {
        hidden.value = tags.join(",");
      }
    }

    tagInput("offeredBox", "skillsOfferedHidden");
    tagInput("wantedBox", "skillsWantedHidden");
  </script>
</body>
</html>
