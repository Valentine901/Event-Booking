<?php

require_once __DIR__ . "/database.php";
require_once __DIR__ . "/event.php";

$errors = [];
$success = isset($_GET["created"]);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    [$data, $errors] = validateEvent($_POST);

    if (empty($errors)) {
        try {
            createEvent($conn, $data["name"], $data["description"], $data["price"]);
            header("Location: new-event.php?created=1");
            exit;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $errors[] = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>New Event</title>
</head>
<body class="w-full flex flex-col items-center">
    <a href="new-booking.php" class="mt-4 text-sm font-semibold underline">Go to bookings</a>

    <form action="" method="POST" class="w-full max-w-md mt-6 space-y-4 px-4">

        <?php if ($success): ?>
            <p class="text-sm font-semibold text-green-600">Event created successfully!</p>
        <?php endif; ?>

        <?php foreach ($errors as $error): ?>
            <p class="text-sm font-semibold text-red-600"><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>

        <div class="space-y-1">
            <label for="event_name" class="text-sm font-semibold">Event Name</label>
            <input type="text" id="event_name" name="event_name" required
                   value="<?= htmlspecialchars($_POST["event_name"] ?? "") ?>"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>

        <div class="space-y-1">
            <label for="event_description" class="text-sm font-semibold">Description</label>
            <textarea id="event_description" name="event_description" rows="4"
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"><?= htmlspecialchars($_POST["event_description"] ?? "") ?></textarea>
        </div>

        <div class="space-y-1">
            <label for="event_price" class="text-sm font-semibold">Price</label>
            <input type="number" id="event_price" name="event_price" min="0" step="0.01" required
                   value="<?= htmlspecialchars($_POST["event_price"] ?? "") ?>"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>

        <button type="submit" class="py-2 w-full text-center rounded-lg text-white bg-gray-600 text-sm font-semibold">
            Create Event
        </button>
    </form>

</body>
</html>