<?php
	header('Content-Description: File Transfer');
	header('Content-Disposition: attachment; filename="' . $_GET["nome"] .'"');
	header('Content-Type: application/octet-stream');
	header('Content-Transfer-Encoding: binary');
	header('Content-Length: ' . filesize("tmpOutput.txt"));
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
	header('Expires: 0');
	readfile("tmpOutput.txt");