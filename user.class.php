<?php
class User {
	
	private $connection;
	
	//see funk. k�ivitus kui tekitame uue instantsi
	//new user 
	function __construct($mysqli){
		
		//$this on see klass e User
		//-> connection on klassi muutuja 
		$this->connection=$mysqli;
		
	}
	
	function logInUser($email, $hash){
        
        $stmt = $this->connection->prepare("SELECT id, email FROM user_sample WHERE email=? AND password=?");
        $stmt->bind_param("ss", $email, $hash);
        $stmt->bind_result($id_from_db, $email_from_db);
        $stmt->execute();
        if($stmt->fetch()){
            echo "Kasutaja logis sisse id=".$id_from_db;
            
            // sessioon, salvestatakse serveris
            $_SESSION['logged_in_user_id'] = $id_from_db;
            $_SESSION['logged_in_user_email'] = $email_from_db;
            
            //suuname kasutaja teisele lehel
            header("Location: data.php");
            
        }else{
            echo "Wrong credentials!";
        }
        $stmt->close();
        
    }
    
    
    function createUser($create_email, $hash){
		
		//objekt kus tagastame errori (id, message) v�i successi (message)
		$response= new StdClass();
		
		//kontrollime kas on juba olemas sellise emailiga kasutaja
		$stmt = $this->connection->prepare("SELECT id FROM user_sample WHERE email=?");
		$stmt->bind_param("s", $create_email);
		$stmt->bind_result($id);
        $stmt->execute();	
        
		//kas saime rea andmeid
		if($stmt->fetch()){
			
			//email on juba olemas
			$error=new StdClass();
			$error->id = 0;
			$error->message = "Email on juba kasutusel.";
			
			$response->error=$error;
			
			//p�rast return k�sku ei vaadata funki edasi
			return $response;
		}
		
		//siia olen j�udnud siis kui emaili ei olnud 
		
        $stmt = $this->connection->prepare("INSERT INTO user_sample (email, password) VALUES (?,?)");
        $stmt->bind_param("ss", $create_email, $hash);
        if ($stmt->execute()){
			//sisestamine �nnestus
			$success=new StdClass();
			$success->message= "Kasutaja edukalt loodud";
			
			$response->success=$success;
			
		}
		else{
			//ei �nnestunud
			$error=new StdClass();
			$error->id = 1;
			$error->message = "Midagi l�ks katki.";
			
			$response->error=$error;			
		}
        $stmt->close();
		
		return $response;
        
    }
}
?> 