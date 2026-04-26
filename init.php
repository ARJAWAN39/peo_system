<?php
// Force session to work correctly across folders
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
