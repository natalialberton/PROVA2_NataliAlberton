function validarFuncionario() {
    let nome = document.getElementById("nome_funcionario").value;
    let telefone = document.getElementById("telefone").value;
    let email = document.getElementById("email").value;

    if (nome.length < 3) {
        alert("O nome do funcionário deve ter pelo menos 3 caracteres.");
        return false;
    }

    let regexTelefone = /^[0-9]{10,11}$/;
    if (!regexTelefone.test(telefone)) {
        alert("Digite um telefone válido (10 ou 11 dígitos).");
        return false;
    }

    let regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regexEmail.test(email)) {
        alert("Digite um e-mail válido.");
        return false;
    }

    return true;
}

//MÁSCARAS PARA OS FORMULÁRIOS
function mascara(o, f) {
    objeto = o;
    funcao = f;
    setTimeout("executaMascara()", 1);
}

function executaMascara() {
    objeto.value = funcao(objeto.value);
}

//Máscara nome
function nomeMasc(variavel) {
    variavel = variavel.replace(/[^a-zA-Z áÁéÉíÍóÓúÚçÇ]/g,"");
    return variavel;
}

//Máscara Telefone
function telefoneMasc(variavel) {
    variavel = variavel.replace(/\D/g,"");
    variavel = variavel.replace(/^(\d\d)(\d)/g,"($1) $2");
    variavel = variavel.replace(/(\d{5})(\d)/,"$1-$2");
    return variavel;
}
