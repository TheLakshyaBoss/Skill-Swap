<?php 
include "app/db.php";
session_start();

$logged_in = false;
if (isset($_SESSION["user_id"])) {
    $logged_in = true;    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skill Swap Platform</title>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Parkinsans:wght@300..800&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Parkinsans", sans-serif;
        }

        body {
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
            
            <?php if (!$logged_in) { ?>
                <button class="login-btn" onclick="location.href='app/login.php'"> Login</button>
            <?php } ?>
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

        <?php

            // Fetch only public profiles
            $result = $conn->query("SELECT name, profile_pic, skills_offered, skills_wanted FROM users WHERE profile_visibility = 'public'");

            echo '<div class="profiles-grid" id="profilesGrid">';

            while ($row = $result->fetch_assoc()) {
                $name = htmlspecialchars($row['name']);
                $pic = $row['profile_pic'] ? htmlspecialchars($row['profile_pic']) : 'https://via.placeholder.com/100?text=User';
                
                $offered = array_filter(array_map('trim', explode(',', $row['skills_offered'])));
                $wanted = array_filter(array_map('trim', explode(',', $row['skills_wanted'])));

                // Random rating for now
                $rating = number_format(rand(25, 50) / 10, 1);
                
                echo '<div class="profile-card">';
                echo    '<div class="profile-photo"><img src="' . $pic . '" alt="' . $name . '" style="width:100%; border-radius: 8px;"></div>';
                echo    '<div class="profile-info">';
                echo        '<div class="profile-name">' . $name . '</div>';

                // Skills Offered
                echo '<div class="skills-section">';
                echo     '<div class="skills-label">Skills Offered →</div>';
                echo     '<div class="skills-tags">';
                foreach ($offered as $skill) {
                    echo '<span class="skill-tag">' . htmlspecialchars($skill) . '</span>';
                }
                echo     '</div>';
                echo '</div>';

                // Skills Wanted
                echo '<div class="skills-section">';
                echo     '<div class="skills-label">Skills Wanted →</div>';
                echo     '<div class="skills-tags">';
                foreach ($wanted as $skill) {
                    echo '<span class="skill-tag">' . htmlspecialchars($skill) . '</span>';
                }
                echo     '</div>';
                echo '</div>';

                echo    '</div>'; // .profile-info

                echo    '<div class="profile-actions">';
                echo        '<button class="request-btn" onclick="sendRequest(\'' . $name . '\')">Request</button>';
                echo        '<div class="rating">rating: ' . $rating . '/5</div>';
                echo    '</div>';
                echo '</div>';
            }

            echo '</div>';
        ?>

    </div>
</body>
</html>