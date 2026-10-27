<?php

require_once __DIR__ . "/database.php";
require_once __DIR__ . "/booking.php";

$bookings = getBookings($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>All Bookings</title>
</head>
<body class="w-full flex flex-col items-center">

    <div class="w-full max-w-3xl mt-6 px-4">

        <div class="flex items-center justify-between mb-4">
            <h1 class="text-lg font-semibold">All Bookings</h1>
            <a href="new-booking.php" class="py-2 px-4 rounded-lg text-white bg-gray-600 text-sm font-semibold">
                New Booking
            </a>
        </div>

        <?php if (empty($bookings)): ?>
            <p class="text-sm text-gray-500">No bookings yet.</p>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-3 py-2">#</th>
                            <th class="px-3 py-2">Customer</th>
                            <th class="px-3 py-2">Event</th>
                            <th class="px-3 py-2">Tickets</th>
                            <th class="px-3 py-2">Total</th>
                            <th class="px-3 py-2">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                            <tr class="border-t border-gray-300">
                                <td class="px-3 py-2"><?= (int) $booking["id"] ?></td>
                                <td class="px-3 py-2"><?= htmlspecialchars($booking["customer_name"]) ?></td>
                                <td class="px-3 py-2"><?= htmlspecialchars($booking["event_name"]) ?></td>
                                <td class="px-3 py-2"><?= (int) $booking["ticket_quantity"] ?></td>
                                <td class="px-3 py-2"><?= number_format($booking["total_amount"], 2) ?></td>
                                <td class="px-3 py-2"><?= htmlspecialchars($booking["booking_date"]) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

    </div>

</body>
</html>