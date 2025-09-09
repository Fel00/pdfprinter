document.addEventListener("DOMContentLoaded", function () {
    const valorBufetInput = document.getElementById("valor_bufet");
    const valorDeslocamentoInput = document.getElementById("valor_deslocamento");
    const valorTotalInput = document.getElementById("valor_total");

    // Máscara de moeda para os campos
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

    // Aplica máscara nos campos
    Inputmask(currencyMask).mask(valorBufetInput);
    Inputmask(currencyMask).mask(valorDeslocamentoInput);
    Inputmask(currencyMask).mask(valorTotalInput);

    // Função para calcular o valor total
    function calcularValorTotal() {
        const valorBufet = parseFloat(valorBufetInput.inputmask.unmaskedvalue() || 0); // Remove máscara e converte para número
        const valorDeslocamento = parseFloat(valorDeslocamentoInput.inputmask.unmaskedvalue() || 0); // Remove máscara e converte para número
        const valorTotal = valorBufet + valorDeslocamento; // Soma os valores

        // Atualiza o campo com o total formatado
        valorTotalInput.value = `${valorTotal.toLocaleString("pt-BR", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        })}`;
    }

    // Adiciona eventos para recalcular o valor total sempre que houver input ou mudança
    valorBufetInput.addEventListener("input", calcularValorTotal);
    valorBufetInput.addEventListener("blur", calcularValorTotal); // Garante atualização ao perder o foco
    valorDeslocamentoInput.addEventListener("input", calcularValorTotal);
    valorDeslocamentoInput.addEventListener("blur", calcularValorTotal);

    // Calcula o total ao carregar a página
    calcularValorTotal();
});
