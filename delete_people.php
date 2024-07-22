<?php

include "connection.php";
include "response.php";

    header('Content-Type: application/json');
    $value = file_get_contents('php://input');
    $jsonObject = json_decode($value, true);
    
	$connection = $connection;
	$response_server = new Response();
    $response = [];
    
    $id = $jsonObject['id'];
    
    $connection->beginTransaction();
    try {
        $query = "DELETE FROM people WHERE id = $id";
        $stmt = $connection->prepare($query);
        $stmt->execute();

        $query2 = "DELETE FROM InterpersonalSkills WHERE person_id = $id";
        $stmt = $connection->prepare($query2);
        $stmt->execute();
        
        $response = $response_server->ok("Berhasil dihapus");
        $connection->commit();
    }
    catch (PDOException $e) {
        $connection->rollback();
		$response = $response_server->internalServerError($e);

    }	

    echo json_encode($response);