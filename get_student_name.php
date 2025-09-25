<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['student_name'])) {
  echo json_encode(['name' => $_SESSION['student_name']]);
} else {
  echo json_encode(['name' => 'Student']);
}