document.getElementById('loginForm').addEventListener('submit', e => {
  e.preventDefault();
  const u = document.getElementById('user').value;
  const p = document.getElementById('pass').value;
  if(u==='admin' && p==='admin123'){
     location.href='/dashboard.html';
  }else{
     alert('Usuário ou senha inválidos');
  }
});
