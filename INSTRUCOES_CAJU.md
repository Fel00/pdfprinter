# Instruções para Configuração da Caju Catering

## Arquivos Criados

Foram criados os seguintes arquivos para o sistema de contratos da Caju Catering:

1. **form_contrato_caju.php** - Formulário de contrato específico para a Caju
2. **gerar_pdf_caju.php** - Processamento e geração do PDF do contrato
3. **contrato_caju.php** - Template do contrato em HTML
4. **config_caju.php** - Arquivo de configuração centralizada
5. **INSTRUCOES_CAJU.md** - Este arquivo de instruções

## Configuração Necessária

### 1. Atualizar Informações da Empresa

Edite o arquivo `config_caju.php` e preencha as seguintes informações:

```php
$config_caju = [
    'nome' => 'DRINKE EVENTOS E SERVICOS LTDA',
    'cnpj' => '51.880.357/0001-42',
    'endereco' => 'alameda das Angélicas, 298, Cidade 2000, Fortaleza, Ceará',
    'representante' => 'FRANCISCO TAUNAY ANDRADE DE ALENCAR',
    'telefone' => '(85) XXXXX-XXXX', // Telefone a ser preenchido
    'email' => 'contato@cajucatering.com', // Email a ser preenchido
    
    // Dados bancários (a serem preenchidos)
    'banco' => 'Nome do Banco',
    'agencia' => 'XXXX',
    'conta' => 'XXXXX-X',
    'pix' => 'Chave PIX da empresa',
    
    'logo' => 'img/caju.png' // Logo já configurado
];
```

### 2. Informações Restantes a Serem Preenchidas

Ainda precisam ser preenchidas as seguintes informações:

- **Telefone de contato** da empresa
- **Email de contato** da empresa

**Informações já configuradas:**
- ✅ **CNPJ**: 51.880.357/0001-42
- ✅ **Nome**: DRINKE EVENTOS E SERVICOS LTDA
- ✅ **Endereço**: alameda das Angélicas, 298, Cidade 2000, Fortaleza, Ceará
- ✅ **Representante**: FRANCISCO TAUNAY ANDRADE DE ALENCAR
- ✅ **Dados bancários**: BANCO DO NORDESTE, AG 300, CONTA 29059-4
- ✅ **PIX**: 51880357000142

### 3. Teste do Sistema

Após preencher as informações:

1. Acesse `http://localhost:8000`
2. Clique em "Gerar Contrato - Caju"
3. Preencha o formulário de teste
4. Gere o PDF para verificar se todas as informações estão corretas

### 4. Personalizações Adicionais

Se necessário, você pode:

- Ajustar os tipos de buffet no formulário (`form_contrato_caju.php`)
- Modificar as cláusulas do contrato (`contrato_caju.php`)
- Alterar o layout ou cores do formulário

## Funcionalidades Implementadas

### ✅ Formulário de Contrato Caju
- **Informações do contratante**: Nome, CPF, telefone (com máscaras)
- **Informações do evento**: Endereço, quantidade de pessoas, tipo de bufê
- **Cardápio detalhado**:
  - Mesa fixa (campos adicionáveis dinâmicos)
  - Volantes (campos adicionáveis dinâmicos)
  - Bebidas não alcoólicas (checkbox)
  - Ornamentação (campos adicionáveis dinâmicos)
  - Louças e material (campo de texto)
  - Equipe (campo de texto)
- **Data e horários**: Data, início, conclusão, chegada
- **Valores**: Valor total do serviço (com máscara de moeda)

### ✅ Funcionalidades JavaScript
- **Função addMenuItem**: Permite adicionar itens dinamicamente
- **Máscaras de entrada**: CPF, telefone e valores monetários
- **Validação de campos**: Campos obrigatórios e formatação automática

### ✅ Geração de PDF
- **Template personalizado** baseado no contrato real da Caju
- **Cardápio dinâmico** que aparece apenas se preenchido
- **Dados bancários** já configurados
- **Logo da Caju** incluído no final do contrato
- **Processamento completo** de todos os campos do formulário

## Estrutura do Sistema

```
pdfprinter/
├── form_contrato_caju.php    # Formulário de contrato Caju
├── gerar_pdf_caju.php        # Processamento do contrato Caju
├── contrato_caju.php         # Template do contrato Caju
├── config_caju.php           # Configurações da empresa Caju
├── form_contrato.php         # Formulário de contrato Feiju (original)
├── gerar_pdf.php             # Processamento do contrato Feiju (original)
├── contrato.php              # Template do contrato Feiju (original)
└── index.php                 # Menu principal (atualizado)
```

## Observações

- O sistema mantém a funcionalidade original da Feiju intacta
- A nova funcionalidade da Caju é completamente independente
- Todas as informações sensíveis estão centralizadas no arquivo de configuração
- O logo da Caju (`caju.png`) já está configurado e funcionando
