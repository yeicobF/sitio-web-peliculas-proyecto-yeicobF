/**
 * Interacciones con comentarios.
 */

const updateCommentInteractions = ({
  selectedClass,
  dbCommentInteraction,
  buttons,
  interactionData,
}) => {
  console.log("update comment interactions");
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
    console.table({ interaction: value });
    if (interaction === "user_interaction") {
      buttons[value].classList.add(selectedClass);
      continue;
    }
    interactionData[interaction].value = value;
    // https://attacomsian.com/blog/javascript-update-element-text
    interactionData[interaction].textContent = value;
  }
};
