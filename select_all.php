<?php

include "connection.php";
include "response.php";

    header('Content-Type: application/json');
    $value = file_get_contents('php://input');
    $jsonObject = json_decode($value, true);
    
	$connection = $connection;
	$response_server = new Response();
    $response = [];
    
    $connection->beginTransaction();
    try {
        $query = "SELECT a.first_name, a.last_name, a.email, a.phone_number, a.birthdate, a.skill_level, b.communication_skills, b.teamwork_skills, b.leadership_skills FROM people a JOIN InterpersonalSkills b ON a.id = b.person_id";
        $stmt = $connection->prepare($query);
        $stmt->execute();
        $result  = $stmt->fetchall(PDO::FETCH_ASSOC);

        if ($result) $response = $response_server->ok("OK", $result);
        else $response = $response_server->error("No data found");
    }
    catch (PDOException $e) {
        $connection->rollback();
		$response = $response_server->internalServerError($e);

    }	

    echo json_encode($response);