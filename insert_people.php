<?php 

include "connection.php";
include "response.php";

    header('Content-Type: application/json');
    $value = file_get_contents('php://input');
    $jsonObject = json_decode($value, true);
    
	$connection = $connection;
	$response_server = new Response();
    $response = [];

    $first_name = $jsonObject['first_name'];
    $last_name = $jsonObject['last_name'];
    $email = $jsonObject['email'];
    $phone_number = $jsonObject['phone_number'];
    $birthdate = $jsonObject['birthdate'];
    $communication_skills = $jsonObject['communication_skills'];
    $teamwork_skills = $jsonObject['teamwork_skills'];
    $leadership_skills = $jsonObject['leadership_skills'];

    $connection->beginTransaction();
    try {
            $skill_levels = ($communication_skills + $teamwork_skills + $leadership_skills) / 3;    

            $selectEmail = "SELECT email FROM people WHERE email = '$email'";
            $stmt = $connection->prepare($selectEmail);
            $stmt->execute();
            if ($stmt->rowCount() > 0) $response = $response_server->error("Email sudah terdaftar");
            else {
                $query = "	INSERT INTO people SET first_name = '$first_name',
                                    last_name = '$last_name',
                                    email = '$email',
                                    phone_number = '$phone_number',
                                    birthdate = '$birthdate',
                                    skill_level = $skill_levels";
                $stmt = $connection->prepare($query);
                $stmt->execute();
                $id = $connection->lastInsertId();
    
                $query2 = "INSERT INTO InterpersonalSkills SET person_id = $id,
                                                            communication_skills = $communication_skills,
                                                            teamwork_skills = $teamwork_skills,
                                                            leadership_skills = $leadership_skills";
                $stmt = $connection->prepare($query2);
                $stmt->execute();
    
                $response = $response_server->ok("Berhasil Insert");
            }
            $connection->commit();
        }
    catch (PDOException $e) {
            $connection->rollback();
            $response = $response_server->internalServerError($e);
    
        }

    echo json_encode($response);