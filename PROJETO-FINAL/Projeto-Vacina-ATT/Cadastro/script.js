// Formatação do CPF
document.getElementById("cpf").addEventListener("input", function (e) {
    let cpf = e.target.value.replace(/\D/g, ""); // Remove caracteres não numéricos
    cpf = cpf.replace(/^(\d{3})(\d)/, "$1.$2");
    cpf = cpf.replace(/^(\d{3})\.(\d{3})(\d)/, "$1.$2.$3");
    cpf = cpf.replace(/^(\d{3})\.(\d{3})\.(\d{3})(\d)/, "$1.$2.$3-$4");
    e.target.value = cpf; // Atualiza o valor do campo
});

// Alternar visibilidade da senha
document.querySelector(".eye-svg").addEventListener("click", function() {
    const senhaInput = document.getElementById("senha");
    const type = senhaInput.type === "password" ? "text" : "password";
    senhaInput.type = type; // Altera o tipo de input entre texto e senha
});

// Formatação do telefone (limita o número de caracteres a 14, incluindo máscara)
function formatarTelefone(e) {
    let input = e.target;
    let value = input.value.replace(/\D/g, ''); // Remove caracteres não numéricos
    
    // Limita a quantidade de caracteres (números + formatação) a 14
    if (value.length > 11) {
        value = value.substring(0, 11); // Limita o valor para 11 dígitos
    }
    
    // Formatação do número de telefone com parênteses, espaço e traço
    if (value.length <= 2) {
        input.value = value.replace(/(\d{2})/, '($1) '); // Formato (XX)
    } else if (value.length <= 7) {
        input.value = value.replace(/(\d{2})(\d{5})/, '($1) $2-'); // Formato (XX) XXXXX
    } else {
        input.value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3'); // Formato (XX) XXXXX-XXXX
    }
}

// Aplique o evento para o campo de telefone
document.getElementById('telefone').addEventListener('input', formatarTelefone);
