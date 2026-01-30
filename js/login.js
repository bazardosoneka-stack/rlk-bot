document.getElementById('loginForm').addEventListener('submit', function (e) {
  e.preventDefault();
  const u = document.getElementById('user').value;
  const p = document.getElementById('pass').value;
  if (u === 'admin' && p === 'admin123') {
    location.href = 'dashboard.html';
  } else {
    const err = document.getElementById('error');
    err.textContent = 'Acesso negado';
    err.classList.add('shake');
    setTimeout(() => err.classList.remove('shake'), 400);
  }
});
