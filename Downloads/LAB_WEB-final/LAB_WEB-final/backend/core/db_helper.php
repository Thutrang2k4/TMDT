<?php
function db_query($conn, $sql, $params = [], $types = '')
{
  $stmt = $conn->prepare($sql);
  if (!$stmt)
    return false;
  if ($params)
    $stmt->bind_param($types, ...$params);
  $stmt->execute();
  return $stmt;
}