import dayjs from 'dayjs';
import dayjsUTC from 'dayjs/plugin/utc';
import dayjsTimezone from 'dayjs/plugin/timezone';
import dayjsCustomParseFormat from 'dayjs/plugin/customParseFormat';
import dayjsLocale from 'dayjs/plugin/localizedFormat';
import dayjsUpdateLocale from 'dayjs/plugin/updateLocale';
import dayjsRelativeTime from 'dayjs/plugin/relativeTime';

import en from 'dayjs/locale/en';
import bs from 'dayjs/locale/bs';

dayjs.extend(dayjsUTC);
dayjs.extend(dayjsTimezone);
dayjs.extend(dayjsCustomParseFormat);
dayjs.extend(dayjsLocale);
dayjs.extend(dayjsUpdateLocale);
dayjs.extend(dayjsRelativeTime);

dayjs.updateLocale('bs', {
    relativeTime: {
        future: "za %s",
        past: "prije %s",
        s: 'par sekundi',
        m: "minutu",
        mm: function(number) {
            if (number > 10 && number < 20) return number + " minuta"
            if (number % 10 === 0) return number + " minuta"
            else if (number % 10 === 1 && number !== 11) return number + " minutu"
            else if (number % 10 > 1 && number % 10 < 5) return number + " minute"
            else if (number % 10 >= 5) return number + " minuta"
        },
        h: "sat",
        hh: function (number) {
            if (number > 10 && number < 20) return number + " sati"
            if (number % 10 === 0) return number + " sati"
            else if (number % 10 === 1 && number !== 11) return number + " sat"
            else if (number % 10 > 1 && number % 10 < 5) return number + " sata"
            else if (number % 10 >= 5) return number + " sati"
        },
        d: "dan",
        dd: function (number) {
            if (number % 10 === 1) return number + " dan";
            else return number + " dana";
        },
        M: "mjesec",
        MM: function (number) {
            if (number > 1 && number < 5) return number + " mjeseca";
            else if (number >= 5) return number + " mjeseci";
        },
        y: "godinu",
        yy: function (number) {
            if (number > 10 && number < 20) return number + " godina"
            if (number % 10 === 0) return number + " godina"
            else if (number % 10 === 1 && number !== 11) return number + " godinu"
            else if (number % 10 > 1 && number % 10 < 5) return number + " godine"
            else if (number % 10 >= 5) return number + " godina"
        }
    }
});

function getHRT(datetime, timezone, dateFormat, timeFormat)  {
    return dayjs(datetime).tz(timezone, false).format(dateFormat + ', ' + timeFormat);
}

function getTimeFromNow(datetime, locale, timezone) {
    return dayjs(datetime).locale(locale).tz(timezone).fromNow();
}

export function getHumanReadableTime() {
    return {
        dayjs,
        getHRT,
        getTimeFromNow,
    }
}
