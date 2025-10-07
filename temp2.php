<?php
// =====================================
// Simple PHP-MySQL Portal CRUD Project
// =====================================

// Database Connection
$servername = "localhost";
$username = "root"; // default for XAMPP/WAMP
$password = "";
$database = "pra8";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// =====================================
// CREATE TABLE (run once manually in phpMyAdmin or uncomment below)
// =====================================
// $conn->query("CREATE TABLE events (id INT AUTO_INCREMENT PRIMARY KEY, event_name VARCHAR(100), event_date DATE, description TEXT)");

// =====================================
// INSERT Operation
// =====================================
if (isset($_POST['submit'])) {
  $name = $_POST['event_name'];
  $date = $_POST['event_date'];
  $desc = $_POST['description'];

  $sql = "INSERT INTO events (event_name, event_date, description) VALUES ('$name', '$date', '$desc')";
  if ($conn->query($sql)) {
    echo "<p style='color:green;'>✅ Event added successfully!</p>";
  } else {
    echo "<p style='color:red;'>❌ Error: " . $conn->error . "</p>";
  }
}

// =====================================
// DELETE Operation
// =====================================
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $conn->query("DELETE FROM events WHERE id=$id");
  echo "<p style='color:red;'>🗑️ Event deleted successfully!</p>";
}

// =====================================
// UPDATE Operation
// =====================================
if (isset($_POST['update'])) {
  $id = $_POST['id'];
  $name = $_POST['event_name'];
  $date = $_POST['event_date'];
  $desc = $_POST['description'];

  $sql = "UPDATE events SET event_name='$name', event_date='$date', description='$desc' WHERE id=$id";
  if ($conn->query($sql)) {
    echo "<p style='color:green;'>✅ Event updated successfully!</p>";
  } else {
    echo "<p style='color:red;'>❌ Error updating: " . $conn->error . "</p>";
  }
}

// Fetch record for editing
$editData = null;
if (isset($_GET['edit'])) {
  $id = $_GET['edit'];
  $res = $conn->query("SELECT * FROM events WHERE id=$id");
  $editData = $res->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Portal - PHP MySQL CRUD</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f8f9fa;
      margin: 40px;
    }
    h2 {
      color: #333;
      text-align: center;
    }
    form {
      background: #fff;
      padding: 20px;
      margin: 20px auto;
      width: 400px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    input, textarea {
      width: 100%;
      padding: 8px;
      margin-top: 6px;
      margin-bottom: 10px;
    }
    table {
      width: 90%;
      margin: 20px auto;
      border-collapse: collapse;
      background: #fff;
    }
    table, th, td {
      border: 1px solid #ccc;
      text-align: center;
      padding: 10px;
    }
    th {
      background: #007bff;
      color: #fff;
    }
    a {
      text-decoration: none;
      color: #007bff;
    }
    .dashboard {
      background: #e9ecef;
      padding: 15px;
      border-radius: 10px;
      width: 60%;
      margin: 20px auto;
    }
  </style>
</head>
<body>

<h2>🎓 College Portal - Event Management System</h2>

<!-- Event Form -->
<form method="POST">
  <h3><?php echo isset($editData) ? "✏️ Update Event" : "➕ Add New Event"; ?></h3>
  <input type="hidden" name="id" value="<?php echo $editData['id'] ?? ''; ?>">
  Event Name: <input type="text" name="event_name" required value="<?php echo $editData['event_name'] ?? ''; ?>"><br>
  Event Date: <input type="date" name="event_date" required value="<?php echo $editData['event_date'] ?? ''; ?>"><br>
  Description:<br>
  <textarea name="description" required><?php echo $editData['description'] ?? ''; ?></textarea><br>
  
  <?php if ($editData) { ?>
    <input type="submit" name="update" value="Update Event">
  <?php } else { ?>
    <input type="submit" name="submit" value="Add Event">
  <?php } ?>
</form>

<!-- Display All Events -->
<h2>📋 All Events</h2>
<table>
  <tr>
    <th>ID</th>
    <th>Event Name</th>
    <th>Date</th>
    <th>Description</th>
    <th>Action</th>
  </tr>
  <?php
  $result = $conn->query("SELECT * FROM events ORDER BY id DESC");
  while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['event_name']}</td>
            <td>{$row['event_date']}</td>
            <td>{$row['description']}</td>
            <td>
              <a href='?edit={$row['id']}'>✏️ Edit</a> |
              <a href='?delete={$row['id']}' onclick='return confirm(\"Delete this event?\");'>🗑️ Delete</a>
            </td>
          </tr>";
  }
  ?>
</table>

<!-- Dashboard Latest 5 Events -->
<div class="dashboard">
  <h3>📅 Latest 5 Events</h3>
  <?php
  $latest = $conn->query("SELECT * FROM events ORDER BY id DESC LIMIT 5");
  while ($row = $latest->fetch_assoc()) {
    echo "<p><strong>{$row['event_name']}</strong> ({$row['event_date']})<br>{$row['description']}</p><hr>";
  }
  ?>
</div>

</body>
</html>
