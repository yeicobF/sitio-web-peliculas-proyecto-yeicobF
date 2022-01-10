/**
 * Interacciones con comentarios.
 */

/**
 * Obtener método de la interacción actual (POST, PUT, DELETE) y la interacción actual.
 * @param object Interacciones en la base de datos y la interacción actual
 * @returns Objeto con el método y la interacción actual.
 */
const getCurrentInteractionMethod = ({
  dbCommentInteraction,
  currentInteraction,
}) => {
  /**
   * Si no se ha seleccionado método, elegirlo ahora. No puede ser PUT y
   * DELETE al mismo tiempo.
   */
  console.log(
    "dbCommentInteraction.user_interaction",
    dbCommentInteraction.user_interaction,
  );
  console.log("currentInteraction", currentInteraction);

  // Si no hay interacción actualmente, hacer inserción.
  if (!Object.hasOwn(dbCommentInteraction, "user_interaction")) {
    method = "POST";
    /**
     * Devolvemos el método y la interacción actual.
     *
     * No dejar pasar, ya que, si no existe la propiedad, el currentInteraction
     * es diferente del dbCommentInteraction.user_interaction, ya que, el
     * primero sí existe, pero el segundo es undefined, por lo que, son
     * diferentes.
     */
    return [method, currentInteraction];
  }
  // Si el comentario ya tiene interacción, eliminarla. La interacción ya
  // está seleccionada, por lo que hay que actualizar.
  if (dbCommentInteraction.user_interaction === currentInteraction) {
    method = "DELETE";
  }
  /**
   * Ya que obtuvimos la interacción actual del comentario y el usuario,
   * ver si esta existe con el usuario actual, y si, el botón presionado
   * es distinto al de la interacción de la BD, hacer PUT.
   *
   * https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Object/hasOwn
   */
  if (dbCommentInteraction.user_interaction !== currentInteraction) {
    // La actualización la hace automáticamente su respectivo método.
    method = "PUT";

    // La interacción actual en la base de datos es la contraria al botón que
    // presionamos.
    currentInteraction = dbCommentInteraction.user_interaction;
  }

  /** Devolvemos el método y la interacción actual. */
  return [method, currentInteraction];
};

/**
 * Actualizar interacciones de los comentarios.
 *
 * Dependiendo de la interacción que haya en la base de datos, se modifican los
 * elementos para renderizar correctamente qué botón está seleccionado,
 * modificar el número de likes, tomando en cuenta la interacción del usuario
 * actual.
 *
 * @param {string} selectedClass Clase de CSS que indica que el elemento está
 * seleccionado.
 * @param {object} dbCommentInteraction Objeto con las interacciones y sus
 * valores en la base de datos. Si el usuario ha interactuado con algún botón,
 * se indica en una tercera propiedad opcional llamada `user-interaction`.
 * @param {object} buttons Botones de like y dislike obtenidos del DOM.
 * @param {object} interactionData Elemento del DOM que gestiona el número de
 * likes y dislikes.
 */
const updateCommentInteractions = (
  selectedClass,
  dbCommentInteraction,
  buttons,
  interactionData,
) => {
  console.log("update comment interactions");
  console.table(dbCommentInteraction);

  /**
   * Eliminamos el seleccionado de cada botón, ya que a continuación se
   * establecerá su estado dependiendo de su valor en la BD.
   */
  Object.values(buttons).forEach((value) => {
    value.classList.remove(selectedClass);
  });
  /**
   * https://developer.mozilla.org/es/docs/Web/JavaScript/Reference/Statements/for...of
   */
  for (const [interaction, value] of Object.entries(dbCommentInteraction)) {
    if (interaction === "user_interaction") {
      buttons[value].classList.add(selectedClass);
      continue;
    }
    interactionData[interaction].value = value;
    // https://attacomsian.com/blog/javascript-update-element-text
    interactionData[interaction].textContent = value;
  }
};

/**
 * El elemento es un botón de interacción.
 * @param {} target Elemento del DOM obtenido de un evento.
 * @param {string} interactionButtonClass Clase del botón con el que se puede
 * interactuar.
 * @returns {bool} El objeto es un botón de interacción o no.
 */
const isInteractionButton = ({ target, interactionButtonClass }) => {
  return (
    target.tagName === "BUTTON" &&
    target.classList.contains(`${interactionButtonClass}`)
  );
};

/**
 * El elemento es hijo de un botón de interacción.
 *
 * Se utiliza el el método closest, el cual, obtiene el elemento padre más
 * cercano al elemento actual. Buscamos que exista un padre que sea un botón con
 * la clase que indica la interacción.
 *
 * Esto significaría que se trata de un elemento hijo de un botón, tal como un
 * SVG dentro o cualquier otro elemento.
 * @param {} target Elemento del DOM obtenido de un evento.
 * @param {string} interactionButtonClass Clase del botón con el que se puede
 * interactuar.
 * @returns {bool} El elemento es hijo de un botón de interacción o no.
 */
const isSonOfButton = ({ target, interactionButtonClass }) => {
  return !(target.closest(`button.${interactionBtnClass}`) === null);
};

