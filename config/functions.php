<?php

/**
 * This file contains reusable functions for the hotel booking application.
 * It handles common database operations and other utility tasks.
 */

// Include the database connection setup
require_once 'db.php';

/**
 * Executes a SELECT query and returns the result set.
 *
 * @param string $sql The SQL query to execute.
 * @param array $params An array of parameters to bind to the query.
 * @param bool $single Fetch a single record if true, otherwise fetch all.
 * @return mixed The fetched data as an array, or a single row, or false on failure.
 */
function select(string $sql, array $params = [], bool $single = false)
{
    try {
        $pdo = getDbConnection();
        if ($pdo === null) {
            return false;
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        if ($single) {
            return $stmt->fetch();
        }
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Select query failed: " . $e->getMessage());
        return false;
    }
}

/**
 * Executes an INSERT, UPDATE, or DELETE query.
 *
 * @param string $sql The SQL query to execute.
 * @param array $params An array of parameters to bind to the query.
 * @return int|false The number of affected rows, or false on failure.
 */
function execute(string $sql, array $params = [], bool $returnLastInsertId = false)
{
    try {
        $pdo = getDbConnection();
        if ($pdo === null) {
            return false;
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        if ($returnLastInsertId) {
            return $pdo->lastInsertId();
        }

        return $stmt->rowCount();
    } catch (PDOException $e) {
        error_log("Execute query failed: " . $e->getMessage());
        return false;
    }
}

/**
 * Fetches the site settings from the database.
 *
 * @return array|false The site settings as an associative array, or false if not found.
 */
function getSiteSettings()
{
    $settings = select("SELECT * FROM site_settings WHERE id = 1", [], true);
    return $settings ?: [];
}

/**
 * Deletes a file if it exists.
 *
 * @param string $filePath The full path to the file to delete.
 * @return bool True on success, false on failure.
 */
function deleteOldFile(string $filePath): bool
{
    if (file_exists($filePath) && is_file($filePath)) {
        return unlink($filePath);
    }
    return false;
}

