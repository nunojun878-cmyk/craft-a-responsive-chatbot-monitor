<?php

/* 
 * File: 67ig_craft_a_respons.php
 * Purpose: Craft a Responsive Chatbot Monitor
 * Author: [Your Name]
 * Date: [Current Date]
 */

// Configuration settings
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'chatbot_monitor';

// Create connection to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get chatbot responses
function getResponses() {
    $sql = "SELECT * FROM responses";
    $result = $conn->query($sql);
    $responses = array();
    while ($row = $result->fetch_assoc()) {
        $responses[] = $row;
    }
    return $responses;
}

// Function to save new chatbot response
function saveResponse($response) {
    $sql = "INSERT INTO responses (response) VALUES ('$response')";
    $conn->query($sql);
}

// Function to update chatbot response
function updateResponse($id, $response) {
    $sql = "UPDATE responses SET response = '$response' WHERE id = '$id'";
    $conn->query($sql);
}

// Function to delete chatbot response
function deleteResponse($id) {
    $sql = "DELETE FROM responses WHERE id = '$id'";
    $conn->query($sql);
}

// Display chatbot responses
$responses = getResponses();
?>

<!-- HTML and CSS for responsive design -->
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }
    .container {
        max-width: 800px;
        margin: 40px auto;
        padding: 20px;
        background-color: #f9f9f9;
        border: 1px solid #ccc;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .response {
        padding: 10px;
        border-bottom: 1px solid #ccc;
    }
    .response:last-child {
        border-bottom: none;
    }
</style>

<div class="container">
    <h2>Chatbot Responses</h2>
    <ul>
        <?php foreach ($responses as $response) { ?>
            <li class="response">
                <p><?php echo $response['response']; ?></p>
                <a href="#" onclick="updateResponse(<?php echo $response['id']; ?>)">Edit</a>
                <a href="#" onclick="deleteResponse(<?php echo $response['id']; ?>)">Delete</a>
            </li>
        <?php } ?>
    </ul>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="text" name="new_response" placeholder="Enter new response...">
        <button type="submit">Save</button>
    </form>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $new_response = $_POST['new_response'];
        saveResponse($new_response);
    }
    ?>
</div>

<script>
    function updateResponse(id) {
        var response = prompt("Enter new response:");
        if (response != null) {
            window.location.href = "<?php echo $_SERVER['PHP_SELF']; ?>?update&id=" + id + "&response=" + response;
        }
    }

    function deleteResponse(id) {
        if (confirm("Are you sure you want to delete this response?")) {
            window.location.href = "<?php echo $_SERVER['PHP_SELF']; ?>?delete&id=" + id;
        }
    }

    <?php
    if (isset($_GET['update'])) {
        $id = $_GET['id'];
        $response = $_GET['response'];
        updateResponse($id, $response);
    } elseif (isset($_GET['delete'])) {
        $id = $_GET['id'];
        deleteResponse($id);
    }
    ?>
</script>

<?php
$conn->close();
?>