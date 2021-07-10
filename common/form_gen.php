<?php
function print_select_method($selected_id = null) 
{
	global $database;
	$query = "SELECT id, naam FROM beoordeling_methode";	
	print "<!-- $query -->\n";
?>
		Methode : <select name="method">
		<option value="-1" disabled selected>Kies een methode</option>
<?php
	// Send the query to the database server.
	$stmt = $database->query($query, PDO::FETCH_ASSOC);
	// Loop through all the records.
	foreach ($stmt as $record) 	
	{
		$id = $record["id"];
		$value = $record["naam"];
		
		// Check if this option should be pre-selected.
		$selected_yn="";
		if ($id==$selected_id) {
			$selected_yn = "selected";
		}
		
		// Generate an option for each item in the table.
?>
		<option value="<?=$id?>" <?=$selected_yn?>><?=$value?></option>
<?php
	}
	print "</select>";
}

function print_select_criterium($selected_id = null) 
{
	global $database;
	$query = "SELECT id, naam FROM criterium";	
	print "<!-- $query -->\n";
?>
		Criterium : <select name="criterium">
		<option value="-1" disabled selected>Kies een criterium</option>
<?php
	// Send the query to the database server.
	$stmt = $database->query($query, PDO::FETCH_ASSOC);
	// Loop through all the records.
	foreach ($stmt as $record) 	
	{
		$id = $record["id"];
		$value = $record["naam"];
		
		// Check if this option should be pre-selected.
		$selected_yn="";
		if ($id==$selected_id) {
			$selected_yn = "selected";
		}
		
		// Generate an option for each item in the table.
?>
		<option value="<?=$id?>" <?=$selected_yn?>><?=$value?></option>
<?php
	}
	print "</select>";
}

function print_select_project($selected_id = null) 
{
	global $database;
	$query = "SELECT id, naam FROM project";	
	print "<!-- $query -->\n";
?>
		Project : <select name="project">
		<option value="-1" disabled selected>Kies een project</option>
<?php
	// Send the query to the database server.
	$stmt = $database->query($query, PDO::FETCH_ASSOC);
	// Loop through all the records.
	foreach ($stmt as $record) 	
	{
		$id = $record["id"];
		$value = $record["naam"];
		
		// Check if this option should be pre-selected.
		$selected_yn="";
		if ($id==$selected_id) {
			$selected_yn = "selected";
		}
		
		// Generate an option for each item in the table.
?>
		<option value="<?=$id?>" <?=$selected_yn?>><?=$value?></option>
<?php
	}
	print "</select>";
}

function print_add_project()
{
?>
	<h2>Nieuw project</h2>
	<form>
		Naam : <input type="text" name="name" /><br />
		Omschrijving : <input type="textarea" name="description" /><br />
		Semester : <input type="number" name="semester" min="1" max="8" /><br />
		Difficulty : <input type="hidden" name="stars" min="1" max="5" />
<?php
	print_stars(1, "stars");
?>
		<input type="submit" name="add_project" value="Toevoegen" />
	</form>
<?php
}

function print_edit_project($project_id)
{
	global $database;
	$query = "SELECT * FROM project WHERE id = :id";	
	print "<!-- $query -->\n";
	$data = [
		"id" => $project_id
	];
	
	try {
		$stmt = $database->prepare($query);
		if ($stmt->execute($data)) 
		{
			// Actually read the record.
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
?>
			<h2><?=$row["naam"]?></h2>
			<ul>
				<li>Semester : <?=$row["semester"]?></li>
				<li>Level : 
<?php
			print_stars($row["sterren"]);
?>
				</li>
			</ul>
			<p>
				<?=$row["omschrijving"]?>
			</p>
<?php
			print_project_criteria($project_id);
		} 
		else 
		{
			print "Database refused to read project information.";
		}
	} catch (Exception $ex) {
		print "ERROR: Failed to load project : ".$ex->getMessage();
	}
}

function print_project_criteria($project_id) 
{
	global $database;
	$query = "SELECT criteriumid, gewicht, m.max*gewicht as max, c.naam as crit_naam, c.omschrijving as crit_omschrijvind, methodeid, m.naam as methode_naam, m.omschrijving as methode_omschrijving FROM `project_criterium` pc, criterium c, beoordeling_methode m WHERE pc.criteriumid = c.id AND c.methodeid = m.id AND groepid = :id";	
	print "<!-- $query -->\n";
	$data = [
		"id" => $project_id
	];
	
	try {
		$stmt = $database->prepare($query);
		if ($stmt->execute($data)) 
		{
			$total = 0;
?>
			<table>
			<tr>
				<th>Criterium</th>
				<th>Gewicht</th>
				<th>Maximaal</th>
				<th>Methode</th>
				<th>Actie</th>
			</tr>
<?php
			foreach ($stmt as $record) 	{
				$total += $record["max"];
?>
			<tr>
				<td><?=$record["crit_naam"]?></td>
				<td><?=$record["gewicht"]?></td>
				<td><?=$record["max"]?></td>
				<td>
<?php
					print_select_method($record["methodeid"]);
?>
				</td>
			</tr>
<?php
			}
?>
			</table>
			<p>Maximum point available : <?=$total?></p>
<?php
		} 
		else 
		{
			print "Database refused to read criteria.";
		}
	} catch (Exception $ex) {
		print "ERROR: Failed to load criteria : ".$ex->getMessage();
	}
}

function print_stars($checked, $field = null)
{
	$js = "";
	echo "<span onmouseleave=\"update_star_size(0)\">";
	for ($i=0;$i<5;$i++) 
	{
		if ($field) {
			$value = $i+1;
			$js = "onmouseover=\"update_star_size($value)\" onclick=\"update_star('$field', $value)\" name=\"sterretje_$value\"";
		}
		$yellow = "";
		if ($i<$checked) 
		{
			$yellow = "checked";
		}
		
		echo "<span class=\"fa fa-star $yellow\" $js></span>\n\r";			
	}
	echo "</span>";
}

?>























