import { intToTimeString, stringTimeToInt } from "../time_functions";

class TimePeriod {
    constructor(starts, ends) {
        this.starts = stringTimeToInt(starts);
        this.ends = stringTimeToInt(ends);
        this.starts_string = starts;
        this.ends_string = ends;
    }

    isValid() {
        return this.starts < this.ends;
    }

    asTimePair() {
        return [this.starts_string, this.ends_string];
    }

    asString() {
        return `${this.starts_string} - ${this.ends_string}`;
    }
}

export { TimePeriod };
