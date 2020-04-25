import { intToTimeString } from "../time_functions";

class TimePeriod {
    constructor(starts, ends) {
        this.starts = parseInt(starts);
        this.ends = parseInt(ends);
    }

    isValid() {
        return this.starts < this.ends;
    }

    asTimePair() {
        return [this.starts, this.ends];
    }

    asString() {
        return `${intToTimeString(this.starts)} - ${intToTimeString(
            this.ends
        )}`;
    }
}

export { TimePeriod };
