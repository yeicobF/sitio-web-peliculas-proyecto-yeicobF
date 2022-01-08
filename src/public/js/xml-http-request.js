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
    const xhr = new XMLHttpRequest();
    xhr.open(method, url);

    xhr.responseType = "json";

    if (data) {
      xhr.setRequestHeader("Content-Type", "application/json");
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
      reject("Algo salió mal.");
    };

    console.log(data);
    xhr.send(JSON.stringify(data));
  });
  return promise;
};

const getData = (url) => {
  sendHttpRequest("GET", url).then((responseData) => {
    console.log(responseData);
    return responseData;
  });
};

/**
 * Enviar datos mediante POST.
 */
const sendData = (url, data) => {
  sendHttpRequest("POST", url, data)
    .then((responseData) => {
      console.log(responseData);
      return responseData;
    })
    .catch((err) => {
      console.log(err);
      return err;
    });
};
