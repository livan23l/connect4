class EMaths {
    static getProportion(targetRange, referenceRange, referenceValue, inverse = false) {
        const [minTr, maxTr] = targetRange;
        const [minRef, maxRef] = referenceRange;

        const trDiff = maxTr - minTr;
        const refDiff = maxRef - minRef;

        const refNorm = referenceValue - minRef;
        const normalized = inverse
            ? 1 - (refNorm / refDiff)
            : (refNorm / refDiff);

        return normalized * trDiff + minTr;
    }

    static randomFloat(min, max, decimals = 1) {
        const difference = max - min;
        const factor = 10 ** decimals;

        // Random calculation
        const random = Math.random() * difference;
        const randomFinal = (Math.round(random * factor)) / factor;

        return randomFinal + min;
    }

    static checkProbability(probability) {
        return (Math.random() <= probability);
    }
}

class Effect {
    #discs;
    #amountOfDiscs;
    #animationEnd

    #resetDisc(disc) {
        // Change the style values
        const left = EMaths.randomFloat(0, 100);
        disc.element.style.left = `${left}dvw`;
        disc.element.style.top = '0';

        // Change the rest of the properties
        disc.start = false;
        disc.reverse = false;
        disc.bounced = false;
        disc.top = 0;
        disc.velocity = disc.initialVelocity;
    }

    #generateDiscs() {
        for (let i = 0; i < this.#amountOfDiscs; i++) {
            // Create a new disc
            const disc = document.createElement('div');
            disc.classList.add('effect__disc');

            // Set the disc properties randomly
            const color = EMaths.checkProbability(0.5) ? 'blue' : 'red';

            const velocity = EMaths.randomFloat(1.5, 2.5);
            const loss = EMaths.randomFloat(0.2, 0.5);
            const startProbability = EMaths.randomFloat(0.02, 0.08);  // For dispersion
            const height = 48;

            // Add the attributes to the disc element
            disc.classList.add(`effect__disc--${color}`);
            disc.style.height = `${height}px`;

            // Add the disc to the body
            document.body.append(disc);

            // Push the disc to the array
            this.#discs.push({
                element: disc,
                initialVelocity: velocity,
                height,
                loss,
                startProbability,
            });

            // Reset the current disc
            this.#resetDisc(this.#discs[i]);
        }
    }

    #rainAnimation() {
        // Animation properties
        const acceleration = 0.9;
        const deceleration = 0.9;

        // Define the animation function
        const animation = () => {
            // Check if the animation ends
            if (this.#animationEnd) return;

            // Calculate the window size
            const windowHeight = window.innerHeight;

            // Make the corresponding actions for all the discs
            for (let i = 0; i < this.#amountOfDiscs; i++) {
                // Get the current disc
                const disc = this.#discs[i];

                // Start validations
                if (!disc.start) {
                    // Check if it can start now
                    if (!EMaths.checkProbability(disc.startProbability)) continue;

                    disc.start = true;
                }

                // Increase or decrease the top property
                if (disc.reverse) {
                    // Decrease the velocity subtracting the deceleration
                    disc.velocity -= deceleration;

                    // Check if the disc is still going in reverse
                    if (disc.velocity < 0) {
                        disc.reverse = false;
                        disc.velocity = 0;
                    }

                    // Apply the velocity to the top
                    disc.top -= disc.velocity;
                } else {
                    // Increase the top adding the velocity
                    disc.velocity += acceleration;
                    disc.top += disc.velocity;

                    // Make actions depending on the 'bounced' value
                    if (disc.bounced) {
                        if (disc.top - disc.height >= windowHeight) {
                            this.#resetDisc(disc);
                            continue;
                        }
                    } else {
                        // Check if the disc has reach the page bottom
                        if (disc.top > windowHeight) {
                            disc.top = windowHeight;
                            disc.reverse = true;
                            disc.bounced = true;

                            // Decrease the velocity according to the loss
                            disc.velocity = disc.velocity - (disc.velocity * disc.loss);
                        }
                    }
                }

                // Apply the resulting top
                disc.element.style.top = `${disc.top}px`;
            }

            requestAnimationFrame(animation);
        }

        // Start the animation
        requestAnimationFrame(animation);
    }

    #endEffectEvent() {
        window.addEventListener('effectEnd', () => {
            this.#animationEnd = true;
        });
    }

    #startEffectEvent() {
        window.addEventListener('effectStart', () => {
            this.#rainAnimation();
        });
    }

    constructor() {
        // Set the effect properties
        this.#discs = [];
        this.#amountOfDiscs = 15;
        this.#animationEnd = false;

        // Generate the discs
        this.#generateDiscs();

        // Set the effects events
        this.#startEffectEvent();
        this.#endEffectEvent();
    }
}

new Effect();