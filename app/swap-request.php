<?php
include "db.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Not logged in.");
}

$user_id = $_SESSION['user_id'];

// Fetch user profile picture
$profilePic = "https://via.placeholder.com/40?text=U";
$picQuery = $conn->prepare("SELECT profile_pic FROM users WHERE id = ?");
$picQuery->bind_param("i", $user_id);
$picQuery->execute();
$picResult = $picQuery->get_result();
if ($row = $picResult->fetch_assoc()) {
    if (!empty($row['profile_pic'])) {
        $profilePic = $row['profile_pic'];
    }
}
$picQuery->close();

// Fetch requests
$stmt = $conn->prepare("
    SELECT r.*, 
           u1.name AS from_user, u1.profile_pic AS from_pic,
           u2.name AS to_user, u2.profile_pic AS to_pic
    FROM requests r
    JOIN users u1 ON r.from_user_id = u1.id
    JOIN users u2 ON r.to_user_id = u2.id
    WHERE r.from_user_id = ? OR r.to_user_id = ?
    ORDER BY r.requested_at DESC
");
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Swap Requests</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #0d1117;
      color: #c9d1d9;
    }

    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #161b22;
      padding: 12px 24px;
      border-bottom: 1px solid #30363d;
    }

    .navbar h1 {
      font-size: 20px;
      color: #ffffff;
    }

    .navbar-right {
      display: flex;
      align-items: center;
      gap: 20px;
    }

    .navbar-right a {
      color: #58a6ff;
      text-decoration: none;
    }

    .navbar-right img {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      border: 2px solid #30363d;
    }

    .filter-bar {
      display: flex;
      gap: 12px;
      padding: 16px 24px;
      align-items: center;
    }

    select, input[type="text"] {
      background: #161b22;
      border: 1px solid #30363d;
      color: #c9d1d9;
      padding: 8px 12px;
      border-radius: 6px;
    }

    .request-card {
      display: flex;
      justify-content: space-between;
      background: #161b22;
      border: 1px solid #30363d;
      border-radius: 10px;
      padding: 20px;
      margin: 16px 24px;
    }

    .profile-photo {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: #2f2f2f;
      object-fit: cover;
    }

    .left-section {
      display: flex;
      gap: 16px;
    }

    .details {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .skill-label {
      font-size: 14px;
      margin-top: 4px;
    }

    .skill-tag {
      display: inline-block;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 13px;
      margin: 3px 4px 0 0;
    }

    .skill-offered { background: rgba(96,165,250,0.2); color: #60a5fa; }
    .skill-wanted { background: rgba(16,185,129,0.2); color: #10b981; }

    .rating {
      font-size: 13px;
      color: #8b949e;
    }

    .status {
      font-weight: bold;
      font-size: 15px;
      margin-bottom: 6px;
    }

    .status-pending { color: #e3b341; }
    .status-accepted { color: #22c55e; }
    .status-rejected { color: #ef4444; }

    .actions button {
      padding: 6px 12px;
      margin-right: 6px;
      font-size: 14px;
      border-radius: 6px;
      border: none;
      cursor: pointer;
    }

    .accept-btn { background-color: #22c55e; color: white; }
    .reject-btn { background-color: #ef4444; color: white; }

    .pagination {
      text-align: center;
      margin: 20px;
      color: #94a3b8;
    }
  </style>
</head>
<body>

<div class="navbar">
  <h1>Skill Swap Platform</h1>
  <div class="navbar-right">
    <a href="#">Home</a>
    <img src="<?= $profilePic ?>" alt="Profile Photo">
  </div>
</div>

<div class="filter-bar">
  <select>
    <option value="">All</option>
    <option value="pending">Pending</option>
    <option value="accepted">Accepted</option>
    <option value="rejected">Rejected</option>
  </select>
  <input type="text" placeholder="Search...">
</div>

<?php while ($row = $result->fetch_assoc()):
    $is_sender = $row['from_user_id'] == $user_id;
    $partner_name = $is_sender ? $row['to_user'] : $row['from_user'];
    $partner_pic = $is_sender ? $row['to_pic'] : $row['from_pic'];
    $status_class = "status-" . strtolower($row['status']);
    ?>

    <div class="request-card">
      <div class="left-section">
        <img class="profile-photo" src="<?= $partner_pic ?: 'https://via.placeholder.com/80?text=User' ?>" alt="Profile">
        <div class="details">
          <div style="font-size: 18px;"><?= htmlspecialchars($partner_name) ?></div>
          <div class="skill-label">Skills Offered ⇒ 
            <span class="skill-tag skill-offered"><?= htmlspecialchars($row['offered_skill']) ?></span>
          </div>
          <div class="skill-label">Skill Wanted ⇒ 
            <span class="skill-tag skill-wanted"><?= htmlspecialchars($row['wanted_skill']) ?></span>
          </div>
          <div class="rating">Rating: 3.9/5</div>
        </div>
      </div>
      <div class="details" style="text-align: right;">
        <div class="status <?= $status_class ?>">Status: <?= ucfirst($row['status']) ?></div>
        <?php if ($row['status'] == 'pending' && !$is_sender): ?>
            <div class="actions">
              <button class="accept-btn">Accept</button>
              <button class="reject-btn">Reject</button>
            </div>
        <?php endif; ?>
      </div>
    </div>

<?php endwhile; ?>
</body>
</html>
