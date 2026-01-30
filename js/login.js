document.getElementById('loginForm').addEventListener('submit', function(e){
  e.preventDefault();
  const u = document.getElementById('user').value;
  const p = document.getElementById('pass').value;
  if(u === 'admin' && p === 'admin123'){
    window.location.href = 'dashboar2.html';
  }else{
    alert('Acesso negado');
  }
});
