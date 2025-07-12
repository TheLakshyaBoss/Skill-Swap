<?php
session_start();
include "db.php";

$id = $_GET["id"];
$stmt = $conn->prepare("SELECT name, profile_pic, skills_offered, skills_wanted FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $name = htmlspecialchars($row['name']);
    $profile_pic = "../" . $row['profile_pic'] ?: "https://via.placeholder.com/120?text=User";
    
    $skills_offered = array_filter(array_map('trim', explode(",", $row['skills_offered'])));
    $skills_wanted = array_filter(array_map('trim', explode(",", $row['skills_wanted'])));
}

// Getting current user's offered skills
if (isset($_SESSION['user_id'])) {

    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT skills_offered FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $my_skills = array_filter(array_map('trim', explode(',', $row['skills_offered'])));
    }
    $stmt->close();
}

// Getting skills_wanted of the user offering
$stmt = $conn->prepare("SELECT skills_wanted FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $their_skills = array_filter(array_map('trim', explode(',', $row['skills_wanted'])));
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skill Swap Platform</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #e2e8f0;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            margin-bottom: 40px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #60a5fa;
        }

        .nav-buttons {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .nav-btn {
            padding: 8px 16px;
            border: 2px solid #475569;
            border-radius: 8px;
            background: transparent;
            color: #e2e8f0;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .nav-btn:hover {
            border-color: #60a5fa;
            background: rgba(96, 165, 250, 0.1);
        }

        .profile-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(45deg, #8b5cf6, #06b6d4);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .profile-icon:hover {
            transform: scale(1.1);
        }

        /* Main Content */
        .main-content {
            display: flex;
            gap: 40px;
            align-items: flex-start;
        }

        .user-card {
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid #334155;
            border-radius: 20px;
            padding: 30px;
            flex: 1;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .user-name {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 30px;
            color: #f1f5f9;
        }

        .skills-section {
            margin-bottom: 30px;
        }

        .skills-title {
            font-size: 18px;
            margin-bottom: 15px;
            color: #cbd5e1;
        }

        .skills-list {
            background: rgba(15, 23, 42, 0.3);
            border-radius: 12px;
            padding: 15px;
            min-height: 60px;
            color: #e2e8f0;
            font-weight: 500;
            line-height: 1.6;
            border-left: 4px solid #60a5fa;
        }

        .rating-section {
            background: rgba(15, 23, 42, 0.4);
            border: 1px solid #475569;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
        }

        .rating-title {
            font-size: 18px;
            color: #cbd5e1;
            margin-bottom: 10px;
        }

        .profile-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .profile-photo {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            border: 3px solid #475569;
            background: linear-gradient(45deg, #1e293b, #334155);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .profile-photo:hover {
            border-color: #60a5fa;
            transform: scale(1.05);
        }

        .request-btn {
            background: linear-gradient(45deg, #10b981, #059669);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .request-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        /* Popup Overlay */
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .popup-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .popup {
            background: rgba(30, 41, 59, 0.95);
            border: 1px solid #475569;
            border-radius: 20px;
            padding: 30px;
            width: 90%;
            max-width: 500px;
            backdrop-filter: blur(20px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            transform: scale(0.9) translateY(20px);
            transition: all 0.3s ease;
        }

        .popup-overlay.active .popup {
            transform: scale(1) translateY(0);
        }

        .popup-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 25px;
            color: #f1f5f9;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: #cbd5e1;
            font-size: 14px;
        }

        .form-select {
            width: 100%;
            padding: 12px;
            border: 1px solid #475569;
            border-radius: 8px;
            background: rgba(15, 23, 42, 0.8);
            color: #e2e8f0;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-select:focus {
            outline: none;
            border-color: #60a5fa;
            box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.1);
        }

        .form-textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #475569;
            border-radius: 8px;
            background: rgba(15, 23, 42, 0.8);
            color: #e2e8f0;
            font-size: 14px;
            min-height: 100px;
            resize: vertical;
            transition: all 0.3s ease;
        }

        .form-textarea:focus {
            outline: none;
            border-color: #60a5fa;
            box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.1);
        }

        .form-textarea::placeholder {
            color: #64748b;
        }

        .popup-buttons {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 25px;
        }

        .btn-cancel {
            padding: 10px 20px;
            border: 2px solid #475569;
            border-radius: 8px;
            background: transparent;
            color: #e2e8f0;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            border-color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
        }

        .btn-submit {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(45deg, #3b82f6, #1d4ed8);
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        /* Animation for popup */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }
        @media (max-width: 768px) {
            .main-content {
                flex-direction: column;
                gap: 20px;
            }
            
            .header {
                flex-direction: column;
                gap: 15px;
            }
            
            .nav-buttons {
                order: -1;
            }
            
            .popup {
                width: 95%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">Skill Swap Platform</div>
            <div class="nav-buttons">
                <button class="nav-btn">Swap request</button>
                <button class="nav-btn">Home</button>
                <div class="profile-icon">👤</div>
            </div>
        </div>

    <div class="main-content">
        <div class="user-card">
            <h2 class="user-name"><?= $name ?></h2>

            <div class="skills-section">
                <h3 class="skills-title">Skills Offered</h3>
                <div class="skills-list">
                    <?php foreach ($skills_offered as $skill): ?>
                        <span style="display: inline-block; background: rgba(96, 165, 250, 0.2); color: #60a5fa; padding: 4px 12px; border-radius: 20px; margin: 2px; font-size: 14px;">
                            <?= htmlspecialchars($skill) ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="skills-section">
                <h3 class="skills-title">Skills Wanted</h3>
                <div class="skills-list">
                    <?php foreach ($skills_wanted as $skill): ?>
                        <span style="display: inline-block; background: rgba(16, 185, 129, 0.2); color: #10b981; padding: 4px 12px; border-radius: 20px; margin: 2px; font-size: 14px;">
                            <?= htmlspecialchars($skill) ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="rating-section">
                <h3 class="rating-title">Rating and Feedback</h3>
                <div style="font-size: 24px; margin: 10px 0;">⭐⭐⭐⭐⭐</div>
                <div style="color: #94a3b8;">4.8/5 (24 reviews)</div>
            </div>
        </div>

        <!-- Profile Section -->
        <div class="profile-section">
            <div class="profile-photo">
                <img src="<?= $profile_pic ?>" alt="Profile Picture" style="width: 200px; height: 200px; border-radius: 50%; object-fit: cover;">
            </div>
            <button class="request-btn" onclick="openPopup()">Request</button>
        </div>
    </div>


    <!-- Popup Overlay -->
    <div class="popup-overlay" id="popupOverlay" onclick="closePopup(event)">
        <div class="popup" onclick="event.stopPropagation()">
            <h2 class="popup-title">Choose one of your offered skills</h2>
    
            <form method="post" action="request.php">
                <input type="hidden" name="to_user_id" value="<?php echo $id; ?>">
                <div class="form-group">
                    <label class="form-label">Choose one of your offered skills</label>
                    <select class="form-select" name="my_skill">
                        <option value="">Select a skill...</option>
                        <?php foreach ($my_skills as $skill): ?>
                            <option value="<?= htmlspecialchars($skill) ?>"><?= htmlspecialchars($skill) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Choose one of their wanted skills</label>
                    <select class="form-select" name="their_skill">
                        <option value="">Select a skill...</option>
                        <?php foreach ($their_skills as $skill): ?>
                            <option value="<?= htmlspecialchars($skill) ?>"><?= htmlspecialchars($skill) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Message</label>
                    <textarea class="form-textarea" placeholder="Write your message here..." name="message"></textarea>
                </div>
                
                <div class="popup-buttons">
                    <button class="btn-cancel" onclick="closePopup()">Cancel</button>
                    <button type="submit" class="btn-submit">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openPopup() {
            const overlay = document.getElementById('popupOverlay');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closePopup(event) {
            if (event && event.target !== event.currentTarget) {
                return;
            }
            const overlay = document.getElementById('popupOverlay');
            overlay.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        function submitRequest() {
            // Here you would typically send the request to your backend
            alert('Request submitted successfully!');
            closePopup();
        }

        // Close popup on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePopup();
            }
        });
    </script>
</body>
</html>