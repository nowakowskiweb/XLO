export function wordFlick(words, speed, targetHtml) {
    let i = 0;
    let offset = 0;
    let forwards = true;
    let skipCount = 0;
    const skipDelay = 10;

    setInterval(() => {
        if (forwards) {
            if (offset >= words[i].length) {
                ++skipCount;
                if (skipCount === skipDelay) {
                    forwards = false;
                    skipCount = 0;
                }
            }
        } else {
            if (offset === 0) {
                forwards = true;
                i++;
                offset = 0;
                if (i >= words.length) {
                    i = 0;
                }
            }
        }

        const part = words[i].substr(0, offset);
        if (skipCount === 0) {
            if (forwards) {
                offset++;
            } else {
                offset--;
            }
        }

        targetHtml.textContent = part;
    }, speed);
}
