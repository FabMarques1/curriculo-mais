const formRegistro = document.getElementById("formRegistro");

const estadoSelect = document.getElementById("estado");
const cidadeSelect = document.getElementById("cidade");

const cepInput = document.getElementById("cep");

const logradouroInput =
    document.getElementById("logradouro");

const bairroInput =
    document.getElementById("bairro");

const dataNascimento =
    document.getElementById("data_nascimento");

const erroData =
    document.getElementById("erroData");


/*
=====================================================
ESTADOS DO BRASIL
=====================================================
*/

const estados = [

    { sigla: "AC", nome: "Acre" },
    { sigla: "AL", nome: "Alagoas" },
    { sigla: "AP", nome: "Amapá" },
    { sigla: "AM", nome: "Amazonas" },
    { sigla: "BA", nome: "Bahia" },
    { sigla: "CE", nome: "Ceará" },
    { sigla: "DF", nome: "Distrito Federal" },
    { sigla: "ES", nome: "Espírito Santo" },
    { sigla: "GO", nome: "Goiás" },
    { sigla: "MA", nome: "Maranhão" },
    { sigla: "MT", nome: "Mato Grosso" },
    { sigla: "MS", nome: "Mato Grosso do Sul" },
    { sigla: "MG", nome: "Minas Gerais" },
    { sigla: "PA", nome: "Pará" },
    { sigla: "PB", nome: "Paraíba" },
    { sigla: "PR", nome: "Paraná" },
    { sigla: "PE", nome: "Pernambuco" },
    { sigla: "PI", nome: "Piauí" },
    { sigla: "RJ", nome: "Rio de Janeiro" },
    { sigla: "RN", nome: "Rio Grande do Norte" },
    { sigla: "RS", nome: "Rio Grande do Sul" },
    { sigla: "RO", nome: "Rondônia" },
    { sigla: "RR", nome: "Roraima" },
    { sigla: "SC", nome: "Santa Catarina" },
    { sigla: "SP", nome: "São Paulo" },
    { sigla: "SE", nome: "Sergipe" },
    { sigla: "TO", nome: "Tocantins" }

];


/*
=====================================================
ADICIONAR ESTADOS NO SELECT
=====================================================
*/

estados.forEach(estado => {

    const option = document.createElement("option");

    option.value = estado.sigla;
    option.textContent = estado.nome;

    estadoSelect.appendChild(option);

});


/*
=====================================================
BUSCAR CIDADES DO ESTADO
=====================================================
*/

estadoSelect.addEventListener("change", async function () {

    const uf = this.value;

    cidadeSelect.innerHTML =
        '<option value="">Carregando cidades...</option>';

    cidadeSelect.disabled = true;


    if (!uf) {

        cidadeSelect.innerHTML =
            '<option value="">Selecione o estado primeiro</option>';

        return;

    }


    try {

        const resposta = await fetch(
            `https://servicodados.ibge.gov.br/api/v1/localidades/estados/${uf}/municipios`
        );

        const cidades = await resposta.json();

        cidadeSelect.innerHTML =
            '<option value="">Selecione a cidade</option>';


        cidades.forEach(cidade => {

            const option =
                document.createElement("option");

            option.value = cidade.id;

            option.textContent =
                cidade.nome;

            cidadeSelect.appendChild(option);

        });


        cidadeSelect.disabled = false;


    } catch (erro) {

        console.error(
            "Erro ao carregar cidades:",
            erro
        );


        cidadeSelect.innerHTML =
            '<option value="">Erro ao carregar cidades</option>';

    }

});


/*
=====================================================
BUSCAR ENDEREÇO PELO CEP
=====================================================
*/

cepInput.addEventListener("blur", async function () {

    const cep =
        this.value.replace(/\D/g, "");


    if (cep.length !== 8) {
        return;
    }


    try {

        const resposta = await fetch(
            `https://viacep.com.br/ws/${cep}/json/`
        );


        const endereco =
            await resposta.json();


        if (endereco.erro) {

            alert("CEP não encontrado.");

            return;

        }


        /*
        Preencher logradouro
        */

        logradouroInput.value =
            endereco.logradouro || "";


        /*
        Preencher bairro
        */

        bairroInput.value =
            endereco.bairro || "";


        /*
        Selecionar estado
        */

        estadoSelect.value =
            endereco.uf;


        /*
        Disparar carregamento das cidades
        */

        estadoSelect.dispatchEvent(
            new Event("change")
        );


        /*
        Aguardar cidades carregarem
        */

        setTimeout(() => {

            const cidadeOptions =
                Array.from(cidadeSelect.options);


            const cidadeEncontrada =
                cidadeOptions.find(option =>

                    option.textContent
                        .toLowerCase() ===
                    endereco.localidade
                        .toLowerCase()

                );


            if (cidadeEncontrada) {

                cidadeSelect.value =
                    cidadeEncontrada.value;

            }

        }, 500);


    } catch (erro) {

        console.error(
            "Erro ao consultar CEP:",
            erro
        );

    }

});


/*
=====================================================
MÁSCARA DE CEP
=====================================================
*/

cepInput.addEventListener("input", function () {

    let cep =
        this.value.replace(/\D/g, "");


    if (cep.length > 5) {

        cep =
            cep.substring(0, 5) +
            "-" +
            cep.substring(5, 8);

    }


    this.value = cep;

});


/*
=====================================================
DATA DE NASCIMENTO
=====================================================
*/

/*
Obtém a data atual.
*/

const hoje = new Date();


const ano =
    hoje.getFullYear();


const mes =
    String(
        hoje.getMonth() + 1
    ).padStart(2, "0");


const dia =
    String(
        hoje.getDate()
    ).padStart(2, "0");


const dataAtual =
    `${ano}-${mes}-${dia}`;


/*
Impede que o calendário
selecione uma data futura.
*/

dataNascimento.max =
    dataAtual;


/*
=====================================================
VALIDAÇÃO DA DATA
=====================================================
*/

formRegistro.addEventListener(
    "submit",
    function (event) {

        const data =
            new Date(
                dataNascimento.value +
                "T00:00:00"
            );


        const agora =
            new Date();



        if (

            !dataNascimento.value ||

            isNaN(
                data.getTime()
            ) ||

            data > agora

        ) {

            event.preventDefault();

            erroData.style.display =
                "block";

            dataNascimento.focus();


            return;

        }


        erroData.style.display =
            "none";

    }
);
