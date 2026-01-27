// Espera o carregamento do DOM para aplicar as máscaras
document.addEventListener("DOMContentLoaded", function () {

    // Máscara dinâmica para CPF ou CNPJ
    const cpfCnpjInput = document.getElementById("cpf");
    if (cpfCnpjInput) {
        // Aplica máscara inicial de CPF
        let inputMask = Inputmask({
            mask: "999.999.999-99",
            placeholder: "_",
        });
        inputMask.mask(cpfCnpjInput);

        // Monitora mudanças para alternar entre CPF e CNPJ
        cpfCnpjInput.addEventListener("input", function () {
            // Remove caracteres não numéricos
            const value = this.value.replace(/\D/g, "");
            
            // Se tem 14 dígitos, aplica máscara CNPJ
            if (value.length >= 11) {
                if (value.length > 11) {
                    // Remove máscara anterior e aplica CNPJ
                    Inputmask.remove(this);
                    Inputmask({
                        mask: "99.999.999/9999-99",
                        placeholder: "_",
                    }).mask(this);
                    // Reaplica os valores
                    this.value = value.substring(0, 14);
                    this.value = this.value.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, "$1.$2.$3/$4-$5");
                }
            } else if (value.length <= 11) {
                // Usa máscara CPF
                Inputmask.remove(this);
                Inputmask({
                    mask: "999.999.999-99",
                    placeholder: "_",
                }).mask(this);
            }
        });
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
