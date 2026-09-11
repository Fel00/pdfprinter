document.addEventListener("DOMContentLoaded", function () {
    const valorBufetInput = document.getElementById("valor_bufet");
    const valorDeslocamentoInput = document.getElementById("valor_deslocamento");
    const valorTotalInput = document.getElementById("valor_total");
    const tipoBufetSelect = document.getElementById("tipo_bufet");
    const descricaoBufetContainer = document.getElementById("descricao_bufet_container");
    const descricaoBufetInput = document.getElementById("descricao_bufet");

    if (!valorBufetInput || !valorDeslocamentoInput || !valorTotalInput) {
        return;
    }

    const currencyMask = {
        alias: "currency",
        prefix: "R$ ",
        groupSeparator: ".",
        radixPoint: ",",
        autoGroup: true,
        digits: 2,
        digitsOptional: false,
        placeholder: "0",
        clearMaskOnLostFocus: true,
    };

    Inputmask(currencyMask).mask(valorBufetInput);
    Inputmask(currencyMask).mask(valorDeslocamentoInput);

    function calcularValorTotal() {
        const valorBufet = parseFloat(valorBufetInput.inputmask.unmaskedvalue() || 0);
        const valorDeslocamento = parseFloat(valorDeslocamentoInput.inputmask.unmaskedvalue() || 0);
        const valorTotal = valorBufet + valorDeslocamento;

        valorTotalInput.value = valorTotal.toLocaleString("pt-BR", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });
    }

    valorBufetInput.addEventListener("input", calcularValorTotal);
    valorBufetInput.addEventListener("blur", calcularValorTotal);
    valorDeslocamentoInput.addEventListener("input", calcularValorTotal);
    valorDeslocamentoInput.addEventListener("blur", calcularValorTotal);

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

    calcularValorTotal();
});
