from flask import Flask, request, jsonify
from selenium import webdriver
from selenium.webdriver.common.by import By
import time

app = Flask(__name__)

@app.route('/validar', methods=['POST'])
def validar_dados():
    data = request.json
    cpf = data.get("cpf")
    data_nascimento = data.get("dataNascimento")

    if not cpf or not data_nascimento:
        return jsonify({"erro": "Campos obrigatórios faltando"}), 400

    options = webdriver.ChromeOptions()
    options.add_argument("--headless")  # Para rodar sem abrir o navegador
    options.add_argument("--no-sandbox")
    options.add_argument("--disable-dev-shm-usage")

    driver = webdriver.Chrome(options=options)

    try:
        # Acesse o site externo
        driver.get("https://www.siteexterno.com/validar")
        time.sleep(2)  # Aguarde o carregamento da página

        # Insira CPF e Data de Nascimento
        driver.find_element(By.NAME, "cpf").send_keys(cpf)
        driver.find_element(By.NAME, "dataNascimento").send_keys(data_nascimento)

        # Clicar no CAPTCHA
        driver.find_element(By.CLASS_NAME, "captcha-class").click()
        time.sleep(5)  # Aguarde a validação do CAPTCHA

        # Enviar o formulário
        driver.find_element(By.ID, "botao-submit").click()
        time.sleep(3)

        # Capturar resposta
        resultado = driver.page_source  # Captura a resposta da validação
        return jsonify({"resultado": resultado})

    except Exception as e:
        return jsonify({"erro": str(e)}), 500

    finally:
        driver.quit()

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
