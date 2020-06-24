import { dayNameFromInt } from "../time_functions";

class Day {
    constructor(day, available = [], confirmed = [], unconfirmed = []) {
        this.day = day;
        this.available_periods = available;
        this.confirmed_periods = confirmed;
        this.unconfirmed_periods = unconfirmed;
    }

    name() {
        return dayNameFromInt(this.day);
    }

    isActiveAt(time) {
        return this.available_periods.some(
            period => period.starts <= time && period.ends >= time
        );
    }
}

export { Day };
