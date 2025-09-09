// Espera o carregamento do DOM para aplicar as máscaras
document.addEventListener("DOMContentLoaded", function () {

    // Máscara para CPF no formato 000.000.000-00
    Inputmask({
        mask: "999.999.999-99",
        placeholder: "_",
    }).mask(document.getElementById("cpf"));

    Inputmask({
        mask: "(99) 99999-9999",
        placeholder: "_",
    }).mask(document.getElementById("telefone"));

    // Máscara para a quantia no formato monetário (ex: R$ 1.234,56)
    Inputmask({
        alias: "currency",
        prefix: "R$ ",
        groupSeparator: ".",
        radixPoint: ",",
        autoGroup: true,
        digits: 2,
        digitsOptional: false,
        placeholder: "0",
        clearMaskOnLostFocus: true,
    }).mask(document.getElementById("quantia"));

    // Máscara para data no formato DD/MM/AAAA (caso prefira sobrepor ao input date padrão)
    Inputmask({
        mask: "99/99/9999",
        placeholder: "_",
    }).mask(document.getElementById("data"));

    // Restrição para o campo "Contratante", permitindo apenas letras e espaços
    document.getElementById("contratante").addEventListener("input", function (e) {
        // Remove qualquer caractere que não seja letra ou espaço
        this.value = this.value.replace(/[^a-zA-ZÀ-ÿ\s]/g, "");
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const tipoBufetSelect = document.getElementById("tipo_bufet");
    const descricaoBufetContainer = document.getElementById("descricao_bufet_container");
    const descricaoBufetInput = document.getElementById("descricao_bufet");

    // Adiciona um evento para monitorar mudanças na seleção do tipo de bufê
    tipoBufetSelect.addEventListener("change", function () {
        if (tipoBufetSelect.value === "Feiju Personalizada:") {
            // Mostra o campo adicional para descrição
            descricaoBufetContainer.style.display = "block";
            descricaoBufetInput.required = true; // Torna o campo obrigatório
        } else {
            // Oculta o campo adicional e remove a obrigatoriedade
            descricaoBufetContainer.style.display = "none";
            descricaoBufetInput.required = false;
        }
    });
});
