<?

session_start();
echo json_encode(['sucesso'=> true, 'lista'=> [['v'=> $_SESSION['sub'],'t'=> $_SESSION['sub']]]]);
?>