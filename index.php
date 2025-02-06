<?php
session_start();

// Initialize events if not set
if (!isset($_SESSION['events'])) {
    $_SESSION['events'] = [];
}

// Handle form submission (Add Event)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['title']) && isset($_POST['date'])) {
    $title = trim($_POST['title']);
    $date = trim($_POST['date']);

    if (!empty($title) && !empty($date)) {
        $_SESSION['events'][] = ["title" => $title, "date" => $date];
    }
}

// Handle event deletion
if (isset($_POST['delete_event'])) {
    $eventIndex = $_POST['delete_event'];
    array_splice($_SESSION['events'], $eventIndex, 1);
    echo json_encode($_SESSION['events']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎉 Fun Event Manager 🎊</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Patrick+Hand&display=swap');
        body {
            font-family: 'Patrick Hand', cursive;
            text-align: center;
            background: #121212;
            padding: 20px;
            color: white;
        }
        .container {
            max-width: 400px;
            margin: auto;
            background: #1e1e1e;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0px 0px 15px rgba(255, 255, 255, 0.2);
            color: white;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            background: #bb86fc;
            color: white;
            padding: 10px;
            margin: 5px 0;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
            font-weight: bold;
        }
        li:hover {
            background: #985eff;
        }
        button {
            padding: 10px;
            margin-top: 10px;
            background: #03dac6;
            color: black;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background: #00c4b4;
        }
        input[type="text"], input[type="date"] {
            padding: 8px;
            width: 70%;
            border: 2px solid #bb86fc;
            border-radius: 8px;
            font-family: 'Patrick Hand', cursive;
            background: #333;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>🎉 Fun Event Manager 🎊</h2>
    <form method="POST">
        <input type="text" name="title" placeholder="Event Title" required>
        <input type="date" name="date" required>
        <button type="submit">Add Event 🎯</button>
    </form>

    <h3>Upcoming Events:</h3>
    <ul id="eventList">
        <?php foreach ($_SESSION['events'] as $index => $event): ?>
            <li data-index="<?php echo $index; ?>"> <?php echo htmlspecialchars($event['title']) . " - " . $event['date']; ?> </li>
        <?php endforeach; ?>
    </ul>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("eventList").addEventListener("click", function (event) {
            if (event.target.tagName === "LI") {
                let index = event.target.getAttribute("data-index");
                deleteEvent(index);
            }
        });
    });

    function deleteEvent(index) {
        fetch("index.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "delete_event=" + index
        })
        .then(response => response.json())
        .then(events => {
            let eventList = document.getElementById("eventList");
            eventList.innerHTML = "";
            events.forEach((event, i) => {
                let li = document.createElement("li");
                li.textContent = event.title + " - " + event.date;
                li.setAttribute("data-index", i);
                eventList.appendChild(li);
            });
        });
    }
</script>

</body>
</html>