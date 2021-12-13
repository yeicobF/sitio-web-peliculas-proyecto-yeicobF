const rotate = "rotate";
const rotate_180_class = "rotate-180";

/**
 * Función para activar el toggle del elemento.
 *
 * @param {string} mainSelector Selector del elemento principal que activará el
 * toggle.
 * @param {string} elementSelectorToToggle Elemento al que se le aplicará el
 * toggle. Este será relativo al elemento principal `mainSelector`.
 */
export function activateToggleRotate180(mainSelector, elementToToggle) {
  const mainElements = document.querySelectorAll(mainSelector);

  // console.log("rotar");
  // console.log(mainElements);

  // Agregamos el evento por cada elemento.
  mainElements.forEach((mainElement) =>
    mainElement.addEventListener("click", (event) => {
      event.preventDefault();

      // console.log(mainElement);
      // console.log(event.target);

      // Obtener elementos para voltear.
      const elementsToToggle = mainElement.querySelectorAll(elementToToggle);

      console.log(elementsToToggle);

      // A cada uno de los elementos que coincidan para hacer toggle.
      elementsToToggle.forEach((element) =>
        element.classList.toggle(rotate_180_class),
      );
    }),
  );
}
