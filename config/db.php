<?php

/**
 * Creates and returns a database connection using PDO.
 *
 * This function centralizes the database connection logic, making the application
 * easier to maintain. The credentials are in one place, and the connection
 * settings are standardized.
 *
 * @return PDO|null Returns a PDO object on successful connection, or null on failure.
 */
function getDbConnection(): ?PDO
{
    // --- Database Credentials ---
    // For security, it's better to load these from a configuration file
    // or environment variables that are outside of your web root.
    $dbHost = 'localhost';    // Your database host (e.g., '127.0.0.1')
    $dbName = 'hotel_booking';  // Your database name
    $dbUser = 'root';         // Your database username
    $dbPass = '';     // Your database password
    $charset = 'utf8mb4';

    // Data Source Name (DSN) specifies the database driver, host, name, and charset.
    $dsn = "mysql:host=$dbHost;dbname=$dbName;charset=$charset";

    // PDO options for error handling and fetch mode.
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors.
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch results as associative arrays.
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Use native prepared statements for security.
    ];

    try {
        // Attempt to create a new PDO instance (the database connection).
        return new PDO($dsn, $dbUser, $dbPass, $options);
    } catch (\PDOException $e) {
        // In a production environment, log the error to a file.
        // Avoid displaying detailed error messages to the end-user.
        error_log("Database connection failed: " . $e->getMessage());
        return null;
    }
}