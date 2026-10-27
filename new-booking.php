<?php

require_once __DIR__ . "/database.php";
require_once __DIR__ . "/booking.php";

$errors = [];
$success = isset($_GET["booked"]);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    [$data, $errors] = validateBooking($_POST);

    if (empty($errors)) {
        try {
            createBooking(
                $conn,
                $data["customer_name"],
                $data["event_id"],
                $data["ticket_quantity"]
            );
            header("Location: new-booking.php?booked=1");
            exit;
        } catch (InvalidArgumentException $e) {
            $errors[] = $e->getMessage();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $errors[] = "Something went wrong. Please try again.";
        }
    }
}

$events = getEvents($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>New Booking</title>
</head>
<body class="w-full flex flex-col items-center">
    <a href="bookings.php" class="mt-4 text-sm font-semibold underline">View all bookings</a>
    <a href="new-event.php" class="mt-2 text-sm font-semibold underline">Create new event</a>
    <form action="" method="POST" class="w-full max-w-md mt-6 space-y-4 px-4">

        <?php if ($success): ?>
            <p class="text-sm font-semibold text-green-600">Booking saved successfully!</p>
        <?php endif; ?>

        <?php foreach ($errors as $error): ?>
            <p class="text-sm font-semibold text-red-600"><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>

        <div class="space-y-1">
            <label for="customer_name" class="text-sm font-semibold">Customer Name</label>
            <input type="text" id="customer_name" name="customer_name" required
                   value="<?= htmlspecialchars($_POST["customer_name"] ?? "") ?>"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>

        <div class="space-y-1">
            <label for="event_id" class="text-sm font-semibold">Event</label>
            <select id="event_id" name="event_id" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="">Select an event</option>
                <?php foreach ($events as $event): ?>
                    <option value="<?= (int) $event["id"] ?>"
                        <?= ((int) ($_POST["event_id"] ?? 0) === (int) $event["id"]) ? "selected" : "" ?>>
                        <?= htmlspecialchars($event["name"]) ?> (<?= number_format($event["price"], 2) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="space-y-1">
            <label for="ticket_quantity" class="text-sm font-semibold">Ticket Quantity</label>
            <input type="number" id="ticket_quantity" name="ticket_quantity"
                   min="1" max="<?= MAX_TICKETS ?>" required
                   value="<?= htmlspecialchars($_POST["ticket_quantity"] ?? "1") ?>"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>

        <button type="submit" class="py-2 w-full text-center rounded-lg text-white bg-gray-600 text-sm font-semibold">
            Book Now
        </button>
    </form>

</body>
</html>