<?
require __DIR__ . '/bibliotecas/vendor/autoload.php';

class Push{
    public $pusher;
    function __construct(){
         $options = array(
    'cluster' => 'sa1',
    'useTLS' => true
  );
  $this->pusher = new Pusher\Pusher(
    '54adf1e4ca2bdf5b0788',
    '5fec12e047b6d2766a59',
    '1881319',
    $options
  );

    }
    
    function get(){
        return $this->pusher;
    }
}
  



?>