const getInteractionElements = ({ interactionForm }) => {
  /* Botones en donde se da tanto like como dislike. */
  const buttons = {
    like: interactionForm.querySelector(
      `button.${interactionBtnClass}[name="like"]`,
    ),
    dislike: interactionForm.querySelector(
      `button.${interactionBtnClass}[name="dislike"]`,
    ),
  };

  /* Elementos que guardan los datos numéricos de las interacciones. */
  const interactionData = {
    likes: interactionForm.querySelector(`data[title="likes-number"]`),
    dislikes: interactionForm.querySelector(`data[title="dislikes-number"]`),
  };

  return [buttons, interactionData];
};

const postCommentInteraction = ({
  event,
  isUserLoggedIn,
  userId,
  controllerUrl,
  interactionFormClass,
  interactionBtnClass,
}) => {
  const target = event.target;
  const isSon = isSonOfButton({ target, interactionBtnClass });
  const isButton = isInteractionButton({ target, interactionBtnClass });

  // Si el usuario no está registrado o el target no existe.
  if (!target || !isUserLoggedIn) return;
  // Si no es botón aún puede ser el hijo.
  if (!isButton && !isSon) return;

  // Evitar que se recargue la página si se presiona el botón.
  event.preventDefault();

  /**
   * Formulario padre en donde se encuentran los datos del comentario.
   */
  const form = target.closest(`form.${interactionFormClass}`);
  /**
   * https://developer.mozilla.org/en-US/docs/Web/API/FormData/FormData
   *
   * Obtener todos los campos del formulario.
   */
  const formData = new FormData(form);
  [buttons, interactionData] = getInteractionElements({
    interactionForm: form,
  });
  const comentarioPeliculaId = formData.get("comentario_pelicula_id");
  const queryParams = {
    comentario_pelicula_id: comentarioPeliculaId,
    usuario_id: userId,
  };
  // const getUrl = `${controllerUrl}?comentario_pelicula_id=${comentarioPeliculaId}&usuario_id=${userId}`;
  const getUrl = appendGetParamsToUrl({
    url: controllerUrl,
    paramsObj: queryParams,
  });

  /**
   * Botón al que se dio click.
   *
   * - Si el elemento actual es el hijo del botón, obtenemos el botón como tal.
   * - Si no, lo obtenemos del elemento actual.
   */
  let clickedButton = isSon
    ? target.closest(`button.${interactionBtnClass}`)
    : target;
  /**
   * Inicializamos el estado inicial del botón, por si ocurre un fallo en la
   * operación, devolverlo al estado inicial.
   */
  const clickedButtonBeginningState = clickedButton;
  /** Obtener botón al que dimos click para comparar con su interacción. */
  let currentInteraction = clickedButton.getAttribute("name");
  let dbCommentInteraction;
  /**
   * Indicar que se dio click al botón, aunque después se obtendrá el estado de
   * la base de datos.
   */
  clickedButton.classList.add(selectedClass);

  /**
   * https://www.youtube.com/watch?v=41VfSbuYBP0&ab_channel=midulive
   *
   * async/await ¿Qué problemas puede dar y cómo te ayuda Promise.all y
   * Promise.allSettled? (JavaScript)
   */
  /**
   * Obtener datos de la BD, no del DOM, por si fueron actualizados y el DOM
   * no. Obtener botones de like y dislike.
   *
   * https://stackoverflow.com/a/37534034/13562806 How to return data from
   * promise [duplicate]
   */
  getData(getUrl)
    .then((dbCommentInteraction) => {
      console.log("get response: ", dbCommentInteraction);

      [method, currentInteraction] = getCurrentInteractionMethod({
        dbCommentInteraction,
        currentInteraction,
      });

      // console.log("method", method);
      // console.log("currentInteraction", currentInteraction);
      formData.set("_method", method);
      formData.set("tipo", currentInteraction);

      // https://stackoverflow.com/a/69374442/13562806
      /**
       * Las promesas van anidadas, ya que, para hacer el post primero hay
       * que obtener los datos actuales, y luego de hacer el post, hay que
       * obtener los nuevos datos, que es lo único que se regresa para
       * manejar en el then del get inicial.
       *
       * Este return sendData en realidad devuelve la respuesta que recibe
       * el último getData anidado.
       *
       * Si no hacemos return de sendData, pero sí de getData, se realizará
       * el then del primer get aunque no se haya resuelto la promesa del
       * último getData.
       *
       * Una fuente que me ayudó:
       * - How to make promise.all wait for nested promise.all?
       * - https://stackoverflow.com/questions/36545464/how-to-make-promise-all-wait-for-nested-promise-all
       */
      return sendData(controllerUrl, Object.fromEntries(formData)).then(
        (response) => {
          console.log("post response: ", response);
          return getData(getUrl);
        },
      );
    })
    .then((lastGet) => {
      dbCommentInteraction = lastGet;
      console.log("datos actualizados - get response: ", dbCommentInteraction);
      updateCommentInteractions(
        selectedClass,
        dbCommentInteraction,
        buttons,
        interactionData,
      );
    })
    .catch((error) => {
      console.table("error:", error);
      // Si ocurre un error, regresar el botón del click al estado anterior.
      clickedButton = clickedButtonBeginningState;
    });
};
