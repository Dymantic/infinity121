import { dayNameFromInt } from "../time_functions";

class Day {
    constructor(day, periods = []) {
        this.day = day;
        this.periods = periods;
    }

    name() {
        return dayNameFromInt(this.day);
    }

    isActiveAt(time) {
        return this.periods.some(
            period => period.starts <= time && period.ends >= time
        );
    }
}

export { Day };
