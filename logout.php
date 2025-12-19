<?php
// Startar sessionen så att den kan avslutas
session_start();

// Tar bort alla variabler som är sparade i sessionen
session_unset();

// Förstör hela sessionen (användaren loggas ut)
session_destroy();

// Skickar användaren tillbaka till inloggningssidan
header('Location: feed.php');

// Avslutar scriptet så inget mer körs
exit();
?>