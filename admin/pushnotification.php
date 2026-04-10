<?

include __DIR__."/bibliotecas/vendor/autoload.php";

  $beamsClient = new \Pusher\PushNotifications\PushNotifications(
  array(
    "instanceId" => "e56cb033-13b8-48e1-867d-b13cbecea5a0",
    "secretKey" => "9FDCDF4BD01216E77D47F0FD77AA43EC6A9586EC0A3254F8FD6A2CE3BFDED89E",
  )
);


$beamsClient->publishToUsers(

  array("user-30"),

  array(

    "fcm" => array(

      "notification" => array(

        "title" => "Hi!",

        "body" => "This is my first Push Notification!"

      )

    ),

    "apns" => array("aps" => array(

      "alert" => array(

        "title" => "Hi!",

        "body" => "This is my first Push Notification!"

      )

    )),

    "web" => array(

      "notification" => array(

        "title" => "Hi!",

        "body" => "This is my first Push Notification!"

      )

    )

));



?>