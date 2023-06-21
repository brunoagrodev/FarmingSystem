function validarFormulario() {
    // Validar dataNascimento
    var dataNascimento = new Date(document.getElementById("datanascimento").value); // Obtém o valor da data de nascimento do elemento com o ID "datanascimento"
    var dataNascimentoObjeto = new Date(dataNascimento); // Cria um objeto Date a partir da data de nascimento
    var hoje = new Date(); // Cria um objeto Date com a data de hoje
    var idade = hoje.getFullYear() - dataNascimentoObjeto.getFullYear(); // Calcula a diferença de anos entre hoje e a data de nascimento
    var meses = hoje.getMonth() - dataNascimentoObjeto.getMonth(); // Calcula a diferença de meses entre hoje e a data de nascimento

// Verifica se o mês atual é menor do que o mês de nascimento
// ou se o mês atual é igual ao mês de nascimento, mas o dia atual é menor do que o dia de nascimento
// Nesse caso, ainda não fez aniversário este ano, então subtrai um ano da idade
    if (meses < 0 || (meses === 0 && hoje.getDate() < dataNascimentoObjeto.getDate())) {
        idade--;
    }

// Verifica se a idade é maior do que 120
// Se for, exibe um alerta informando que a idade máxima permitida é de 120 anos
// e retorna false para interromper a execução do código
    if (idade > 120) {
        alert("A idade máxima permitida é de 120 anos.");
        return false;
    }


    // Validar CPF
    var cpf = document.getElementById("cpf").value;
    // Remove caracteres não numéricos
    cpf = cpf.replace(/[^\d]+/g, '');
    // Verifica se o CPF possui 11 dígitos
    if (cpf.length !== 11) {
        alert("CPF inválido! O CPF deve ter 11 dígitos.");
        return false;
    }
    // Verifica se todos os dígitos são iguais
    if (/^(\d)\1+$/.test(cpf)) {
        alert("CPF inválido! O CPF não pode ter todos os dígitos iguais.");
        return false;
    }
    // Validação dos dígitos verificadores
    var sum = 0;
    var remainder;
    // Validando o 10º dígito
    // Calculando a soma ponderada dos 9 primeiros dígitos
    for (var i = 1; i <= 9; i++) {
        sum += parseInt(cpf.substring(i - 1, i)) * (11 - i);
    }
    remainder = (sum * 10) % 11;
    // Analisando se o dígito verificador vale 10 ou 11 (é obrigatório que seja de 0 à 9)
    if ((remainder === 10) || (remainder === 11)) {
        remainder = 0;
    }
    // Verificando se o 10º dígito é válido de acordo com a soma ponderada
    if (remainder !== parseInt(cpf.substring(9, 10))) {
        alert("CPF inválido! Dígito verificador (10º dígito) não confere.");
        return false;
    }
    // Validando o 11º dígito
    sum = 0;
    // Calculando a soma ponderada dos 10 primeiros dígitos
    for (var i = 1; i <= 10; i++) {
        sum += parseInt(cpf.substring(i - 1, i)) * (12 - i);
    }
    remainder = (sum * 10) % 11;
    // Analisando se o dígito verificador vale 10 ou 11 (é obrigatório que seja de 0 à 9)
    if ((remainder === 10) || (remainder === 11)) {
        remainder = 0;
    }
    // Verificando se o 11º dígito é válido de acordo com a soma ponderada
    if (remainder !== parseInt(cpf.substring(10, 11))) {
        alert("CPF inválido! Dígito verificador (11º dígito) não confere.");
        return false;
    }


    // Restrição de 8 dígitos para a senha
    var senha = document.getElementById("senha").value;

    if (senha.length < 8) {
        alert("A senha deve ter no mínimo 8 caracteres.");
        return false;
    }
    return true;
}


