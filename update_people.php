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
    $email = $jsonObject['email'];
    $phone_number = $jsonObject['phone_number'];
    $communication_skills = $jsonObject['communication_skills'];
    $teamwork_skills = $jsonObject['teamwork_skills'];
    $leadership_skills = $jsonObject['leadership_skills'];

    $connection->beginTransaction();
    try {
            
        $selectEmail = "SELECT email FROM people WHERE email = '$email'";
        $stmt = $connection->prepare($selectEmail);
        $stmt->execute();
        if ($stmt->rowCount() > 0) $response = $response_server->error("Email sudah terdaftar");
        else {
            $skill_levels = ($communication_skills + $teamwork_skills + $leadership_skills) / 3;    
            $query = "	UPDATE Interpersonalskills SET communication_skills = $communication_skills, 
                                                        teamwork_skills = $teamwork_skills,
                                                        leadership_skills = $leadership_skills
                                                        WHERE person_id = $id";
            $stmt = $connection->prepare($query);
            $stmt->execute();

            $query2 = "UPDATE people SET email = '$email', 
                                         phone_number = '$phone_number',
                                         skill_level = $skill_levels
                            WHERE id = $id";
            $stmt = $connection->prepare($query2);
            $stmt->execute();

            $response = $response_server->ok("Berhasil Update");
        }
            $connection->commit();
        }
    catch (PDOException $e) {
            $connection->rollback();
            $response = $response_server->internalServerError($e);
    
        }

    echo json_encode($response);