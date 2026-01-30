FROM node:20-slim
WORKDIR /app

# instala Chrome + deps
RUN apt-get update && apt-get install -y \
  ca-certificates fonts-liberation libappindicator1 libasound2 libatk1.0-0 \
  libc6 libcairo2 libcups2 libdbus-1-3 libexpat1 libfontconfig1 libgbm1 \
  libgcc1 libgconf-2-4 libgdk-pixbuf2.0-0 libglib2.0-0 libgtk-3-0 libnspr4 \
  libnss3 libpango-1.0-0 libpangocairo-1.0-0 libstdc++6 libx11-6 libx11-xcb1 \
  libxcb1 libxcomposite1 libxcursor1 libxdamage1 libxext6 libxfixes3 libxi6 \
  libxrandr2 libxrender1 libxss1 libxtst6 lsb-release wget xdg-utils xvfb \
  && rm -rf /var/lib/apt/lists/*

# cria user não-root
RUN groupadd -r pptr && useradd -r -g pptr pptr
COPY --chown=pptr:pptr . /app
USER pptr

ENV PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=true \
    PUPPETEER_EXECUTABLE_PATH=/usr/bin/chromium

RUN npm ci --omit=dev
EXPOSE 3000
CMD ["xvfb-run","-a","node","index.js"]
