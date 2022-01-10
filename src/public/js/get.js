/**
 * Agregar parámetros get a una query.
 *
 * https://developer.mozilla.org/en-US/docs/Web/API/URLSearchParams/append
 * https://developer.mozilla.org/en-US/docs/Web/API/URLSearchParams
 * https://developer.mozilla.org/en-US/docs/Web/API/URL/search
 * https://stackoverflow.com/a/67664558/13562806
 *
 * @param {string} url URL al que se le agregarán los elementos.
 * @param {Array} params Parámetros con sus valores.
 * @returns {string} URL con los parámetros.
 */
const appendGetParamsToUrl = ({ url, paramsObj }) => {
  // Construimos un objeto con los parámetros de la URL, los cuales obtenemos de
  // un objeto.
  const searchParams = new URLSearchParams(paramsObj);
  // Construimos un objeto URL con la URL que se envió.
  const newUrl = new URL(url);

  // Establecemos los parámetros de la búsqueda.
  newUrl.search = searchParams.toString();
  console.log(newUrl);

  return newUrl.toString();
};
