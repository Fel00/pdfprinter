// Espera o carregamento do DOM para aplicar as máscaras
document.addEventListener("DOMContentLoaded", function () {

    // Referências aos elementos
    const cpfInput = document.getElementById("cpf");
    const useCnpjCheckbox = document.getElementById("useCnpj");
    const cpfLabel = document.getElementById("cpfLabel");

    if (cpfInput && useCnpjCheckbox) {
        // Função para aplicar a máscara correta
        function applyMask(useCnpj) {
            // Remove máscara anterior
            Inputmask.remove(cpfInput);
            
            if (useCnpj) {
                // Aplica máscara CNPJ: 99.999.999/9999-99
                cpfLabel.textContent = "CNPJ:";
                cpfInput.placeholder = "__.__._____/____-__";
                Inputmask({
                    mask: "99.999.999/9999-99",
                    placeholder: "_",
                }).mask(cpfInput);
            } else {
                // Aplica máscara CPF: 999.999.999-99
                cpfLabel.textContent = "CPF:";
                cpfInput.placeholder = "___.___.___.____-__";
                Inputmask({
                    mask: "999.999.999-99",
                    placeholder: "_",
                }).mask(cpfInput);
            }
            
            // Limpa o campo
            cpfInput.value = "";
            cpfInput.focus();
        }

        // Listener para mudanças na checkbox
        useCnpjCheckbox.addEventListener("change", function () {
            applyMask(this.checked);
        });

        // Aplicar máscara inicial (CPF)
        applyMask(false);
    }

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
