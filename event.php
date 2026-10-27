<?php

function getEvents(PDO $conn): array
{
    $sql = "SELECT id, name, description, price FROM events ORDER BY name";
    $stmt = $conn->query($sql);

    return $stmt->fetchAll();
}

function findEvent(PDO $conn, int $id): ?array
{
    $sql = "SELECT id, name, description, price FROM events WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(["id" => $id]);

    return $stmt->fetch() ?: null;
}

function createEvent(PDO $conn, string $name, string $description, float $price): bool
{
    $sql = "INSERT INTO events (name, description, price)
            VALUES (:name, :description, :price)";
    $stmt = $conn->prepare($sql);

    return $stmt->execute([
        "name" => $name,
        "description" => $description,
        "price" => $price,
    ]);
}

function validateEvent(array $input): array
{
    $errors = [];

    $name = trim($input["event_name"] ?? "");
    if ($name === "") {
        $errors[] = "Event name is required.";
    } elseif (mb_strlen($name) > 150) {
        $errors[] = "Event name is too long.";
    }

    $description = trim($input["event_description"] ?? "");

    $price = filter_var($input["event_price"] ?? null, FILTER_VALIDATE_FLOAT, [
        "options" => ["min_range" => 0],
    ]);
    if ($price === false) {
        $errors[] = "Price must be a number of 0 or more.";
    }

    $data = [
        "name" => $name,
        "description" => $description,
        "price" => $price,
    ];

    return [$data, $errors];
}