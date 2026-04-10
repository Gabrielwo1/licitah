<script>
function verificarEdestruirCookie() {
  var cookieValor = getCookie('sessaoAcesso');
  
  if (cookieValor !== '') {
    // Define o cookie para expirar no passado, destruindo-o
    document.cookie = 'sessaoAcesso=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
  }else {
    
  }
}

function getCookie(name) {
  var cookieName = name + '=';
  var cookies = document.cookie.split(';');
  
  for (var i = 0; i < cookies.length; i++) {
    var cookie = cookies[i].trim();
    
    if (cookie.indexOf(cookieName) === 0) {
      return cookie.substring(cookieName.length, cookie.length);
    }
  }
  
  return '';
}



verificarEdestruirCookie();
window.location.href = dominioAdress;

</script>

