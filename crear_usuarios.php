<?php

$pass1 = password_hash('admin123', PASSWORD_DEFAULT);
$pass2 = password_hash('estudiante123', PASSWORD_DEFAULT);
$pass3 = password_hash('docente123', PASSWORD_DEFAULT);


echo "Admin: $pass1<br>";
echo "Estudiante: $pass2<br>";
echo "Docente: $pass3<br>";
