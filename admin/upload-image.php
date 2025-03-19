<?php
if ($_FILES['file']['name']) {
  if (!is_dir('../uploads')) {
    mkdir('../uploads', 0777, true);
  }

  $fileName = time() . '_' . $_FILES['file']['name'];
  $filePath = '../uploads/' . $fileName;

  // Move uploaded file
  if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
    // Return location
    echo json_encode(['location' => '/uploads/' . $fileName]);
  } else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to upload file']);
  }
}
