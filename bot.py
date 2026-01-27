import telebot, os, mercadopago
from telebot import types

TOKEN      = os.getenv("TOKEN",      "8512967381:AAEHqB0Z-lQB6d2H8_3n_6PhNNp2NBdhitM")
MP_TOKEN   = os.getenv("MP_TOKEN",   "APP_USR-73b028d9-9d7f-4a90-ace1-0d4d47c4417f")
ADMIN_ID   = int(os.getenv("ADMIN_ID","8337105439"))
DONO_USER  = "astrodono"
GRUPO_LINK = "https://t.me/+qRi9ljZuHgRiYTMx"

bot = telebot.TeleBot(TOKEN)
sdk = mercadopago.SDK(MP_TOKEN)

def obter_valor_cc(tipo_bruto):
    t = tipo_bruto.upper()
    if "NUBANK BLACK" in t: return 35.00
    if "BLACK"        in t: return 60.00
    if "INFINITE"     in t: return 40.00
    if "NUBANK GOLD"  in t: return 15.00
    if "GOLD"         in t: return 50.00
    if "NUBANK PLATINUM" in t: return 50.00
    if "PLATINUM"     in t: return 50.00
    if "SIGNATURE"    in t: return 58.00
    if "WORLD ELITE"  in t: return 60.00
    if "CLASSIC"      in t: return 25.00
    if "STANDARD"     in t: return 40.00
    return 50.00

def listar_estoque_inteligente(categoria_solicitada):
    arquivos = ["ccs.txt","consul.txt"]
    linhas = []
    for arq in arquivos:
        if os.path.exists(arq):
            with open(arq, encoding="utf-8") as f:
                linhas.extend([l.strip() for l in f if "|" in l])
    resultado = []
    for linha in linhas:
        eh_consul = "CONSUL" in linha.upper() or "SALDO" in linha.upper()
        if categoria_solicitada=="CONSUL" and eh_consul: resultado.append(linha)
        elif categoria_solicitada=="CCS" and not eh_consul: resultado.append(linha)
    return resultado

def gerar_pix(valor,descricao):
    payment_data = {
        "transaction_amount": float(valor),
        "description": f"UNIÃO ASTRO - {descricao}",
        "payment_method_id":"pix",
        "payer":{
            "email":"cliente@uniaoastro.com",
            "first_name":"Cliente",
            "last_name":"Astro",
            "identification":{"type":"CPF","number":"19119119100"}
        }
    }
    res = sdk.payment().create(payment_data)
    if "response" in res and "point_of_interaction" in res["response"]:
        return res["response"]["point_of_interaction"]["transaction_data"]["qr_code"]
    return None

@bot.message_handler(commands=["start","menu"])
def menu_principal(message):
    markup = types.InlineKeyboardMarkup(row_width=1)
    markup.add(
        types.InlineKeyboardButton("✡️ COMPRAR CCS ✡️",   callback_data="listar_CCS"),
        types.InlineKeyboardButton("✡️ COMPRAR CONSUL ✡️",callback_data="listar_CONSUL"),
        types.InlineKeyboardButton("✡️ BUSCAR BIN ✡️",     callback_data="pesquisar_bin"),
        types.InlineKeyboardButton("✡️ BUSCAR BANCO ✡️",   callback_data="pesquisar_banco"),
        types.InlineKeyboardButton("✡️ GRUPO DE CLIENTES ✡️", url=GRUPO_LINK),
        types.InlineKeyboardButton("✡️ SUPORTE ✡️",        url=f"https://t.me/{DONO_USER}")
    )
    bot.send_message(message.chat.id,"✡️ UNIÃO ASTRO - PAGAMENTO AUTOMÁTICO ATIVO ✡️",reply_markup=markup)

@bot.callback_query_handler(func=lambda c: c.data.startswith("listar_"))
def tratar_listagem(call):
    cat = call.data.split("_")[1]
    itens = listar_estoque_inteligente(cat)
    if not itens:
        bot.answer_callback_query(call.id,"✡️ ESTOQUE VAZIO ✡️",show_alert=True)
        return
    markup = types.InlineKeyboardMarkup(row_width=1)
    for i,linha in enumerate(itens):
        partes = linha.split("|")
        banco  = partes[0].replace("/add ccs ","").replace("/add consul ","").strip()
        if cat=="CONSUL":
            try:    valor=float(partes[1].replace("R$","").replace(",","."))
            except: valor=120.00
            label = f"✡️ {banco} - R$ {valor:.2f} ✡️"
        else:
            tipo  = partes[1].strip()
            valor = obter_valor_cc(tipo)
            label = f"✡️ {banco} {tipo} - R$ {valor:.2f} ✡️"
        markup.add(types.InlineKeyboardButton(label,callback_data=f"buy_{cat}_{i}"))
    bot.edit_message_text(f"✡️ CATEGORIA: {cat} ✡️",call.message.chat.id,call.message.message_id,reply_markup=markup)

@bot.callback_query_handler(func=lambda c: c.data.startswith("buy_"))
def tratar_compra(call):
    bot.answer_callback_query(call.id✡️"✡️ GERANDO PIX AUTOMÁTICO... ✡️")
    cat,index = call.data.split("_")[1], int(call.data.split("_")[2])
    itens = listar_estoque_inteligente(cat)
    item  = itens[index]
    partes= item.split("|")
    if cat=="CONSUL":
        try:    valor=float(partes[1].replace("R$","").replace(",","."))
        except: valor=120.00
    else:
        valor = obter_valor_cc(partes[1].strip())
    pix_code = gerar_pix(valor,f"{cat} {partes[0]}")
    if pix_code:
        texto=(f"✡️ PAGAMENTO DA {cat} ✡️\n\n"
               f"💰 VALOR: R$ {valor:.2f}\n\n"
               f"👇 COPIE O CÓDIGO ABAIXO:\n\n`{pix_code}`\n\n"
               f"✡️ ENVIE O COMPROVANTE PARA @{DONO_USER} ✡️")
        bot.send_message(call.message.chat.id,texto,parse_mode="Markdown")
    else:
        bot.send_message(call.message.chat.id,"❌ ERRO AO GERAR PIX. CONTATE O SUPORTE.")

@bot.message_handler(commands=["add"])
def comando_add(message):
    if message.from_user.id != ADMIN_ID: return
    try:
        p = message.text.split(" ",2)
        cat = p[1].lower()
        with open(f"{cat}.txt","a",encoding="utf-8") as f: f.write(p[2]+"\n")
        bot.send_message(ADMIN_ID,"✡️ ADICIONADO COM SUCESSO ✡️")
    except:
        bot.send_message(ADMIN_ID,"✡️ USE: /ADD [CCS/CONSUL] DADOS ✡️")

print("🚀 UNIÃO ASTRO ONLINE - PIX ATIVO")
bot.polling()
