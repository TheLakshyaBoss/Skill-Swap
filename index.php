<?php 

$url = "app/login.php";
if (isset($_SESSION["user_id"])) {
    $url = "";
}

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
            background-color: #1a1a1a;
            color: #ffffff;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            border-bottom: 1px solid #333;
            margin-bottom: 30px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #ffffff;
        }

        .login-btn {
            background-color: transparent;
            color: #ffffff;
            border: 2px solid #ffffff;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .login-btn:hover {
            background-color: #ffffff;
            color: #1a1a1a;
        }

        .search-section {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            justify-content: center;
        }

        .availability-filter {
            background-color: #333;
            color: #ffffff;
            border: 1px solid #555;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-input {
            flex: 1;
            max-width: 400px;
            padding: 10px 15px;
            border: 1px solid #555;
            border-radius: 5px;
            background-color: #333;
            color: #ffffff;
        }

        .search-btn {
            background-color: #4a9eff;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .search-btn:hover {
            background-color: #357abd;
        }

        .profiles-grid {
            display: grid;
            gap: 20px;
            margin-bottom: 30px;
        }

        .profile-card {
            background-color: #2a2a2a;
            border: 1px solid #444;
            border-radius: 15px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .profile-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #444;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #aaa;
            font-size: 12px;
            flex-shrink: 0;
        }

        .profile-info {
            flex: 1;
        }

        .profile-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .skills-section {
            margin-bottom: 10px;
        }

        .skills-label {
            color: #4a9eff;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .skills-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .skill-tag {
            background-color: #444;
            color: #ffffff;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 12px;
            border: 1px solid #555;
        }

        .profile-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
            align-items: flex-end;
        }

        .request-btn {
            background-color: #4a9eff;
            color: #ffffff;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .request-btn:hover {
            background-color: #357abd;
        }

        .rating {
            color: #ffd700;
            font-size: 14px;
        }

        .page-btn {
            background-color: #333;
            color: #ffffff;
            border: 1px solid #555;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .page-btn:hover,
        .page-btn.active {
            background-color: #4a9eff;
            border-color: #4a9eff;
        }

        .page-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        @media (max-width: 768px) {
            .search-section {
                flex-direction: column;
            }
            
            .profile-card {
                flex-direction: column;
                text-align: center;
            }
            
            .profile-actions {
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">Skill Swap Platform</div>
            <button class="login-btn" onclick="login()">Login</button>
        </header>

        <div class="search-section">
            <select class="availability-filter" id="availabilityFilter">
                <option value="">Availability</option>
                <option value="available">Available Now</option>
                <option value="busy">Busy</option>
                <option value="offline">Offline</option>
            </select>
            <input type="text" class="search-input" placeholder="Search for skills or people..." id="searchInput">
            <button class="search-btn" onclick="searchProfiles()">Search</button>
        </div>

        <div class="profiles-grid" id="profilesGrid">
            <div class="profile-card">
                <div class="profile-photo">Profile Photo</div>
                <div class="profile-info">
                    <div class="profile-name">Marc Demo</div>
                    <div class="skills-section">
                        <div class="skills-label">Skills Offered →</div>
                        <div class="skills-tags">
                            <span class="skill-tag">Java Script</span>
                            <span class="skill-tag">Python</span>
                        </div>
                    </div>
                    <div class="skills-section">
                        <div class="skills-label">Skill wanted →</div>
                        <div class="skills-tags">
                            <span class="skill-tag">Photoshop</span>
                            <span class="skill-tag">Graphic design</span>
                        </div>
                    </div>
                </div>
                <div class="profile-actions">
                    <button class="request-btn" onclick="sendRequest('Marc Demo')">Request</button>
                    <div class="rating">rating: 3.9/5</div>
                </div>
            </div>

            <div class="profile-card">
                <div class="profile-photo">Profile Photo</div>
                <div class="profile-info">
                    <div class="profile-name">Michell</div>
                    <div class="skills-section">
                        <div class="skills-label">Skills Offered →</div>
                        <div class="skills-tags">
                            <span class="skill-tag">Java Script</span>
                            <span class="skill-tag">Python</span>
                        </div>
                    </div>
                    <div class="skills-section">
                        <div class="skills-label">Skill wanted →</div>
                        <div class="skills-tags">
                            <span class="skill-tag">Photoshop</span>
                            <span class="skill-tag">Graphic design</span>
                        </div>
                    </div>
                </div>
                <div class="profile-actions">
                    <button class="request-btn" onclick="sendRequest('Michell')">Request</button>
                    <div class="rating">rating: 2.5/5</div>
                </div>
            </div>

            <div class="profile-card">
                <div class="profile-photo">Profile Photo</div>
                <div class="profile-info">
                    <div class="profile-name">Joe Wills</div>
                    <div class="skills-section">
                        <div class="skills-label">Skills Offered →</div>
                        <div class="skills-tags">
                            <span class="skill-tag">Java Script</span>
                            <span class="skill-tag">Python</span>
                        </div>
                    </div>
                    <div class="skills-section">
                        <div class="skills-label">Skill wanted →</div>
                        <div class="skills-tags">
                            <span class="skill-tag">Photoshop</span>
                            <span class="skill-tag">Graphic design</span>
                        </div>
                    </div>
                </div>
                <div class="profile-actions">
                    <button class="request-btn" onclick="sendRequest('Joe Wills')">Request</button>
                    <div class="rating">rating: 4.0/5</div>
                </div>
            </div>
        </div>
</body>
</html>