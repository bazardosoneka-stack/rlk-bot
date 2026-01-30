// relógio cyber
function clock() {
  const now = new Date();
  document.getElementById('clock').textContent = now.toLocaleString('pt-BR');
}
setInterval(clock, 1000);
clock();

// ruído estático
const canvas = document.getElementById('noise');
const ctx = canvas.getContext('2d');
function resize() {
  canvas.width = innerWidth;
  canvas.height = innerHeightHeight;
}
resize();
window.onresize = resize;
function noise() {
  const imgData = ctx.createImageData(canvas.width, canvas.height);
  const buf = imgData.data;
  for (let i = 0; i < buf.length; i += 4) {
    const v = Math.random() * 255;
    buf[i] = buf[i + 1] = buf[i + 2] = v;
    buf[i + 3] = 255;
  }
  ctx.putImageData(imgData, 0, 0);
}
setInterval(noise, 90);

// checker fake (simulação)
function check() {
  const area = document.getElementById('result');
  area.textContent = 'CHECANDO...';
  setTimeout(() => {
    area.textContent = '[✓] LIVE 544850******|06|29|123\n[✗] DIE 544851******|05|28|321\n';
  }, 1500);
}

// gerador fake
function gerar() {
  const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%&*';
  let pwd = '';
  for (let i = 0; i < 16; i++) pwd += chars[Math.floor(Math.random() * chars.length)];
  document.getElementById('pwdOut').value = pwd;
}
