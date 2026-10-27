<?php

require_once __DIR__ . "/event.php";

const MAX_TICKETS = 10;

function validateBooking(array $input): array
{
    $errors = [];

    $name = trim($input["customer_name"] ?? "");
    if ($name === "") {
        $errors[] = "Customer name is required.";
    } elseif (mb_strlen($name) > 100) {
        $errors[] = "Customer name is too long.";
    }

    $eventId = filter_var($input["event_id"] ?? null, FILTER_VALIDATE_INT, [
        "options" => ["min_range" => 1],
    ]);
    if ($eventId === false) {
        $errors[] = "Please select a valid event.";
    }

    $quantity = filter_var($input["ticket_quantity"] ?? null, FILTER_VALIDATE_INT, [
        "options" => ["min_range" => 1, "max_range" => MAX_TICKETS],
    ]);
    if ($quantity === false) {
        $errors[] = "Ticket quantity must be a whole number from 1 to " . MAX_TICKETS . ".";
    }

    $data = [
        "customer_name" => $name,
        "event_id" => $eventId,
        "ticket_quantity" => $quantity,
    ];

    return [$data, $errors];
}

function getBookings(PDO $conn): array
{
    $sql = "SELECT b.id,
                   b.customer_name,
                   b.ticket_quantity,
                   b.total_amount,
                   b.booking_date,
                   e.name AS event_name
            FROM bookings b
            JOIN events e ON e.id = b.event_id
            ORDER BY b.booking_date DESC";

    return $conn->query($sql)->fetchAll();
}

function calculateTotal(string|float $price, int $quantity): string
{
    return number_format((float) $price * $quantity, 2, ".", "");
}

function createBooking(PDO $conn, string $customerName, int $eventId, int $quantity): bool
{
    $event = findEvent($conn, $eventId);

    if ($event === null) {
        throw new InvalidArgumentException("The selected event does not exist.");
    }

    $total = calculateTotal($event["price"], $quantity);

    $sql = "INSERT INTO bookings (customer_name, event_id, ticket_quantity, total_amount)
            VALUES (:customer_name, :event_id, :ticket_quantity, :total_amount)";
    $stmt = $conn->prepare($sql);

    return $stmt->execute([
        "customer_name" => $customerName,
        "event_id" => $eventId,
        "ticket_quantity" => $quantity,
        "total_amount" => $total,
    ]);
}