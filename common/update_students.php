<?php

function add_student($firstname, $lastname, $klas)
{
	global $database;
	
	$query  = "INSERT INTO leerlingen (voornaam, achternaam, klas, geslacht, geb_datum) ";
	$query .= "VALUES (:veld1, :veld2, :veld3, \"j\", \"1987-12-27\")";	
	
	print "<!-- $query -->\n";

	$data = [
		"veld1" => $firstname,
		"veld2" => $lastname,
		"veld3" => $klas
	];
	
	try {
		print "About to add new student.";
		$stmt = $database->prepare($query);
		if ($stmt->execute($data)) 
		{
			print "Student successfully added.";
		} 
		else 
		{
			print "Database refused to add new student.";
		}
	} catch (Exception $ex) {
		print "ERROR: Failed to add student : ".$ex->getMessage();
	}
}

function update_student($number, $firstname, $lastname, $klas)
{
	global $database;
	
	$query  = "UPDATE leerlingen ";
	$query .= "SET voornaam = :veld1, ";
	$query .= "    achternaam = :veld2, ";
	$query .= "    klas = :veld3 ";
	$query .= "WHERE llnr = :veld0";
		
	print "<!-- $query -->\n";

	$data = [	
		"veld0" => $number,
		"veld1" => $firstname,
		"veld2" => $lastname,
		"veld3" => $klas
	];
	
	try {
		print "About to change student $number.";
		$stmt = $database->prepare($query);
		if ($stmt->execute($data)) 
		{
			print "Student successfully updated.";
		} 
		else 
		{
			print "Database refused to update this student.";
		}
	} catch (Exception $ex) {
		print "ERROR: Failed to update student : ".$ex->getMessage();
	}
}


?>