// Função para aplicar a máscara de CPF
function mascaraCPF(campo) {
    campo.value = campo.value
        .replace(/\D/g, '') // Remove tudo que não é dígito
        .replace(/(\d{3})(\d)/, '$1.$2') // Coloca um ponto após os primeiros 3 dígitos
        .replace(/(\d{3})(\d)/, '$1.$2') // Coloca um ponto após os próximos 3 dígitos
        .replace(/(\d{3})(\d{1,2})/, '$1-$2') // Coloca um hífen antes dos últimos 2 dígitos
        .replace(/(-\d{2})\d+?$/, '$1'); // Impede que o usuário digite mais caracteres
}

// Aplica a máscara ao campo de CPF
const cpfInput = document.getElementById('cpf');
cpfInput.addEventListener('input', function () {
    mascaraCPF(this);
});

// function validarCPF(cpf) {
//     // Remove caracteres não numéricos
//     cpf = cpf.replace(/\D/g, '');

//     // Verifica se o CPF tem 11 dígitos ou se é uma sequência de dígitos repetidos
//     if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) {
//         return false;
//     }

//     // Função para calcular o dígito verificador
//     function calcularDigito(parteCPF, multiplicadorInicial) {
//         let soma = 0;
//         for (let i = 0; i < parteCPF.length; i++) {
//             soma += parseInt(parteCPF.charAt(i)) * multiplicadorInicial--;
//         }
//         const resto = soma % 11;
//         return resto < 2 ? 0 : 11 - resto;
//     }

//     // Calcula o primeiro dígito verificador
//     const primeiroDigito = calcularDigito(cpf.substring(0, 9), 10);

//     // Calcula o segundo dígito verificador
//     const segundoDigito = calcularDigito(cpf.substring(0, 10), 11);

//     // Verifica se os dígitos calculados são iguais aos dígitos informados
//     return cpf.charAt(9) === primeiroDigito.toString() && cpf.charAt(10) === segundoDigito.toString();
// }

const mensagemErro = document.getElementById('erro-cpf');

cpfInput.addEventListener('blur', function () {
    if (!validarCPF(this.value)) {
        mensagemErro.textContent = 'CPF inválido. Digite um CPF válido.';
        mensagemErro.style.display = 'block';
    } else {
        mensagemErro.style.display = 'none';
    }
});

// function validarDataNascimento(data) {
//     // Converte a data inserida para um objeto Date
//     const dataNascimento = new Date(data);
//     const hoje = new Date(); // Data atual

//     // Verifica se a data de nascimento é posterior ao dia atual
//     if (dataNascimento > hoje) {
//         return false; // Data inválida (futura)
//     }

//     return true; // Data válida
// }



// Função para validar o CPF
function validarCPF(cpf) {
    cpf = cpf.replace(/\D/g, '');
    if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) {
        return false;
    }
    function calcularDigito(parteCPF, multiplicadorInicial) {
        let soma = 0;
        for (let i = 0; i < parteCPF.length; i++) {
            soma += parseInt(parteCPF.charAt(i)) * multiplicadorInicial--;
        }
        const resto = soma % 11;
        return resto < 2 ? 0 : 11 - resto;
    }
    const primeiroDigito = calcularDigito(cpf.substring(0, 9), 10);
    const segundoDigito = calcularDigito(cpf.substring(0, 10), 11);
    return cpf.charAt(9) === primeiroDigito.toString() && cpf.charAt(10) === segundoDigito.toString();
}

// Função para validar a data de nascimento
function validarDataNascimento(data) {
    const dataNascimento = new Date(data);
    const hoje = new Date();
    return dataNascimento <= hoje; // Verifica se a data não é futura
}

// Adiciona um evento de confirmação ao formulário
const formulario = document.getElementById('formulario-votacao');

formulario.addEventListener('submit', function (event) {
    // Valida o CPF
    const cpfInput = document.getElementById('cpf');
    if (!validarCPF(cpfInput.value)) {
        event.preventDefault(); // Impede o envio do formulário
        alert('CPF inválido. Digite um CPF válido.');
        return;
    }

    // Valida a data de nascimento
    const dataNascimentoInput = document.getElementById('dataNascimento');
    if (!validarDataNascimento(dataNascimentoInput.value)) {
        event.preventDefault(); // Impede o envio do formulário
        alert('Data de nascimento inválida. Você não pode nascer no futuro!');
        return;
    }

    // Exibe a confirmação
    const confirmacao = confirm('Tem certeza de que deseja enviar seu voto?');
    if (!confirmacao) {
        event.preventDefault(); // Cancela o envio do formulário
    }
});