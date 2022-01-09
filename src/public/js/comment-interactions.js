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
