
const apiKey = "32d21551a740c3f6b6e6d5ae74657fa3";
// const apiCountryURL = "https://countryflagsapi.com/png/";
const apiCountryURL = "https://flagsapi.com/";
// const apiUnsplash = "https://source.unsplash.com/1600x900/?";
// const apiUnsplash = "https://api.unsplash.com/search/photos?query=";
const apiUnsplashAccessKey = "je-wGgt_SPs2XsIsAvVkKtI8vegj3_ZnNLIlHiDQ67E"; // Sua chave de acesso da API do Unsplash
const apiUnsplash = "https://api.unsplash.com/search/photos?query=";


const cityInput = document.querySelector("#city-input");
const searchBtn = document.querySelector("#search");

const cityElement = document.querySelector("#city");
const tempElement = document.querySelector("#temperature span");
const descElement = document.querySelector("#description");
const weatherIconElement = document.querySelector("#weather-icon");
const countryElement = document.querySelector("#country");
const umidityElement = document.querySelector("#umidity span");
const windElement = document.querySelector("#wind span");

const weatherContainer = document.querySelector("#weather-data");

const errorMessageContainer = document.querySelector("#error-message");
const loader = document.querySelector("#loader");

const suggestionContainer = document.querySelector("#suggestions");
const suggestionButtons = document.querySelectorAll("#suggestions button");

// Loader
const toggleLoader = () => {
  loader.classList.toggle("hide");
};

const getWeatherData = async (city) => {
  toggleLoader();

  const apiWeatherURL = `https://api.openweathermap.org/data/2.5/weather?q=${city}&units=metric&appid=${apiKey}&lang=pt_br`;

  const res = await fetch(apiWeatherURL);
  const data = await res.json();

  toggleLoader();

  return data;
};

// Tratamento de erro
const showErrorMessage = () => {
  errorMessageContainer.classList.remove("hide");
};

const hideInformation = () => {
  errorMessageContainer.classList.add("hide");
  weatherContainer.classList.add("hide");

  suggestionContainer.classList.add("hide");
};

const showWeatherData = async (city) => {
  hideInformation();

  const data = await getWeatherData(city);

  if (data.cod === "404") {
    showErrorMessage();
    return;
  }

  cityElement.innerText = data.name;
  tempElement.innerText = parseInt(data.main.temp);
  descElement.innerText = data.weather[0].description;
  weatherIconElement.setAttribute(
    "src",
    `http://openweathermap.org/img/wn/${data.weather[0].icon}.png`
  );
  countryElement.setAttribute("src", apiCountryURL + data.sys.country + "/flat/64.png");
  umidityElement.innerText = `${data.main.humidity}%`;
  windElement.innerText = `${data.wind.speed}km/h`;

  // Change bg image
//   document.body.style.backgroundImage = `url("${apiUnsplash + city}"&client_id=RkxxoWtFwM1SsV3cOinbJ78wu2hqwVuVDuxV9hn9i_0)`;
    const unsplashURL = `${apiUnsplash}${city}&client_id=${apiUnsplashAccessKey}`;

    const unsplashRes = await fetch(unsplashURL);
    const unsplashData = await unsplashRes.json();

    if (unsplashData.results.length > 0) {
    document.body.style.backgroundImage = `url("${unsplashData.results[0].urls.regular}")`;
    }

  weatherContainer.classList.remove("hide");
};

searchBtn.addEventListener("click", async (e) => {
  e.preventDefault();

  const city = cityInput.value;

  showWeatherData(city);
});

cityInput.addEventListener("keyup", (e) => {
  if (e.code === "Enter") {
    const city = e.target.value;

    showWeatherData(city);
  }
});

// Sugestões
suggestionButtons.forEach((btn) => {
  btn.addEventListener("click", () => {
    const city = btn.getAttribute("id");

    showWeatherData(city);
  });
});


// // Simulação de login de usuário (pode ser feito através de uma variável global, cookie, ou autenticação)
// const usuarioLogado = true;

// // Função para obter as últimas cidades pesquisadas
// function obterUltimasPesquisas() {
//   // Suponhamos que você tenha uma forma de obter as últimas cidades pesquisadas pelo usuário logado
//   if (usuarioLogado) {
//     // Simulando que o usuário fez algumas pesquisas recentes
//     return ["São Paulo", "Rio de Janeiro", "Paris", "Londres"];
//   } else {
//     // Caso não haja usuário logado, retornar um valor padrão ou vazio
//     return [];
//   }
// }

// // Função para atualizar as sugestões de cidades no DOM
// function atualizarSugestoes() {
//   const ultimasPesquisas = obterUltimasPesquisas();

//   // Selecionar o container de sugestões
//   const suggestionsContainer = document.getElementById("suggestions");

//   // Limpar sugestões existentes
//   suggestionsContainer.innerHTML = "";

//   // Adicionar botões para as últimas 4 pesquisas
//   for (let i = 0; i < Math.min(4, ultimasPesquisas.length); i++) {
//     const button = document.createElement("button");
//     button.textContent = ultimasPesquisas[i];
//     button.addEventListener("click", function() {
//       pesquisarCidade(ultimasPesquisas[i]);
//     });
//     suggestionsContainer.appendChild(button);
//   }
// }

// // Função para pesquisar a cidade quando um botão de sugestão é clicado
// function pesquisarCidade(cidade) {
//   const inputCity = document.getElementById("city-input");
//   inputCity.value = cidade;
//   buscarClima(); // Função que você já deve ter para buscar o clima da cidade
// }

// // Event listener para atualizar as sugestões quando o documento estiver pronto
// document.addEventListener("DOMContentLoaded", function() {
//   atualizarSugestoes();
// });

// Função para atualizar as sugestões de cidades no DOM
