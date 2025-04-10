<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if(isset($_SESSION['loggedin'])){
    require_once("header-in.php");
}else{  
    require_once("header.php");
}
?>
<br>
<br>
<br>
<?php

class Booking
{
 
    private $dbh;
 
    private $bookingsTableName = 'bookings';
 
    /**
     * Booking constructor.
     * @param string $database
     * @param string $host
     * @param string $databaseUsername
     * @param string $databaseUserPassword
     */
    public function __construct($database, $host, $databaseUsername, $databaseUserPassword)
    {
        try {
 
            $this->dbh =
                new PDO(sprintf('mysql:host=%s;dbname=%s', $host, $database),
                    $databaseUsername,
                    $databaseUserPassword
                );
 
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
	
	public function index()
	{
	$statement = $this->dbh->query('SELECT * FROM ' . $this->bookingsTableName); 
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

	
	
    public function add(DateTimeImmutable $bookingDate)
    {
         $statement = $this->dbh->prepare(
            'INSERT INTO ' . $this->bookingsTableName . ' (booking_date, id) VALUES (:bookingDate, '.$_SESSION['id'].')');
        if (false === $statement) {
            throw new Exception('Invalid prepare statement');
        }
 
        if (false === $statement->execute([
                'bookingDate' => $bookingDate->format('Y-m-d'),
            ])) {
            throw new Exception(implode(' ', $statement->errorInfo()));
        }
		
	}
	
    public function delete($id)
    {
        $statement = $this->dbh->prepare(
            'DELETE from ' . $this->bookingsTableName . ' WHERE booking_id = :id'
        );
        if (false === $statement) {
            throw new Exception('Invalid prepare statement');
        }
        if (false === $statement->execute([':id' => $id])) {
            throw new Exception(implode(' ', $statement->errorInfo()));
        }
    }
 
}

?>
