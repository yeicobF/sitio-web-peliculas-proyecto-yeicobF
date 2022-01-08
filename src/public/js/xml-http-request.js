/**
 * Enviar una solicitud HTTP con `XMLHttpRequest`.
 *
 * Fuentes:
 *
 * - https://www.youtube.com/watch?v=4K33w-0-p2c&ab_channel=Academind
 * - https://github.com/academind/xhr-fetch-axios-intro/blob/xhr/xhr.js
 *
 * @param {string} method Método POST o GET.
 * @param {string} url URL al que se hará la petición.
 * @param {object} data Datos a enviar.
 * @returns Promesa, por lo que es una solicitud asíncrona.
 */
const sendHttpRequest = (method, url, data) => {
  const promise = new Promise((resolve, reject) => {
    // console.log("request method", method);
    const xhr = new XMLHttpRequest();
    xhr.open(method, url);

    xhr.responseType = "json";

    if (data) {
      xhr.setRequestHeader("Content-Type", "application/json");
      // console.log(data);
    }

    xhr.onload = () => {
      if (xhr.status >= 400) {
        // Terminar la promesa indicando que salió mal. Esto levanta el evento
        // xhr.onerror()
        reject(xhr.response);
      } else {
        resolve(xhr.response);
      }
    };

    xhr.onerror = () => {
      reject(new Error("Algo salió mal."));
    };

    xhr.send(JSON.stringify(data));
  });
  return promise;
};

/**
 * Realiza un get de forma asíncrona, pero esperando el resultado.
 *
 * Esto permite que, se obtengan los datos antes de seguir con alguna otra
 * operación. Podría ser que en algún caso no querramos esperar, pero por el
 * momento ásí cumple con las funcionalidades del proyecto.
 * @param {string} url Url del método.
 * @returns Promesa
 */
const getData = async (url) => {
  // El .then lo hacemos en donde llamemos a la función para poder ejecutar los
  // siguientes pasos.
  return await sendHttpRequest("GET", url);

  // return await sendHttpRequest("GET", url)
  //   .then((responseData) => {
  //     console.log(responseData);
  //     return responseData;
  //   })
  //   .catch((err) => {
  //     console.log(err);
  //   });
};

/**
 * Enviar datos mediante POST.
 */
const sendData = async (url, data) => {
  return await sendHttpRequest("POST", url, data);

  // sendHttpRequest("POST", url, data)
  //   .then((responseData) => {
  //     console.log(responseData);
  //     return responseData;
  //   })
  //   .catch((err) => {
  //     console.log(err);
  //   });
};
