document.addEventListener("DOMContentLoaded", function () {
    const cpfInput = document.getElementById("cpf");
    const useCnpjCheckbox = document.getElementById("useCnpj");
    const cpfLabel = document.getElementById("cpfLabel");
    const telefoneInput = document.getElementById("telefone");
    const contratanteInput = document.getElementById("contratante");
    const tipoBufetSelect = document.getElementById("tipo_bufet");
    const descricaoBufetContainer = document.getElementById("descricao_bufet_container");
    const descricaoBufetInput = document.getElementById("descricao_bufet");

    if (cpfInput && useCnpjCheckbox && cpfLabel) {
        function applyMask(useCnpj) {
            Inputmask.remove(cpfInput);

            if (useCnpj) {
                cpfLabel.textContent = "CNPJ:";
                cpfInput.placeholder = "__.__.___/____-__";
                Inputmask({
                    mask: "99.999.999/9999-99",
                    placeholder: "_",
                }).mask(cpfInput);
            } else {
                cpfLabel.textContent = "CPF:";
                cpfInput.placeholder = "___.___.___.____-__";
                Inputmask({
                    mask: "999.999.999-99",
                    placeholder: "_",
                }).mask(cpfInput);
            }

            cpfInput.value = "";
        }

        useCnpjCheckbox.addEventListener("change", function () {
            applyMask(this.checked);
        });

        applyMask(false);
    }

    if (telefoneInput) {
        Inputmask({
            mask: "(99) 99999-9999",
            placeholder: "_",
        }).mask(telefoneInput);
    }

    if (contratanteInput) {
        contratanteInput.addEventListener("input", function () {
            this.value = this.value.replace(/[^a-zA-ZÀ-ÿ\s]/g, "");
        });
    }

    if (tipoBufetSelect && descricaoBufetContainer && descricaoBufetInput) {
        function atualizarDescricaoBufet() {
            const mostrar = tipoBufetSelect.value === "Feiju Personalizada";
            descricaoBufetContainer.style.display = mostrar ? "block" : "none";
            descricaoBufetInput.required = mostrar;
            if (!mostrar) {
                descricaoBufetInput.value = "";
            }
        }

        tipoBufetSelect.addEventListener("change", atualizarDescricaoBufet);
        atualizarDescricaoBufet();
    }
});
