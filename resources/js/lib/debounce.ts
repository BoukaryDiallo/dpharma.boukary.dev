/**
 * Fonction debounce personnalisée pour retarder l'exécution d'une fonction
 * @param {Function} func - La fonction à débouncer
 * @param {number} wait - Le délai d'attente en millisecondes
 * @param {boolean} immediate - Si true, déclenche la fonction sur le front montant au lieu du front descendant
 * @returns {Function} - La fonction débouncée
 */
export function debounce(func, wait, immediate = false) {
    let timeout

    return function executedFunction(...args) {
        const later = () => {
            timeout = null
            if (!immediate) func.apply(this, args)
        }

        const callNow = immediate && !timeout

        clearTimeout(timeout)
        timeout = setTimeout(later, wait)

        if (callNow) func.apply(this, args)
    }
}

/**
 * Version avancée du debounce avec options supplémentaires
 * @param {Function} func - La fonction à débouncer
 * @param {number} wait - Le délai d'attente en millisecondes
 * @param {Object} options - Options supplémentaires
 * @returns {Function} - La fonction débouncée
 */
export function debounceAdvanced(func, wait, options = {}) {
    let timeout
    let previous = 0

    const { leading = false, trailing = true, maxWait = null } = options

    return function executedFunction(...args) {
        const now = Date.now()

        if (!previous && !leading) {
            previous = now
        }

        const remaining = wait - (now - previous)

        const later = () => {
            previous = leading ? 0 : Date.now()
            timeout = null
            if (trailing) {
                func.apply(this, args)
            }
        }

        if (remaining <= 0 || remaining > wait || (maxWait && now - previous >= maxWait)) {
            if (timeout) {
                clearTimeout(timeout)
                timeout = null
            }
            previous = now
            func.apply(this, args)
        } else if (!timeout && trailing) {
            timeout = setTimeout(later, remaining)
        }
    }
}

export default debounce
