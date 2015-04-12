<?php

function gensalt() {
  $salt = substr(
    str_replace('+', '.',
      base64_encode(
        pack(
          'N4',
          mt_rand(),
          mt_rand(),
          mt_rand(),
          mt_rand()
        )
      )
    ), 0, 22
  );
  $prefix = '$2a$10$';
  $na = $prefix . $salt;
  return $na;
}

?>
