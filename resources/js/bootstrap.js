import axios from 'axios';

import dayjs from 'dayjs'
import dayjsUTC from 'dayjs/plugin/utc';
import dayjsTimezone from 'dayjs/plugin/timezone';
import dayjsCustomParseFormat from 'dayjs/plugin/customParseFormat';
import dayjsLocale from 'dayjs/plugin/localizedFormat';
import en from 'dayjs/locale/en';
import bs from 'dayjs/locale/bs';

dayjs.extend(dayjsUTC);
dayjs.extend(dayjsTimezone);
dayjs.extend(dayjsCustomParseFormat);
dayjs.extend(dayjsLocale);

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.dayjs = dayjs;
