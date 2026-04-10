<?
include_once __DIR__."/../config.php";
function websocket(){

    $cluster = v(["apis","websockets","cluster"], false);
    $secret = v(["apis","websockets","secret"], false);
    $key =  v(["apis","websockets","key"], false);
    $id = v(["apis","websockets","id"], false);
    
    
     require __DIR__ . '/../bibliotecas/vendor/autoload.php';
      $options = array(
    'cluster' => $cluster,
    'useTLS' => true
  );
  $pusher = new Pusher\Pusher(
      
    $key,
    $secret,
    $id,
    $options
  );
  return $pusher;
}








?>