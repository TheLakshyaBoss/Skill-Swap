<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="style.css">

    <title>Skill Swap Platform</title>    
</head>
<body>
    <div class="container">
        
        <?php include "app/header.php" ; ?>

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
                    <div class="profile-name">Shubham Patel</div>
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