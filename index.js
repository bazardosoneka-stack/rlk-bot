const express = require('express');
const cors = require('cors');
const puppeteer = require('puppeteer');
const app = express();
app.use(cors());
app.use(express.json());

app.post('/scan', async (req, res) => {
  const { url } = req.body;
  if (!url) return res.status(400).json({ error: 'URL obrigatória' });

  const browser = await puppeteer.launch({
    headless: true,
    args: ['--no-sandbox', '--disable-setuid-sandbox']
  });
  const page = await browser.newPage();
  await page.goto(url, { waitUntil: 'networkidle2', timeout: 15000 });

  const data = await page.evaluate(() => {
    return [...document.querySelectorAll('*')]
      .map(el => el.textContent)
      .join('|')
      .match(/([\w.-]+@[\w.-]+).*?R\$\s?(\d+[,.]\d{1,2})/gi) || [];
  });

  await browser.close();
  const filtered = data
    .map(m => {
      const [_, mail, val] = m.match(/([\w.-]+@[\w.-]+).*?R\$\s?(\d+[,.]\d{1,2})/) || [];
      return mail && parseFloat(val.replace(',', '.')) > 0 ? { email: mail, saldo: val } : null;
    })
    .filter(Boolean);

  res.json(filtered);
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => console.log(`Rodando na ${PORT}`));
