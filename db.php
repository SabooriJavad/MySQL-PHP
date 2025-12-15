<?php
// Skapar en ny PDO-anslutning till MySQL-databasen "microblock"
$pdo = new PDO(
    "mysql:host=localhost;dbname=microblock;charset=utf8",
    "root", // Databasanvändarnamn
    ""      // Databaslösenord (tomt i detta fall)
);

// Ställer in PDO så att fel hanteras med exceptions
// Gör det lättare att felsöka vid databasfel
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Startar en PHP-session
// Krävs för att kunna använda $_SESSION (inloggning m.m.)
session_start();
?>