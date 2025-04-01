<?php	
$connection = mysqli_connect("localhost", "root", "root", "leisure-centre-booking");
$res = mysqli_query($connection, "SELECT * FROM bookings");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$Uids = [];
while ($row=mysqli_fetch_array($res, MYSQLI_ASSOC))
{
	$Uids[] = $row['id'];
}

class BookableCell
{
    /**
     * @var Booking
     */
    private $booking;
    private $currentURL;
    private $userId;
    private $Uids;
 
    /**
     * BookableCell constructor.
     * @param $booking
     */
	

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
        $this->currentURL = htmlentities($_SERVER['REQUEST_URI']);
	$this->userId = $_SESSION['id'];
	global $Uids;
	$this->Uids = $Uids;
    }
 
    public function update(Calendar $cal)
    {
        if ($this->isDateBooked($cal->getCurrentDate())) {
            return $cal->cellContent =
                $this->bookedCell($cal->getCurrentDate());
        }
 
        if (!$this->isDateBooked($cal->getCurrentDate())) {
            return $cal->cellContent =
                $this->openCell($cal->getCurrentDate());
        }
    }
 
    public function routeActions()
    {
        if (isset($_POST['delete'])) {
            $this->deleteBooking($_POST['id']);
        }
 
        if (isset($_POST['add'])) {
            $this->addBooking($_POST['date']);		
        }
		
    }
	
 
    private function openCell($date)
    {
        return '<div class="open">' . $this->bookingForm($date) . '</div>';
    }
 

    private function bookedCell($date) {
	 $bookedUserIds = $this->bookedUserIds($date);
    if (in_array($_SESSION['id'], $bookedUserIds)) {
            // Current user made the booking
            return '<div class="booked">' . $this->deleteForm($this->bookingId($date)) . '</div>';
        } else {
            // Other user made the booking
            return '<div class="open">' . $this->bookingForm($date) . '</div>';
        }
}


private function bookedUserIds($date) {
    return array_map(function ($record) {
        return $record['id'];
    }, array_filter($this->booking->index(), function ($record) use ($date) {
        return $record['booking_date'] == $date;
    }));
}
    private function isDateBooked($date)
    {
        return in_array($date, $this->bookedDates());
    }
 
    private function bookedDates()
    {
        return array_map(function ($record) {
            return $record['booking_date'];
        }, $this->booking->index());
    }
 
    private function bookingId($date)
    {
        $booking = array_filter($this->booking->index(), function ($record) use ($date) {
            return $record['booking_date'] == $date;
        });
 
        $result = array_shift($booking);
 
        return $result['booking_id'];
    }
 
    private function deleteBooking($id)
    {
       // $this->booking->delete($id);
	//$_SESSION['bookingDelete'] = $id;
    }
 

	private function addBooking($date)
    {
        $date = new DateTimeImmutable($date);
	$_SESSION['bookingDate'] = $date->format('Y-m-d');
        //$this->booking->add($date);
	}
	
		

	private function bookingForm($date)
    {
		return
			'<form method="post" action="/times.php">'.
            '<input type="hidden" name="add" />' .
            '<input type="hidden" name="date" value="' . $date . '" />' .
	     '<input class="submit" type="submit" value="Book" />' .
            '</form>';
	
  }

    private function deleteForm($id)
    {
        return
           '<form method="post" action="/times.php">'.
            '<input type="hidden" name="delete" />' .
            '<input type="hidden" name="id" value="' . $id . '" />' .
            '<input class="submit" type="submit" value="Delete"  />' .
            '</form>';
    }
}	

// 	 '<form  method="post" action="' . $this->currentURL . '">' .
// <form method="post" action="/times.php">


?>